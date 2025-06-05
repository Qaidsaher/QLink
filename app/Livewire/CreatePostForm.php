<?php

namespace App\Livewire;

use App\Models\Post; // Make sure this is imported
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str; // For Str::startsWith

class CreatePostForm extends Component
{
    use WithFileUploads;

    public string $content = '';
    public array $attachments = []; // This will hold an array of Livewire\TemporaryUploadedFile objects
    public array $attachmentPreviews = []; // To show previews of selected files

    // Validation rules
    protected function rules()
    {
        return [
            'content' => 'required_without_all:attachments|nullable|string|max:5000', // Content required if no attachments
            'attachments' => 'nullable|array|max:5', // Max 5 attachments for example
            'attachments.*' => 'nullable|file|max:20480|mimes:jpg,jpeg,png,gif,mp4,mov,mkv,avi,pdf,doc,docx,txt', // Max 20MB per file
        ];
    }

    protected $messages = [
        'content.required_without_all' => 'Please write something or add an attachment.',
        'attachments.max' => 'You can upload a maximum of 5 files.',
        'attachments.*.max' => 'Each file must be less than 20MB.',
        'attachments.*.mimes' => 'Unsupported file type. Allowed: jpg, png, gif, mp4, mov, pdf, doc, docx, txt.',
    ];

    public function updatedAttachments() // This Livewire hook runs when $attachments property is updated
    {
        $this->validateOnly('attachments'); // Validate only the attachments array
        $this->validateOnly('attachments.*'); // Validate each file in the attachments array

        $this->attachmentPreviews = []; // Clear previous previews
        foreach ($this->attachments as $key => $attachment) {
            if (!$attachment->isValid()) { // Check if file is valid (Livewire might set it to null on error)
                $this->addError("attachments.{$key}", "Invalid file uploaded.");
                continue;
            }
            $mimeType = $attachment->getMimeType();
            $previewData = [
                'name' => $attachment->getClientOriginalName(),
                'size' => round($attachment->getSize() / 1024, 2) . ' KB', // Size in KB
            ];

            if (Str::startsWith($mimeType, 'image/')) {
                $previewData['type'] = 'image';
                try {
                    $previewData['url'] = $attachment->temporaryUrl(); // Get temporary URL for preview
                } catch (\Exception $e) {
                    // Could not generate temporary URL (e.g., file too large, wrong type after initial check)
                    $this->addError("attachments.{$key}", "Could not preview image: " . $attachment->getClientOriginalName());
                    $previewData['type'] = 'file_error';
                    $previewData['icon'] = $this->getFileIcon($mimeType);
                }
            } elseif (Str::startsWith($mimeType, 'video/')) {
                $previewData['type'] = 'video';
                // Temporary URL for videos might not always work for direct display in <video>
                // Depending on browser. For preview, just showing an icon is safer.
                $previewData['icon'] = $this->getFileIcon($mimeType);
            } else {
                $previewData['type'] = 'file';
                $previewData['icon'] = $this->getFileIcon($mimeType);
            }
            $this->attachmentPreviews[] = $previewData;
        }
    }

    public function removeAttachment($index)
    {
        // Create a temporary array, remove the element, and reassign
        $tempAttachments = $this->attachments;
        if (isset($tempAttachments[$index])) {
            // No direct way to "delete" a TemporaryUploadedFile from Livewire's perspective before save,
            // but we can remove it from our array. Livewire handles cleanup.
            unset($tempAttachments[$index]);
            $this->attachments = array_values($tempAttachments); // Re-index
        }

        // Also remove from previews
        $tempPreviews = $this->attachmentPreviews;
        if (isset($tempPreviews[$index])) {
            unset($tempPreviews[$index]);
            $this->attachmentPreviews = array_values($tempPreviews); // Re-index
        }
    }

    private function getFileIcon($mimeType = null)
    {
        if ($mimeType) {
            if (Str::contains($mimeType, 'pdf')) return 'fas fa-file-pdf text-red-500';
            if (Str::contains($mimeType, ['word', 'document'])) return 'fas fa-file-word text-blue-500';
            if (Str::contains($mimeType, ['excel', 'spreadsheet'])) return 'fas fa-file-excel text-green-500';
            if (Str::contains($mimeType, ['presentation', 'powerpoint'])) return 'fas fa-file-powerpoint text-orange-500';
            if (Str::contains($mimeType, 'audio')) return 'fas fa-file-audio text-purple-500';
            if (Str::contains($mimeType, 'video')) return 'fas fa-file-video text-teal-500';
            if (Str::contains($mimeType, 'image')) return 'fas fa-file-image text-indigo-500';
        }
        return 'fas fa-file text-gray-500'; // Default
    }

    public function submit()
    {
        $this->validate(); // Validates all rules defined in rules() method

        $post = Auth::user()->posts()->create(['content' => $this->content]);

        if ($post) {
            foreach ($this->attachments as $attachmentFile) {
                if ($attachmentFile->isValid()) { // Double check validity
                    // Store in 'public/post_attachments/USER_ID/YEAR/MONTH'
                    $user = Auth::user();
                    $directory = "post_attachments/{$user->id}/" . now()->format('Y/m');
                    $path = $attachmentFile->store($directory, 'public');

                    $post->attachments()->create([
                        'file_path' => $path,
                        'file_type' => $attachmentFile->getMimeType(),
                        'file_name' => $attachmentFile->getClientOriginalName(),
                    ]);
                }
            }

            $this->content = '';
            $this->attachments = []; // This should clear Livewire's temporary files too
            $this->attachmentPreviews = [];

            $this->emitTo('post-feed', 'postCreated', $post->id); // Inform PostFeed a new post is created (pass ID)
            session()->flash('toast-message', 'Post created successfully!');
            return redirect()->route('feed');
        } else {
            session()->flash('toast-error', 'Failed to create post. Please try again.');
        }
    }

    public function render()
    {
        return view('livewire.create-post-form');
    }
}
