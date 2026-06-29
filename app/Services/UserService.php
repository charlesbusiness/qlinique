<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function list(array $filters = []): LengthAwarePaginator
    {
        return User::query()
            ->when($filters['search'] ?? null, function ($q, $search) {
                $q->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('staff_id', 'like', "%{$search}%");
                });
            })
            ->when($filters['role'] ?? null, fn($q, $r) => $q->where('role', $r))
            ->when(isset($filters['is_active']), fn($q, $v) => $q->where('is_active', $v))
            ->orderBy('created_at', 'desc')
            ->paginate(15);
    }

    public function create(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
            'phone' => $data['phone'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

    public function update(User $user, array $data): User
    {
        $fillable = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role' => $data['role'],
            'phone' => $data['phone'] ?? null,
            'is_active' => $data['is_active'] ?? true,
        ];

        if (! empty($data['password'])) {
            $fillable['password'] = Hash::make($data['password']);
            $fillable['password_reset_at'] = null;
        }

        $user->update($fillable);

        return $user->fresh();
    }

    public function delete(User $user): void
    {
        $user->delete();
    }
}
