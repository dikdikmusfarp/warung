<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'products';

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

    public static function filterQuery($attributes)
    {
        $orderDorection = isset($attributes['order_direction']) ? filter_var($attributes['order_direction'], FILTER_VALIDATE_BOOLEAN) ? 'DESC' : 'ASC' : 'ASC';
            if (isset($attributes['order_column'])) {
                $orderName = 'products.' . $attributes['order_column'];
            } else {
                $orderName = 'products.id';
            }
            return self::select('*')
                ->where(function ($query) use ($attributes) {
                    if (isset($attributes['search'])) {
                        $query->where('products.name', 'ilike', '%' . $attributes['search'] . '%')
                        ->orWhere('products.price', 'ilike', '%' . $attributes['search'] . '%');
                    }
                })
                ->orderByRaw($orderName . " " . $orderDorection . " NULLS LAST");
    }
}
