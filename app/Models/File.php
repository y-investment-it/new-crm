<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'fileable_type',
        'fileable_id',
        'path',
        'original_name',
        'mime',
        'size',
    ];

    public function fileable(): MorphTo
    {
        return $this->morphTo();
    }
}
