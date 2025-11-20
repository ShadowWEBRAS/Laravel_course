<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection
    {
        $this->authorize('viewAny', User::class);

        $users = User::when($request->has('role'), function ($query) use ($request) {
            $query->where('role', $request->get('role'));
        })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return UserResource::collection($users);
    }

    public function block(User $user): JsonResponse
    {
        $this->authorize('block', $user);

        $user->update(['is_blocked' => true]);

        return response()->json([
            'message' => 'User blocked successfully'
        ]);
    }

    public function unblock(User $user): JsonResponse
    {
        $this->authorize('block', $user);

        $user->update(['is_blocked' => false]);

        return response()->json([
            'message' => 'User unblocked successfully'
        ]);
    }
}
