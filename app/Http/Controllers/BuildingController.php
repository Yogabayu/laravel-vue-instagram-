<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Building;
use Illuminate\Http\Request;

class BuildingController extends Controller
{
    public function getAllBuilding()
    {
        $buildings = Building::with('areas')->get();
        return ResponseHelper::successRes('Get all buildings successfully', $buildings);
    }
}
