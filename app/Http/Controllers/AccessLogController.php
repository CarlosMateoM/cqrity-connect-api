<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAccessLogRequest;
use App\Http\Requests\UpdateAccessLogRequest;
use App\Http\Resources\AccessLogResource;
use App\Models\AccessLog;
use App\Services\AccessLogService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccessLogController extends Controller
{

    public function __construct(
        private readonly AccessLogService $accessLogService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResource
    {
        $accessLogs = $this->accessLogService->getAccessLogs($request->user());

        return AccessLogResource::collection(resource: $accessLogs);
    }

   
    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAccessLogRequest $request)
    {
        $data = $request->validated();

        $accessLog = $this->accessLogService->createAccessLog($data);

        return new AccessLogResource(resource: $accessLog);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id)
    {
        $accessLog = $this->accessLogService->getAccessLogById($id);

        return new AccessLogResource(resource: $accessLog);
    }
 

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAccessLogRequest $request, AccessLog $accessLog)
    {
        $data = $request->validated();

        $accessLog = $this->accessLogService->updateAccessLog($accessLog->id, $data);

        return new AccessLogResource(resource: $accessLog);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AccessLog $accessLog)
    {
        $this->accessLogService->deleteAccessLog($accessLog->id);

        return response()->json(['message' => 'Registro de acceso eliminado correctamente .'], 204);
    }
}
