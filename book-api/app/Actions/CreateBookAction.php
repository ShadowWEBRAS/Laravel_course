<?php

namespace App\Actions;

use App\Http\Requests\BookRequest;
use App\Models\Book;

class CreateBookAction
{
    public function execute(BookRequest $request): Book
    {
        return Book::create($request->validated());
    }
}
