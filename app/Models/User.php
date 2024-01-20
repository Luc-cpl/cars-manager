<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

/**
 * Table: users
*
* === Columns ===
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Carbon\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property \Carbon\Carbon|null $deleted_at
*
* === Relationships ===
 * @property-read \App\Models\Car[]|\Illuminate\Database\Eloquent\Collection $ownedCars
 * @property-read \App\Models\Car[]|\Illuminate\Database\Eloquent\Collection $associatedCars
 * @property-read \Laravel\Sanctum\PersonalAccessToken|null $tokens
 * @property-read \Illuminate\Notifications\DatabaseNotification|null $notifications
*/
class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable , SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims(): array
    {
        return [];
    }

    /**
     * The user owned cars.
     */
    public function ownedCars(): HasMany
    {
        return $this->hasMany(Car::class, 'owner_id', 'id');
    }

    /**
     * The cars associated with the user.
     */
    public function associatedCars(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Car::class,
            table: 'user_associated_cars',
            foreignPivotKey: 'user_id',
            relatedPivotKey: 'car_id',
        );
    }
}
