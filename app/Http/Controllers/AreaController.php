<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Area;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    public function getAllArea()
    {
        $areas = Area::all();
        return ResponseHelper::successRes('Get all areas successfully', $areas);
    }

    public function index()
    {
        try {
            $areas = Area::with('building')->get();
            return ResponseHelper::successRes('Get all areas successfully', $areas);
        } catch (\Throwable $th) {
            return ResponseHelper::errorRes('Error fetching areas', $th->getMessage());
        }
    }

    public function insert(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'pic' => 'required|string|max:255',
            'building_id' => 'required|exists:buildings,id',
        ]);

        try {
            $area = Area::create($request->all());
            return ResponseHelper::successRes('Area created successfully', $area);
        } catch (\Throwable $th) {
            return ResponseHelper::errorRes('Error creating area', $th->getMessage());
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'pic' => 'required|string|max:255',
            'building_id' => 'required|exists:buildings,id',
        ]);

        try {
            $area = Area::findOrFail($id);
            $area->update($request->all());
            return ResponseHelper::successRes('Area updated successfully', $area);
        } catch (\Throwable $th) {
            return ResponseHelper::errorRes('Error updating area', $th->getMessage());
        }
    }
}
