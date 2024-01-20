<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'owner_id'
    ];

    /**
     * Get the user that owns the car.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * Get the users for the car.
     */
    public function associatedUsers(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Car::class,
            table: 'user_associated_cars',
            foreignPivotKey: 'car_id',
            relatedPivotKey: 'user_id',
        );
    }
}
