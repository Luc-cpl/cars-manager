<?php

namespace App\Http\Controllers\Car;

use App\Http\Controllers\AbstractController;
use App\Services\Car\Associations\AssociateCarWithUserService;
use App\Services\Car\Associations\DisassociateCarWithUserService;
use App\Services\Car\Associations\GetCarAssociatedUsersService;
use Illuminate\Http\Request;

class CarAssociationController extends AbstractController
{
    public function __construct(
        private GetCarAssociatedUsersService $getService
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return $this->getService->handle($request->route('carId'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, AssociateCarWithUserService $createCarService)
    {
        $request->validate([
            'user_id' => ['exists:users,id'],
        ]);

        $createCarService->handle(
            carId: $request->route('carId'),
            userId: $request->input('user_id') ?? $request->user()->id,
        );

        return $this->getService->handle($request->route('carId'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, DisassociateCarWithUserService $service)
    {
        $service->handle(
            carId: $request->route('carId'),
            userId: $request->route('userId') ?? $request->user()->id,
        );

        $this->getService->handle($request->route('carId'));
    }
}
