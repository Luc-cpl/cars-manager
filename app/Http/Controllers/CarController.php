<?php

namespace App\Http\Controllers;

use App\Services\User\CreateCarService;
use App\Services\User\DeleteCarService;
use App\Services\User\GetCarByIdService;
use App\Services\User\GetCarService;
use App\Services\User\RestoreCarService;
use Illuminate\Http\Request;

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
        $createCarService->handle(
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
    public function destroy(Request $request, DeleteCarService $service)
    {
        $carId = $request->route('carId');
        $service->handle($carId);
    }

    /**
     * Force delete the specified resource from storage.
     */
    public function forceDelete(Request $request, DeleteCarService $service)
    {
        $carId = $request->route('carId');
        $service->handle($carId, false);
    }

    /**
     * Restore the specified resource from storage.
     */
    public function restore(Request $request, RestoreCarService $service)
    {
        $carId = $request->route('carId');
        $service->handle($carId);
    }
}
