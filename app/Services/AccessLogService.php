<?php

namespace App\Services;

use App\Models\AccessLog;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class AccessLogService
{
    public function __construct(
        private readonly ImageService $imageService
    ) {}


    public function getAccessLogs(User $user): LengthAwarePaginator
    {
        $query = QueryBuilder::for(AccessLog::class)
            ->allowedFilters([
                AllowedFilter::partial('userName', 'user.name'),
                AllowedFilter::partial('createdAt', 'created_at'),
                'method'])
            ->allowedSorts(['created_at'])
            ->allowedIncludes(['user'])
            ->orderBy('created_at', 'desc');

        if ($user->hasRole('user')) {
            $query->where('user_id', $user->id);
        }


        return $query->paginate(10);
    }

    public function getAccessLogById(int $id): AccessLog
    {
        return AccessLog::findOrFail($id);
    }

    public function createAccessLog(array $data): AccessLog
    {
        $accessLog = new AccessLog();

        $accessLog->user_id = $data['user_id'];
        $accessLog->method  = $data['method'];
        $accessLog->image = "https://mateomartinezdev.blob.core.windows.net/vehicle-marketplace/CurGqfkoC6rgD0gIenA7gomhE2U2G6LK8YMe1TTY.jpg?se=3025-06-11T22:58:44Z&sv=2024-08-04&sr=b&sig=wb2Fb9YWZC0ufkGflWcx89J9mIXFR6dR7jip4t63t4A%3D&sp=r";

        /* if (isset($data['image'])) {

            $accessLog->image = $this->imageService->uploadImage($data['image']);
        } */

        $accessLog->save();

        return $accessLog;
    }

    public function updateAccessLog(int $id, array $data): AccessLog
    {
        $accessLog = $this->getAccessLogById($id);

        $accessLog->user_id = $data['user_id'] ?? $accessLog->user_id;
        $accessLog->method  = $data['method'] ?? $accessLog->method;

        $accessLog->save();

        return $accessLog;
    }

    public function deleteAccessLog(int $id): void
    {
        $accessLog = $this->getAccessLogById($id);

        $accessLog->delete();
    }
}
