<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    /** @use HasFactory<\Database\Factories\AttachmentFactory> */
    use HasFactory;

    protected $fillable = [
        'post_id',
        'file_path',
        'file_type'
    ];

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    // app/Models/Attachment.php
    public function getFileUrlAttribute()
    {
        return Storage::url($this->file_path); // Prepends /storage/ to the path
    }
   

    public function fileUrl(): string
    {
        return Storage::url($this->file_path);
    }
    public function fileName(): string
    {
        return $this->file_name ?? basename($this->file_path);
    }
    protected $appends = ['file_url'];
}
