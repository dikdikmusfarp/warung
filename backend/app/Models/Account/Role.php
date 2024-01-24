<?php

namespace App\Models\Account;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'roles';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public static function getAdminRole()
    {
        return Role::where('slug', 'admin')->first();
    }

    public static function getCustomerRole()
    {
        return Role::where('slug', 'customer')->first();
    }

    public static function getAdminRoleId()
    {
        return self::getAdminRole() ? self::getAdminRole()->id : null;
    }

    public static function getCustomerRoleId()
    {
        return self::getCustomerRole() ? self::getCustomerRole()->id : null;
    }
}
