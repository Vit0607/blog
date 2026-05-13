<?php

namespace App\Http\Controllers\Admin;

use App\Enums\UserRole;
use App\Http\Controllers\Controller;
use App\Services\Interfaces\UserServiceInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function __construct(
        private UserServiceInterface $userService
    ) {}

    public function index(): View
    {
        $users = $this->userService->getPaginated(10);

        return view('admin.users.index', compact('users'));
    }

    public function updateRole(Request $request, int $userId): JsonResponse
    {
        $role = UserRole::from($request->input('role'));

        $this->userService->changeRole($userId, $role);

        return response()->json(['message' => 'Роль обновлена']);
    }
}
