<?php

namespace App\Livewire;

use App\Models\Post;
use App\Models\Attachment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class PostUpdate extends Component
{
    use WithFileUploads;

    public Post $post;

    // Form State
    public string $content = '';
    
    // Attachment Management
    public array $newAttachments = [];
    public array $newAttachmentPreviews = [];
    public array $existingAttachments = [];
    public array $attachmentsToDelete = [];

    // Validation Configuration
    public int $maxAttachments = 5;
    public int $maxFileSize = 5120; // 5MB

    protected function rules()
    {
        // We only validate new attachments, not existing ones.
        return [
            'content' => 'required|string|min:3|max:5000',
            'newAttachments.*' => ['nullable', 'file', 'max:' . $this->maxFileSize, 'mimes:jpg,jpeg,png,gif,webp,mp4,mov,avi,pdf,doc,docx,xls,xlsx,ppt,pptx,txt,zip,svg'],
            'newAttachments' => ['array', function ($attribute, $value, $fail) {
                if ((count($value) + count($this->existingAttachments)) > $this->maxAttachments) {
                    $fail('You can have a maximum of ' . $this->maxAttachments . ' attachments in total.');
                }
            }],
        ];
    }
    
    /**
     * Mount the component, authorize the user, and populate the form state.
     */
    public function mount(Post $post)
    {
        $this->post = $post;
        
        // Authorization: Only the post owner can edit.
        if (Auth::id() !== $this->post->user_id) {
            abort(403, 'You are not authorized to edit this post.');
        }

        $this->content = $post->content;

        // Load existing attachments into a display-friendly format.
        $this->existingAttachments = $post->attachments->map(function ($attachment) {
            return [
                'id' => $attachment->id,
                'file_url' => $attachment->file_url,
                'file_name' => $attachment->file_name,
                'is_image' => Str::startsWith($attachment->file_type, 'image'),
            ];
        })->all();
    }

    /**
     * Handle new file uploads and generate previews.
     */
    public function updatedNewAttachments()
    {
        $this->validateOnly('newAttachments.*');
        
        $this->newAttachmentPreviews = collect($this->newAttachments)->map(function ($file) {
            if ($file instanceof UploadedFile && $file->isValid()) {
                $isImage = Str::startsWith($file->getMimeType(), 'image/');
                return [
                    'name' => $file->getClientOriginalName(),
                    'is_image' => $isImage,
                    'temp_url' => $isImage ? $file->temporaryUrl() : null,
                ];
            }
            return null;
        })->filter()->values()->all();
    }
    
    /**
     * Mark an existing attachment for deletion.
     * It's removed from the UI and its ID is stored.
     */
    public function markAttachmentForDeletion(int $attachmentId)
    {
        $this->attachmentsToDelete[] = $attachmentId;
        $this->existingAttachments = array_filter($this->existingAttachments, fn($att) => $att['id'] !== $attachmentId);
    }
    
    /**
     * Remove a newly uploaded file before it's saved.
     */
    public function removeNewAttachment(int $index)
    {
        array_splice($this->newAttachments, $index, 1);
        array_splice($this->newAttachmentPreviews, $index, 1);
    }

    /**
     * The main update logic.
     */
    public function updatePost()
    {
        $this->validate();

        // 1. Update the post content
        $this->post->content = $this->content;
        $this->post->save();

        // 2. Delete marked attachments
        if (!empty($this->attachmentsToDelete)) {
            $attachments = Attachment::whereIn('id', $this->attachmentsToDelete)->get();
            foreach ($attachments as $attachment) {
                // Ensure the user owns this attachment indirectly via the post
                if ($attachment->post_id === $this->post->id) {
                    Storage::disk('public')->delete($attachment->file_path);
                    $attachment->delete();
                }
            }
        }

        // 3. Add new attachments
        foreach ($this->newAttachments as $file) {
             if ($file instanceof UploadedFile && $file->isValid()) {
                $path = $file->storeAs("attachments/user_{$this->post->user_id}/post_{$this->post->id}", $file->getClientOriginalName(), 'public');
                $this->post->attachments()->create([
                    'file_path' => $path,
                    'file_type' => Str::startsWith($file->getMimeType(), 'image/') ? 'image' : 'file',
                    'file_name' => $file->getClientOriginalName(),
                ]);
            }
        }

        session()->flash('success', 'Post updated successfully!');
        return redirect()->route('posts.show', $this->post);
    }

    public function render()
    {
        return view('livewire.post-update');
    }
}
