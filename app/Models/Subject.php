<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = ['subject_name'];

    public function todo_items(): HasMany
    {
        return $this->hasMany(TodoItem::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }
}
