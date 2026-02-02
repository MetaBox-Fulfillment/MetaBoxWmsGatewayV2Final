<?php

namespace App\Models\User;

use App\Domain\Enums\UserStatusEnum;
use App\Domain\Enums\UserTypeEnum;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * @method static create(array $array)
 */
class User extends Authenticatable implements MustVerifyEmail
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'pin_code',
        'type',
        'status',
        'partner_id',
        'worker_id',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'pin_code',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'pin_code' => 'hashed',
        'type' => UserTypeEnum::class,
        'status' => UserStatusEnum::class,
        'worker_id' => 'integer',
    ];
}
