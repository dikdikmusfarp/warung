<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiBaseController;
use App\Models\Account\Role;
use App\Models\Master\Product;
use App\Models\Transaction\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends ApiBaseController
{
    public function store(Request $request)
    {
        try {
            if (Auth::user()->role_id && Auth::user()->role_id === Role::getCustomerRoleId()) {
                $request->validate([
                    'product_id' => 'required|numeric',
                ]);
                $model = Cart::select('*')->where('product_id', $request->product_id)->where('user_id', Auth::user()->id)->first();
                DB::beginTransaction();
                if ($model) {
                    $model->amount += 1;
                    $model->save();
                } else {
                    $model = new Cart();
                    $model->product_id = $request->product_id;
                    $model->user_id = Auth::user()->id;
                    $model->amount = 1;
                    $model->save();
                }
                DB::commit();
                return $this->sendResponse($model, 'successfully placed in shopping cart');
            } else {
                throw new \Exception("Access denied, 403");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function show(Request $request)
    {
        try {
            $grandTotal = 0;
            if (Auth::user()->role_id && Auth::user()->role_id === Role::getCustomerRoleId()) {
                $data = Cart::select(
                    'carts.id',
                    'carts.user_id',
                    'carts.product_id',
                    'carts.amount',
                    'products.name',
                    'products.price',
                    )
                ->leftJoin('products', function ($join) {
                    $join->on('products.id', '=', 'carts.product_id');
                })
                ->where('user_id', Auth::user()->id)->get();
                if ($data) {
                    foreach ($data as $key => $value) {
                        $value['total'] = $value['amount'] * $value['price'];
                        $grandTotal += $value['total'];
                    }
                    $data = [
                        'data' => $data,
                        'grandTotal' => $grandTotal,
                    ];
                }
            }
            return $this->sendResponse($data, 'cart detail');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            if (Auth::user()->role_id && Auth::user()->role_id === Role::getCustomerRoleId()) {
                $request->validate([
                    'amount' => 'required|numeric',
                ]);
                $model = Cart::select('*')->where('product_id', $id)->where('user_id', Auth::user()->id)->first();
                $stock = Product::select('stock')->where('id', $id)->first();
                if ($model) {
                    if ($stock['stock'] > $request->amount) {
                        DB::beginTransaction();
                        if ($request->amount==0) {
                            $model->delete();
                        }
                        else {
                            $model->amount = $request->amount;
                            $model->save();
                        }
                        DB::commit();
                        return $this->sendResponse($model, 'success updated a product');
                    }
                    else {
                        throw new \Exception("Not enough stock");
                    }
                } else {
                    throw new \Exception("Error, undefined product, 404");
                }
            } else {
                throw new \Exception("Access denied, 403");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function delete($id)
    {
        try {
            if (Auth::user()->role_id && Auth::user()->role_id === Role::getCustomerRoleId()) {
                $model = Cart::select('*')->where('product_id', $id)->where('user_id', Auth::user()->id)->first();
                if ($model) {
                    $model->delete();
                } else {
                    throw new \Exception("Error, undefined product, 404");
                }
            } else {
                throw new \Exception("Access denied, 403");
            }
            return $this->sendResponse($model, 'product on cart has been deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }
}
