<?php

namespace App\Services;

use App\Models\AccessLog;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

class AccessLogService
{
    public function __construct(
        private readonly ImageService $imageService
    ) {}


    public function getAccessLogs(): LengthAwarePaginator
    {
        return QueryBuilder::for(AccessLog::class)
            ->allowedFilters(['user_id', 'ip_address'])
            ->allowedSorts(['created_at'])
            ->paginate(10);
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

        if (isset($data['image'])) {

            $accessLog->image = $this->imageService->uploadImage($data['image']);
        }

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
