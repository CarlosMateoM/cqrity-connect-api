<?php

namespace App\Services;

use App\Events\OpenLock;
use App\Events\ReadUUID;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Validation\ValidationException;
use Spatie\QueryBuilder\QueryBuilder;

class UserService
{
    public function __construct(
        private readonly ImageService $imageService
    ) {}

    public function getUsers(): LengthAwarePaginator
    {
        return QueryBuilder::for(User::class)
            ->allowedIncludes(['accessLogs'])
            ->allowedFilters(['name', 'email'])
            ->allowedSorts(['name', 'email'])
            ->paginate(10);
    }

    public function getUserById(int $id): User
    {
        return User::findOrFail($id);
    }

    public function createUser(array $data): User
    {
        $user = new User();

        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->password =  $data['password'];

        if (isset($data['image'])) {

            $user->image = $this->imageService->uploadImage($data['image']);
        }

        $user->save();

        $user->assignRole($data['role']);

        return $user;
    }

    public function updateUser(int $id, array $data): User
    {
        $user = $this->getUserById($id);

        $user->name     =   $data['name'] ?? $user->name;
        $user->email    =   $data['email'] ?? $user->email;

        if (isset($data['password'])) {
            $user->password = bcrypt($data['password']);
        }

        if (isset($data['image'])) {

            $user->image = $this->imageService->uploadImage($data['image']);
        }

        $user->save();

        if (isset($data['role'])) {
            $user->syncRoles($data['role']);
        }

        return $user;
    }

    public function deleteUser(int $id): void
    {
        $user = $this->getUserById($id);

        $user->delete();
    }

    public function processUUIDAccess(string $uuid, string $method)
    {
        $user =  User::where('uuid', $uuid)->first();

        if (!$user) {

            event(new ReadUUID($uuid));

            throw ValidationException::withMessages([
                'uuid' => ['El UUID proporcionado no es válido.'],
            ]);
        }

        if ($method === 'APP') {

            event(new OpenLock($user->id));

        }

        return $user;

    }
}
