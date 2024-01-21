<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Table: cars
*
* === Columns ===
 * @property int $id
 * @property int $owner_id
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
*
* === Relationships ===
 * @property-read \App\Models\User|null $owner
 * @property-read \App\Models\Car[]|\Illuminate\Database\Eloquent\Collection $associatedUsers
*/
class Car extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'owner_id',
        'name',
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
            related: User::class,
            table: 'user_associated_cars',
            foreignPivotKey: 'car_id',
            relatedPivotKey: 'user_id',
        );
    }
}
