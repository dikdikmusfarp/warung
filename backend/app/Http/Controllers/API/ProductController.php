<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\ApiBaseController;
use App\Models\Account\Role;
use App\Models\Master\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends ApiBaseController
{
    public function index(Request $request)
    {
        $attributes = $request->all();
        $data = Product::filterQuery($attributes)
            ->paginate(
                isset($attributes['perPage']) ? $attributes['perPage'] : 10,
                ['*'],
                'page',
                isset($attributes['page']) ? $attributes['page'] : 1
            );
        try {
            return $this->sendResponse($data, 'List product');
        } catch (\Exception $e) {
            return $this->sendError($e->getMessage());
        }
    }

    public function store(Request $request)
    {
        try {
            if (Auth::user()->role_id && Auth::user()->role_id === Role::getAdminRoleId()) {
                $request->validate([
                    'name' => 'required|string',
                    'description' => 'required|string',
                    'price' => 'required|numeric',
                    'stock' => 'required|numeric',
                ]);
                if ($request->name) {
                    DB::beginTransaction();
                    $model = new Product();
                    $model->name = ucwords($request->name);
                    $model->description = $request->description;
                    $model->price = $request->price;
                    $model->stock = $request->stock;
                    $model->save();
                    DB::commit();
                    return $this->sendResponse($model, 'success made a stock');
                } else {
                    throw new \Exception("Error, make sure you fill all the form, 400");
                }
            } else {
                throw new \Exception("Access denied, 403");
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            $data = Product::find($id);
            return $this->sendResponse($data, 'stock detail');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        try {
            if (Auth::user()->role_id && Auth::user()->role_id === Role::getAdminRoleId()) {
                $request->validate([
                    'name' => 'required|string',
                    'description' => 'required|string',
                    'price' => 'required|numeric',
                    'stock' => 'required|numeric',
                ]);
                $model = Product::find($id);
                if ($model) {
                    DB::beginTransaction();
                    $model->name = ucwords($request->name);
                    $model->description = $request->description;
                    $model->price = $request->price;
                    $model->stock = $request->stock;
                    $model->save();
                    DB::commit();
                    return $this->sendResponse($model, 'success updated a product');
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
            if (Auth::user()->role_id && Auth::user()->role_id === Role::getAdminRoleId()) {
                $model = Product::find($id);
                if ($model) {
                    $model->delete();
                } else {
                    throw new \Exception("Error, undefined product, 404");
                }
            } else {
                throw new \Exception("Access denied, 403");
            }
            return $this->sendResponse($model, 'product has been deleted');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }
}
