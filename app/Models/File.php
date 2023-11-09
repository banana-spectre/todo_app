<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = ['file_name', 'file_path','subject_id'];

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }
}
