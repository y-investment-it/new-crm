<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function listAgents(): JsonResponse
    {
        $users = User::query()
            ->whereIn('role', ['agent', 'manager'])
            ->where('active', true)
            ->orderBy('name')
            ->get(['id', 'name', 'role']);

        return response()->json($users);
    }
}
