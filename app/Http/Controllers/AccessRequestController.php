<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class AccessRequestController extends Controller
{
    public function __construct(
        private readonly UserService $userService
    ) {}


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $uuid = $request->input('uuid');
        $method = $request->input('method');

        $user = $this->userService->processUUIDAccess($uuid, $method);

        if ($method === 'APP') {
            return response()->json([
                'message' => 'Petición de acceso procesada',
            ]);
        }

        return response()->json([
            'userId' => $user->id
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
