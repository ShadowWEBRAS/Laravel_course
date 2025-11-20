<?php

namespace App\Http\Controllers;

use App\Http\Requests\RentRequest;
use App\Http\Resources\RentResource;
use App\Models\Book;
use App\Models\Rent;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RentController extends Controller
{
    /**
     * Instantiate a new controller instance.
     */
    public function __construct()
    {
        // Убираем authorizeResource, используем ручную авторизацию
    }

    public function index(Request $request): AnonymousResourceCollection
    {
        // Авторизация для просмотра списка аренд
        $rents = Rent::with(['user', 'book'])
            ->when($request->user()->role === 'user', function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return RentResource::collection($rents);
    }

    public function store(RentRequest $request): JsonResponse
    {
        // Авторизация для создания аренды - только обычные пользователи
        if ($request->user()->isAdmin()) {
            return response()->json(['message' => 'Admins cannot rent books'], 403);
        }

        $book = Book::findOrFail($request->book_id);

        if (!$book->is_available) {
            return response()->json(['message' => 'Book is not available for rent'], 422);
        }

        $rent = Rent::create([
            'user_id' => $request->user()->id,
            'book_id' => $book->id,
            'rent_date' => now(),
            'due_date' => $request->due_date,
        ]);

        $book->update(['is_available' => false]);

        return response()->json([
            'message' => 'Book rented successfully',
            'data' => new RentResource($rent->load(['user', 'book']))
        ], 201);
    }

    public function show(Rent $rent): JsonResponse
    {
        // Авторизация для просмотра конкретной аренды
        $user = request()->user();
        if (!$user->isAdmin() && $user->id !== $rent->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json([
            'data' => new RentResource($rent->load(['user', 'book']))
        ]);
    }

    public function update(Request $request, Rent $rent): JsonResponse
    {
        // Авторизация для обновления аренды - только админы
        if (!$request->user()->isAdmin()) {
            return response()->json(['message' => 'Unauthorized. Admin access required.'], 403);
        }

        $request->validate([
            'return_date' => 'required|date|after_or_equal:rent_date',
        ]);

        $rent->update([
            'return_date' => $request->return_date,
        ]);

        if ($rent->book) {
            $rent->book->update(['is_available' => true]);
        }

        return response()->json([
            'message' => 'Book returned successfully',
            'data' => new RentResource($rent->load(['user', 'book']))
        ]);
    }

    public function destroy(Rent $rent): JsonResponse
    {
        // Авторизация для удаления аренды
        $user = request()->user();
        if (!$user->isAdmin() && $user->id !== $rent->user_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        if (!$rent->return_date && $rent->book) {
            $rent->book->update(['is_available' => true]);
        }

        $rent->delete();

        return response()->json(['message' => 'Rent record deleted successfully']);
    }
}
