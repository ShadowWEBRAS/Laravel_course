<?php

namespace App\Http\Controllers;

use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Models\ReadingList;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ReadingListController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $readingList = $request->user()
            ->readingList()
            ->with(['author'])
            ->orderBy('reading_lists.created_at', 'desc')
            ->paginate(10);

        return BookResource::collection($readingList);
    }

    public function store(Request $request, Book $book): JsonResponse
    {
        $user = $request->user();

        if ($user->readingList()->where('book_id', $book->id)->exists()) {
            return response()->json([
                'message' => 'Book is already in your reading list'
            ], 422);
        }

        $user->readingList()->attach($book->id);

        return response()->json([
            'message' => 'Book added to reading list successfully'
        ], 201);
    }

    public function destroy(Request $request, Book $book): JsonResponse
    {
        $request->user()->readingList()->detach($book->id);

        return response()->json([
            'message' => 'Book removed from reading list successfully'
        ]);
    }
}
