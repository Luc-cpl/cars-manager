<?php

namespace App\Http\Controllers\Car;

use App\Http\Controllers\AbstractController;
use App\Services\Car\Associations\AssociateCarWithUserService;
use App\Services\Car\Associations\DisassociateCarWithUserService;
use App\Services\Car\Associations\GetCarAssociatedUsersService;
use Illuminate\Http\Request;

class CarAssociationController extends AbstractController
{
	/**
     * Display a listing of the resource.
     */
    public function index(Request $request, GetCarAssociatedUsersService $service)
    {
		  return $service->handle($request->route('carId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, AssociateCarWithUserService $createCarService)
    {
      $request->validate([
        'user_id' => ['exists:users,id'],
      ]);

      return $createCarService->handle(
        carId: $request->route('carId'),
        userId: $request->input('user_id') ?? $request->user()->id,
      );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, DisassociateCarWithUserService $service)
    {
      return $service->handle(
        carId: $request->route('carId'),
        userId: $request->route('userId') ?? $request->user()->id,
      );
    }
}
