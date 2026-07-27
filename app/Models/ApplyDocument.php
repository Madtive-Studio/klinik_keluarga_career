<?php

namespace App\Models;

use App\Enums\DocumentType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplyDocument extends Model
{
    protected $table = 'apply_documents';

    protected $fillable = [
        'apply_id',
        'document_id',
        'type',
    ];

    protected $casts = [
        'type' => DocumentType::class,
    ];

    public function apply(): BelongsTo
    {
        return $this->belongsTo(Apply::class);
    }

    public function document(): BelongsTo
    {
        return $this->belongsTo(Document::class);
    }
}
