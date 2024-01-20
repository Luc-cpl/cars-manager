<?php

namespace App\Http\Controllers;

use App\Services\Car\CreateCarService;
use App\Services\Car\DeleteCarService;
use App\Services\Car\GetCarByIdService;
use App\Services\Car\GetCarService;
use App\Services\Car\RestoreCarService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use InvalidArgumentException;

class CarController extends AbstractController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request, GetCarService $service)
    {
        return $service->handle(
            query: $request->query(),
            deleted: $request->query('deleted', false)
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, CreateCarService $createCarService)
    {
        return $createCarService->handle(
            ownerId: $request->user()->id
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
        try { 
            $carId = $request->route('carId');
            $service->handle($carId);
        } catch (InvalidArgumentException) {
            return response()->json([
                'message' => 'Car not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Car deleted'
        ], 200);
    }

    /**
     * Force delete the specified resource from storage.
     */
    public function forceDelete(Request $request, DeleteCarService $service): JsonResponse
    {
        try { 
            $carId = $request->route('carId');
            $service->handle($carId, false);
        } catch (InvalidArgumentException) {
            return response()->json([
                'message' => 'Car not found'
            ], 404);
        }

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
