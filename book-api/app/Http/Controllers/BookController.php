<?php

namespace App\Http\Controllers;

use App\Actions\CreateBookAction;
use App\Http\Requests\BookRequest;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class BookController extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Убираем authorizeResource, так как он конфликтует с публичными маршрутами
        // Будем использовать ручную авторизацию в методах
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        // Публичный доступ - никакой авторизации
        $books = Book::when($request->has('search'), function ($query) use ($request) {
            $search = $request->get('search');
            $query->where('title', 'like', "%{$search}%")
                ->orWhere('author', 'like', "%{$search}%");
        })
            ->when($request->has('available'), function ($query) use ($request) {
                if ($request->get('available') === 'true') {
                    $query->where('is_available', true);
                }
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return BookResource::collection($books);
    }

    public function store(BookRequest $request, CreateBookAction $createBook): JsonResponse
    {
        // Ручная авторизация для создания книги
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized. Admin access required.'], 403);
        }

        $book = $createBook->execute($request);

        return response()->json([
            'message' => 'Book created successfully',
            'data' => new BookResource($book)
        ], 201);
    }

    public function show(Book $book): BookResource
    {
        // Публичный доступ
        return new BookResource($book);
    }

    public function update(BookRequest $request, Book $book): JsonResponse
    {
        // Ручная авторизация для обновления книги
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized. Admin access required.'], 403);
        }

        $book->update($request->validated());

        return response()->json([
            'message' => 'Book updated successfully',
            'data' => new BookResource($book)
        ]);
    }

    public function destroy(Book $book): JsonResponse
    {
        // Ручная авторизация для удаления книги
        if (!request()->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized. Admin access required.'], 403);
        }

        $book->delete();

        return response()->json(['message' => 'Book deleted successfully']);
    }
}
