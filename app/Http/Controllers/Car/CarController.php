<?php

namespace App\Http\Controllers\Car;

use App\Http\Controllers\AbstractController;
use App\Services\Car\CreateCarService;
use App\Services\Car\UpdateCarService;
use App\Services\Car\DeleteCarService;
use App\Services\Car\GetCarByIdService;
use App\Services\Car\GetCarService;
use App\Services\Car\RestoreCarService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CarController extends AbstractController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, GetCarService $service)
    {
        return $service->handle(
            query: $request->query()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, CreateCarService $service)
    {
        $request->validate([
			'name' => ['required', 'string', 'min:3', 'max:100'],
        ]);

        return $service->handle(
            ownerId: $request->user()->id,
            name: $request->input('name'),
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function update(Request $request, UpdateCarService $service)
    {
        $request->validate([
			'name' => ['required', 'string', 'min:3', 'max:100'],
        ]);

        return $service->handle(
            carId: $request->route('carId'),
            name: $request->input('name'),
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, GetCarByIdService $service)
    {
        $carId = $request->route('carId');
        return $service->handle($carId);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, DeleteCarService $service): JsonResponse
    {
        $carId = $request->route('carId');
        $service->handle($carId);

        return response()->json([
            'message' => 'Car deleted'
        ], 200);
    }

    /**
     * Force delete the specified resource from storage.
     */
    public function forceDelete(Request $request, DeleteCarService $service): JsonResponse
    {
        $carId = $request->route('carId');
        $service->handle($carId, false);

        return response()->json([
            'message' => 'Car deleted'
        ], 200);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Request $request, RestoreCarService $service): JsonResponse
    {
        $carId = $request->route('carId');
        $service->handle($carId);

        return response()->json([
            'message' => 'Car restored'
        ], 200);
    }
}
