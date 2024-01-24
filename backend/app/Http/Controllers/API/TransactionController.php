<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiBaseController;
use App\Models\Account\Role;
use App\Models\Master\Product;
use App\Models\Transaction\Cart;
use App\Models\Transaction\Sale;
use App\Models\Transaction\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends ApiBaseController
{
    public function store(Request $request)
    {
        try {
            if (Auth::user()->role_id && Auth::user()->role_id === Role::getCustomerRoleId()) {
                $products = Cart::select(
                    'carts.id',
                    'carts.user_id',
                    'carts.product_id',
                    'carts.amount',
                    'products.name',
                    'products.price',
                    )
                ->leftJoin('products', function ($join) {
                    $join->on('products.id', '=', 'carts.product_id');
                })->where('user_id', Auth::user()->id)->get();
                // dd($products);
                DB::beginTransaction();
                if ($products) {
                    $transaction = new Sale();
                    $transaction->user_id = Auth::user()->id;
                    $transaction->save();
                    foreach ($products as $key => $value) {
                        //check stock
                        $check = Product::find($value['product_id']);;
                        if ($check['stock']>$value['amount']) {
                            // new sale detail
                            $detail = new SaleDetail();
                            $detail->sale_id = $transaction->id;
                            $detail->product_id = $value['product_id'];
                            $detail->amount = $value['amount'];
                            $detail->price_at = $value['price'];
                            $detail->total = $value['price'] * $value['amount'];
                            $detail->save();
                            // update bill
                            $transaction->total += $detail->total;
                            $transaction->save();
                            // delete cart
                            $cart = Cart::find($value['id']);
                            $cart->delete();
                            // reducing stock
                            $check->stock -= $detail->amount;
                            $check->save();
                        } else {
                            DB::rollBack();
                            throw new \Exception("stock reduced or sold, 403");
                        }
                    }
                } else {
                    throw new \Exception("no item on shoping cart, 403");
                }
                DB::commit();
                return $this->sendResponse($transaction, 'successfully made a bill');
            } else {
                throw new \Exception("Access denied, 403");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }
}
