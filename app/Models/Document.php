<?php

namespace App\Models;

use App\Enums\DocumentType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory;

    protected $table = 'documents';
    protected $primaryKey = 'id';
    protected $fillable = ['name', 'file', 'type', 'candidate_id', 'created_at', 'updated_at'];
    protected $casts = [
        'type' => DocumentType::class,
    ];
    protected $appends = ['file_url'];

    public function getFileUrlAttribute()
    {
        return $this->file ? Storage::url($this->file) : null;
    }
}
