<?php

namespace App\Livewire; // Or App\Http\Livewire based on your Laravel version

use App\Models\Post;
use App\Models\User;
// use Illuminate\Support\Collection; // Not directly used as a type hint here
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Str;
use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Attributes\Title; //


class Search extends Component
{
    use WithPagination;

    public string $query = '';
    public ?string $filterType = null; // 'users', 'posts', 'hashtags', or null for all

    protected $queryString = [
        'query' => ['except' => '', 'as' => 'q'],
        'filterType' => ['except' => '', 'as' => 'type'],
    ];

    public function mount()
    {
        // Query and filterType are populated from URL by $queryString
    }

    public function updatedQuery()
    {
        $this->resetPage('usersPage');
        $this->resetPage('postsPage');
        $this->resetPage('hashtagsPage');
    }

    public function updatedFilterType()
    {
        $this->resetPage('usersPage');
        $this->resetPage('postsPage');
        $this->resetPage('hashtagsPage');
    }

    public function clearFilters()
    {
        $this->filterType = null;
        $this->updatedFilterType();
    }

    public function setFilterType($type)
    {
        if (in_array($type, ['users', 'posts', 'hashtags', null])) {
            $this->filterType = $type;
            $this->updatedFilterType();
        }
    }

    protected function getUsers(): LengthAwarePaginator
    {
        if (!empty($this->query) && (is_null($this->filterType) || $this->filterType === 'users')) {
            return User::where(function ($q) {
                $q->where('name', 'LIKE', '%' . $this->query . '%')
                    ->orWhere('username', 'LIKE', '%' . $this->query . '%');
            })
                ->select(['id', 'name', 'username', 'avatar', 'bio'])
                ->paginate(10, ['*'], 'usersPage');
        }
        return new LengthAwarePaginator([], 0, 10, 1, ['pageName' => 'usersPage']);
    }

    protected function getPosts(): LengthAwarePaginator
    {
        if (!empty($this->query) && (is_null($this->filterType) || $this->filterType === 'posts')) {
            return Post::with(['user:id,name,username,avatar', 'attachments'])
                ->where('content', 'LIKE', '%' . $this->query . '%')
                ->orderBy('created_at', 'desc')
                ->paginate(10, ['*'], 'postsPage');
        }
        return new LengthAwarePaginator([], 0, 10, 1, ['pageName' => 'postsPage']);
    }

    protected function getHashtags(): LengthAwarePaginator
    {
        if (!empty($this->query) && (is_null($this->filterType) || $this->filterType === 'hashtags')) {
            $cleanQuery = Str::lower(Str::replace('#', '', $this->query));
            $rawHashtagCounts = [];

            Post::where('content', 'LIKE', '%' . '#' . $cleanQuery . '%')
                ->select(['content'])
                ->chunk(500, function ($postsChunk) use (&$rawHashtagCounts, $cleanQuery) {
                    foreach ($postsChunk as $post) {
                        $postHashtags = method_exists($post, 'getHashtags') ? $post->getHashtags() : [];
                        foreach ($postHashtags as $tag) {
                            if (Str::contains($tag, $cleanQuery)) {
                                $rawHashtagCounts[$tag] = ($rawHashtagCounts[$tag] ?? 0) + 1;
                            }
                        }
                    }
                });

            arsort($rawHashtagCounts);

            $hashtags = collect($rawHashtagCounts)
                ->map(function ($count, $tag) {
                    return (object)[
                        'name' => '#' . $tag,
                        'posts_count' => $count,
                        'url' => route('search.index', ['q' => '#' . $tag])
                    ];
                });

            $pageName = 'hashtagsPage';
            $perPage = 15;
            $currentPage = Paginator::resolveCurrentPage($pageName);
            $currentPageItems = $hashtags->slice(($currentPage - 1) * $perPage, $perPage)->values();

            return new LengthAwarePaginator(
                $currentPageItems,
                $hashtags->count(),
                $perPage,
                $currentPage,
                ['path' => Paginator::resolveCurrentPath(), 'pageName' => $pageName]
            );
        }
        return new LengthAwarePaginator([], 0, 15, 1, ['pageName' => 'hashtagsPage']);
    }
    #[Title('seaerch results for :query')]

    public function render()
    {
        $usersResults = $this->getUsers();
        $postsResults = $this->getPosts();
        $hashtagsResults = $this->getHashtags();

        $totalResultsCount = 0;
        if (!empty($this->query)) { // Only calculate total if there's a query
            if (is_null($this->filterType)) {
                $totalResultsCount = $usersResults->total() + $postsResults->total() + $hashtagsResults->total();
            } elseif ($this->filterType === 'users') {
                $totalResultsCount = $usersResults->total();
            } elseif ($this->filterType === 'posts') {
                $totalResultsCount = $postsResults->total();
            } elseif ($this->filterType === 'hashtags') {
                $totalResultsCount = $hashtagsResults->total();
            }
        }

        return view('livewire.search', [
            'usersResults' => $usersResults,
            'postsResults' => $postsResults,
            'hashtagsResults' => $hashtagsResults,
            'totalResultsCount' => $totalResultsCount,
        ]); // NO ->layout() call is crucial here for embedding
    }
}
