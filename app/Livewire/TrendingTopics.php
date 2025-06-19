<?php

namespace App\Livewire;

use App\Models\Post; // Import the Post model
use Illuminate\Support\Carbon;
use Livewire\Component;
use Illuminate\Support\Collection;

class TrendingTopics extends Component
{
    public Collection $trends; // Use Eloquent/Support Collection
    public int $initialCount = 5; // Number of trends to show initially
    public int $loadMoreCount = 5; // Number of trends to load on "show more"
    public int $displayCount;
    public bool $hasMoreTrends = true;
    public string $timePeriod = 'week'; // 'day', 'week', 'month', 'all'

    // For caching
    protected ?Collection $allFetchedTrends = null;
    protected ?string $trendsCacheKey = null;


    public function mount()
    {
        $this->displayCount = $this->initialCount;
        $this->trends = new Collection(); // Initialize as empty collection
        $this->loadTrends();
    }

    protected function calculateTrends()
    {
        // Cache key based on time period
        $this->trendsCacheKey = 'trending_topics_' . $this->timePeriod;

        // Attempt to get from cache first
        // If you have a more robust caching strategy (e.g., scheduled job), you might skip direct calculation here
        // and rely on the cache always being populated.
        // For this example, we calculate if not cached or if cache is stale.
        // A simple time-based cache for demo:
        // if (cache()->has($this->trendsCacheKey)) {
        //    return cache($this->trendsCacheKey);
        // }

        $hashtagCounts = [];

        // Define the date range for posts
        $startDate = match ($this->timePeriod) {
            'day' => Carbon::now()->subDay(),
            'month' => Carbon::now()->subMonth(),
            'all' => null, // No date limit
            default => Carbon::now()->subWeek(), // 'week'
        };

        // Query posts within the time period
        $postsQuery = Post::query()->select(['id', 'content', 'created_at']); // Select only needed fields

        if ($startDate) {
            $postsQuery->where('created_at', '>=', $startDate);
        }

        // Process posts in chunks to manage memory for large datasets
        $postsQuery->chunk(200, function ($postsChunk) use (&$hashtagCounts) {
            foreach ($postsChunk as $post) {
                $hashtagsInPost = $post->getHashtags(); // Uses the method from Post model
                foreach ($hashtagsInPost as $tag) {
                    if (!empty($tag)) { // Ensure tag is not empty
                        $hashtagCounts[$tag] = ($hashtagCounts[$tag] ?? 0) + 1;
                    }
                }
            }
        });

        // Sort hashtags by count in descending order
        arsort($hashtagCounts);

        // Format for display
        $formattedTrends = new Collection();
        foreach ($hashtagCounts as $tag => $count) {
            $formattedTrends->push([
                'name' => '#' . $tag,
                'posts_count' => $count,
                'url' => route('search.index', ['q' => '#' . $tag]) // Example route for searching by tag
            ]);
        }

        // Cache the result (e.g., for 1 hour)
        // cache()->put($this->trendsCacheKey, $formattedTrends, now()->addHour());

        return $formattedTrends;
    }


    public function loadTrends()
    {
        if (is_null($this->allFetchedTrends)) {
            $this->allFetchedTrends = $this->calculateTrends();
        }

        $this->trends = $this->allFetchedTrends->slice(0, $this->displayCount);
        $this->hasMoreTrends = $this->allFetchedTrends->count() > $this->displayCount;
    }

    public function loadMore()
    {
        if (!$this->hasMoreTrends) return;

        $this->displayCount += $this->loadMoreCount;
        $this->loadTrends();
    }

    public function setTimePeriod(string $period)
    {
        if (in_array($period, ['day', 'week', 'month', 'all'])) {
            $this->timePeriod = $period;
            $this->displayCount = $this->initialCount; // Reset display count
            $this->allFetchedTrends = null; // Force recalculation
            $this->loadTrends();
        }
    }

    public function formatPostCount($count)
    {
        if ($count >= 1000000) {
            return round($count / 1000000, 1) . 'M';
        }
        if ($count >= 1000) {
            return round($count / 1000, 1) . 'K';
        }
        return $count;
    }

    public function render()
    {
        return view('livewire.trending-topics');
    }
}
