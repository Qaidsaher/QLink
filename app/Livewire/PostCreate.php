<?php

namespace App\Livewire;

use App\Events\PostUpdated;
use App\Models\Post;
use App\Models\Attachment;
use App\Notifications\UserNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Boolean;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class PostCreate extends Component
{
    use WithFileUploads;

    public string $content = '';
    public array $attachments = [];
    public array $attachmentPreviews = [];
    public bool $showCreateForm = true;

    public int $maxAttachments = 5;
    public int $maxFileSize = 5120; // 5MB

    public bool $isPage = false;
    public function mount()
    {
        $routeName = request()->route()?->getName();

        if ($routeName === 'posts.create') {
            $this->isPage = true;
        } else {
            $this->isPage = false;
        }
    }

    protected function rules()
    {
        return [
            'content' => 'required|string|min:3|max:5000',
            'attachments.*' => [
                'nullable',
                'file',
                'max:' . $this->maxFileSize,
                'mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,svg' // Added svg
            ],
            'attachments' => 'array|max:' . $this->maxAttachments,
        ];
    }

    protected $messages = [
        'attachments.max' => 'You can upload a maximum of :max files.',
        'attachments.*.max' => 'Each file must be less than :max KB.',
        'attachments.*.mimes' => 'Unsupported file type selected.',
        'content.required' => 'The post content cannot be empty.',
        'content.min' => 'The post content must be at least :min characters.',
    ];

    public function updatedAttachments()
    {
        $this->validateOnly('attachments');
        $this->validateOnly('attachments.*');

        $currentPreviews = $this->attachmentPreviews; // Keep existing valid previews
        $newPreviews = [];

        foreach ($this->attachments as $index => $file) {
            if ($file instanceof UploadedFile && $file->isValid()) {
                $mimeType = $file->getMimeType();
                $extension = $file->getClientOriginalExtension();
                $fileIconType = $this->determineFileIconType($mimeType, $extension);

                $newPreviews[$index] = [
                    'name' => $file->getClientOriginalName(),
                    'type' => $mimeType,
                    'size' => round($file->getSize() / 1024, 2) . ' KB',
                    'is_image' => Str::startsWith($mimeType, 'image/'),
                    'temp_url' => Str::startsWith($mimeType, 'image/') ? $file->temporaryUrl() : null,
                    'file_icon_type' => $fileIconType,
                ];
            } elseif (isset($currentPreviews[$index])) {
                // If the file at this index is now invalid/removed by Livewire, but was previewed
                // this logic might need adjustment based on how Livewire handles array updates with WithFileUploads
                // For simplicity, we rebuild previews based on current valid $this->attachments
            }
        }
        // This ensures previews only for currently valid and selected files in $this->attachments
        $this->attachmentPreviews = collect($this->attachments)->map(function ($file, $index) {
            if ($file instanceof UploadedFile && $file->isValid()) {
                $mimeType = $file->getMimeType();
                $extension = $file->getClientOriginalExtension();
                return [
                    'name' => $file->getClientOriginalName(),
                    'type' => $mimeType,
                    'size' => round($file->getSize() / 1024, 2) . ' KB',
                    'is_image' => Str::startsWith($mimeType, 'image/'),
                    'temp_url' => Str::startsWith($mimeType, 'image/') ? $file->temporaryUrl() : null,
                    'file_icon_type' => $this->determineFileIconType($mimeType, $extension),
                ];
            }
            return null; // Or handle differently
        })->filter()->values()->all();
    }

    private function determineFileIconType(string $mimeType, string $extension): string
    {
        if (Str::startsWith($mimeType, 'image/')) return 'image';
        if (Str::startsWith($mimeType, 'video/')) return 'video';
        if (Str::startsWith($mimeType, 'audio/')) return 'audio';
        if ($mimeType === 'application/pdf') return 'pdf';
        if (in_array($extension, ['doc', 'docx', 'odt', 'rtf'])) return 'document';
        if (in_array($extension, ['xls', 'xlsx', 'ods', 'csv'])) return 'spreadsheet';
        if (in_array($extension, ['ppt', 'pptx', 'odp'])) return 'presentation';
        if (in_array($extension, ['zip', 'rar', 'tar', 'gz', '7z'])) return 'archive';
        if (Str::startsWith($mimeType, 'text/plain') || $extension === 'txt') return 'text';
        if ($extension === 'svg') return 'image'; // Treat SVG as an image for icon purposes
        return 'generic';
    }

    public function removeAttachment($index)
    {
        $currentAttachments = is_array($this->attachments) ? $this->attachments : [];
        if (isset($currentAttachments[$index])) {
            unset($currentAttachments[$index]);
            $this->attachments = array_values($currentAttachments);
            // Re-trigger preview update
            $this->updatedAttachments();
        }
    }

    public function savePost()
    {
        $this->validate();

        if (!Auth::check()) {
            session()->flash('error', 'You must be logged in to create a post.');
            return redirect()->route('login');
        }

        $post = Post::create([
            'user_id' => Auth::id(),
            'content' => $this->content,
        ]);

        $imageManager = new ImageManager(new Driver());

        if ($this->attachments) {
            foreach ($this->attachments as $file) {
                if ($file instanceof UploadedFile && $file->isValid()) {
                    $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                    $extension = $file->getClientOriginalExtension();
                    $safeFilename = Str::slug($originalName) . '-' . uniqid() . '.jpg'; // Force jpg

                    $directory = "attachments/user_" . Auth::id() . "/post_" . $post->id;
                    $path = $directory . '/' . $safeFilename;

                    // Check if image type
                    if (Str::startsWith($file->getMimeType(), 'image/')) {
                        try {
                            // Compress and resize the image
                            $compressed = $imageManager->read($file->getPathname())
                                ->scale(width: 1200)
                                ->toJpeg(quality: 85);

                            // Save compressed image
                            Storage::disk('public')->put($path, $compressed->toString());
                        } catch (\Exception $e) {
                            Log::error('Image compression failed: ' . $e->getMessage());
                            continue; // skip this file
                        }
                    } else {
                        // For non-images, store as-is
                        $path = $file->storeAs($directory, $safeFilename, 'public');
                    }

                    Attachment::create([
                        'post_id' => $post->id,
                        'file_path' => $path,
                        'file_type' => $this->determineStoredFileType($file->getMimeType(), $extension),
                        'file_name' => $file->getClientOriginalName(),
                    ]);
                }
            }
        }

        $this->resetForm();

        Auth::user()->notify(new UserNotification('post_created', $post, Auth::user()));
    }


    // This method determines the type string stored in the DB
    private function determineStoredFileType(string $mimeType, string $extension): string
    {
        if (Str::startsWith($mimeType, 'image/')) return 'image';
        if (Str::startsWith($mimeType, 'video/')) return 'video';
        if (Str::startsWith($mimeType, 'audio/')) return 'audio';
        if ($mimeType === 'application/pdf') return 'pdf';
        // You can be more generic for DB storage if preferred
        return 'file';
    }

    public function resetForm()
    {
        $this->content = '';
        $this->attachments = [];
        $this->attachmentPreviews = [];
        $this->resetErrorBag();
    }

    public function render()
    {
        return view('livewire.post-create');
    }
}
