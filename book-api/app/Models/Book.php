<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'description',
        'year',
        'available_copies',
    ];

    protected $casts = [
        'year' => 'integer',
        'available_copies' => 'integer',
    ];

    public function rents()
    {
        return $this->hasMany(Rent::class);
    }

    public function readingLists()
    {
        return $this->belongsToMany(User::class, 'reading_lists')
            ->withTimestamps();
    }

    public function isAvailable(): bool
    {
        return $this->available_copies > 0;
    }

    public function decreaseAvailableCopies(): void
    {
        if ($this->available_copies > 0) {
            $this->decrement('available_copies');
        }
    }

    public function increaseAvailableCopies(): void
    {
        $this->increment('available_copies');
    }
}
