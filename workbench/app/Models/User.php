<?php

declare(strict_types=1);

namespace Workbench\EdineiValdameri\Laravel\Audit\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * @property $email
 * @property $name
 */
class User extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['name', 'email'];
}
