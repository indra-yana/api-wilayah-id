<?php

namespace App\Http\Controllers;

use App\Src\Helpers\SendResponse;
use Illuminate\Http\Request;
use Laravolt\Indonesia\Models\Village;

class DesaController extends Controller
{
    public function all()
    {
        try {
            $desa = \Indonesia::paginateVillages();

            return SendResponse::success($desa);
        } catch (\Throwable $th) {
            return SendResponse::error([], $th->getMessage(), $th);
        }
    }

    public function show($id)
    {
        try {
            $desa = \Indonesia::findVillage($id);

            return SendResponse::success($desa);
        } catch (\Throwable $th) {
            return SendResponse::error([], $th->getMessage(), $th);
        }
    }

    public function create(Request $request)
    {
        try {
            $this->validate($request, [
                'code' => 'required|numeric|unique:' . config('laravolt.indonesia.table_prefix') . 'villages' . ',code',
                'district_code' => 'nullable|numeric',
                'name' => 'required|string',
            ]);

            Village::insert($request->all());

            return SendResponse::success();
        } catch (\Throwable $th) {
            return SendResponse::error([], $th->getMessage(), $th);
        }
    }

    public function update(Request $request)
    {
        try {
            $this->validate($request, [
                'id' => 'required|numeric',
                'name' => 'required|string',
            ]);

            $desa = Village::findOrFail($request->id);
            $desa->name = $request->name;
            $desa->save();

            return SendResponse::success();
        } catch (\Throwable $th) {
            return SendResponse::error([], $th->getMessage(), $th);
        }
    }

    public function delete(Request $request)
    {
        try {
            $desa = Village::findOrFail($request->id);
            $desa->delete();

            return SendResponse::success();
        } catch (\Throwable $th) {
            return SendResponse::error([], $th->getMessage(), $th);
        }
    }
}
