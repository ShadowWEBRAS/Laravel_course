<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'book_id',
        'rent_date',
        'return_date',
        'due_date',
    ];

    protected $casts = [
        'rent_date' => 'date',
        'return_date' => 'date',
        'due_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function isOverdue(): bool
    {
        return !$this->return_date && $this->due_date->isPast();
    }

    public function markAsReturned(): void
    {
        $this->update(['return_date' => now()]);
        if ($this->book) {
            $this->book->increaseAvailableCopies();
        }
    }
}
