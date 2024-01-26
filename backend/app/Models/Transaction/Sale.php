<?php

namespace App\Models\Transaction;

use App\Models\Account\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Sale extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'sales';

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'deleted_at',
        'updated_at',
    ];

    public static function filterQuery($attributes)
    {
        if (Auth::user()->role_id === Role::getAdminRoleId()) {
            $orderDorection = isset($attributes['order_direction']) ? filter_var($attributes['order_direction'], FILTER_VALIDATE_BOOLEAN) ? 'DESC' : 'ASC' : 'ASC';
            if (isset($attributes['order_column'])) {
                $orderName = 'sales.' . $attributes['order_column'];
            } else {
                $orderName = 'sales.id';
            }
            return self::select(
                'sales.id',
                'sales.total',
                'users.name'
            )
                ->where(function ($query) use ($attributes) {
                    if (isset($attributes['search'])) {
                        $query->where('users.name', 'ilike', '%' . $attributes['search'] . '%')
                            ->orWhere('sales.total', 'ilike', '%' . $attributes['search'] . '%');
                    }
                })
                ->leftJoin('users', function ($join) {
                    $join->on('users.id', '=', 'sales.user_id');
                })
                ->orderByRaw($orderName . " " . $orderDorection . " NULLS LAST");
        } else if (Auth::user()->role_id === Role::getCustomerRoleId()) {
            $orderDorection = isset($attributes['order_direction']) ? filter_var($attributes['order_direction'], FILTER_VALIDATE_BOOLEAN) ? 'DESC' : 'ASC' : 'ASC';
            if (isset($attributes['order_column'])) {
                $orderName = 'sales.' . $attributes['order_column'];
            } else {
                $orderName = 'sales.id';
            }
            return self::select(
                'sales.id',
                'sales.total',
                'users.name'
            )
                ->where(function ($query) use ($attributes) {
                    if (isset($attributes['search'])) {
                        $query->where('users.name', 'ilike', '%' . $attributes['search'] . '%')
                            ->orWhere('sales.total', 'ilike', '%' . $attributes['search'] . '%');
                    }
                })
                ->leftJoin('users', function ($join) {
                    $join->on('users.id', '=', 'sales.user_id');
                })->where('user_id',Auth::user()->id)->orderByRaw($orderName . " " . $orderDorection . " NULLS LAST");
        }
    }
}
