<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CV extends Model
{
    use HasFactory;

    protected $table = 'cv';
    protected $fillable = ['name', 'file', 'candidate_id', 'created_at', 'updated_at'];
}
