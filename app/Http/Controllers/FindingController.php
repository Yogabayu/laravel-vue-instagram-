<?php

namespace App\Http\Controllers;

use App\Helpers\ResponseHelper;
use App\Models\Building;
use App\Models\Finding;
use Illuminate\Http\Request;

class FindingController extends Controller
{
    public function index()
    {
        try {
            $buildings = Building::with('areas', 'findings', 'findings.area', 'findings.photoBeforeFindings', 'findings.photoAfterFindings')->get();

            // Create a new "All Building" record with aggregated data
            $allBuilding = new \stdClass();
            $allBuilding->id = 0; // Special ID for the aggregated building
            $allBuilding->name = "All Building";
            $allBuilding->created_at = now();
            $allBuilding->updated_at = now();

            // Collect all areas from all buildings
            $allAreas = collect();
            foreach ($buildings as $building) {
                foreach ($building->areas as $area) {
                    $allAreas->push($area);
                }
            }
            $allBuilding->areas = $allAreas;

            // Collect all findings from all buildings
            $allFindings = collect();
            foreach ($buildings as $building) {
                foreach ($building->findings as $finding) {
                    $allFindings->push($finding);
                }
            }
            $allBuilding->findings = $allFindings;

            // Add the "All Building" to the beginning of the collection
            $buildingsCollection = collect([$allBuilding])->merge($buildings);

            return ResponseHelper::successRes('Get all finding successfully', $buildingsCollection);
        } catch (\Throwable $th) {
            return ResponseHelper::errorRes($th->getMessage(), 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // dd($request->all());
            $finding = Finding::create([
                'building_id' => $request->building_id,
                'area_id' => $request->area_id,
                'desc' => $request->description,
                'pic' => $request->pic,
                'action_plan' => $request->action_plan,
                'dateline' => $request->dateline,
                'status' => $this->formatStatus($request->status), // Convert status format
                'note' => $request->note,
                'isTopFinding' => $request->isTopFinding == 'true' ? true : false,
            ]);

            // Now handle photos
            if ($request->hasFile('beforePhotos')) {
                foreach ($request->file('beforePhotos') as $index => $photo) {
                    $path = $photo->store('findings/before', 'public');
                    $finding->photoBeforeFindings()->create([
                        'photo' => $path,
                    ]);
                }
            }

            if ($request->hasFile('afterPhotos')) {
                foreach ($request->file('afterPhotos') as $index => $photo) {
                    $path = $photo->store('findings/after', 'public');
                    $finding->photoAfterFindings()->create([
                        'photo' => $path,
                    ]);
                }
            }

            return ResponseHelper::successRes('Create finding successfully', $finding);
        } catch (\Exception $th) {
            return ResponseHelper::errorRes($th->getMessage(), 500);
        }
    }
    // Helper method to convert status
    private function formatStatus($status)
    {
        $statusMap = [
            'Open' => 'open',
            'In Progress' => 'in_progress',
            'Closed' => 'closed'
        ];

        return $statusMap[$status] ?? 'open'; // Default to 'open' if invalid
    }

    public function update(Request $request, $id)
    {
        // dd($request->all());
        try {
            $finding = Finding::findOrFail($id);

            // Helper function untuk convert null/empty string ke null yang benar
            $nullIfEmpty = function ($value) {
                return ($value === null || $value === '' || $value === 'null') ? null : $value;
            };

            $finding->building_id = $nullIfEmpty($request->building_id) ?? $finding->building_id;
            $finding->area_id = $nullIfEmpty($request->area_id) ?? $finding->area_id;
            $finding->desc = $request->description ?? $finding->desc;
            $finding->pic = $request->pic ?? $finding->pic;
            $finding->action_plan = $request->action_plan ?? $finding->action_plan;
            $finding->dateline = $request->dateline ?? $finding->dateline;
            $finding->status = $this->formatStatus($request->status) ?? $finding->status;
            $finding->note = $request->note ?? $finding->note;
            $finding->isTopFinding = $request->isTopFinding == 'true' ? true : false;

            $finding->save();

            // Handle file uploads...
            if ($request->hasFile('beforePhotos')) {
                foreach ($request->file('beforePhotos') as $photo) {
                    $path = $photo->store('findings/before', 'public');
                    $finding->photoBeforeFindings()->create([
                        'photo' => $path,
                    ]);
                }
            }

            if ($request->hasFile('afterPhotos')) {
                foreach ($request->file('afterPhotos') as $photo) {
                    $path = $photo->store('findings/after', 'public');
                    $finding->photoAfterFindings()->create([
                        'photo' => $path,
                    ]);
                }
            }

            return ResponseHelper::successRes('Update finding successfully', $finding);
        } catch (\Exception $th) {
            return ResponseHelper::errorRes($th->getMessage(), 500);
        }
    }

    public function destroy($id)
    {
        try {
            $finding = Finding::findOrFail($id);

            // Delete associated photos
            foreach ($finding->photoBeforeFindings as $photo) {
                if (file_exists(public_path('storage/' . $photo->photo))) {
                    unlink(public_path('storage/' . $photo->photo));
                }
                $photo->delete();
            }

            foreach ($finding->photoAfterFindings as $photo) {
                if (file_exists(public_path('storage/' . $photo->photo))) {
                    unlink(public_path('storage/' . $photo->photo));
                }
                $photo->delete();
            }

            $finding->delete();

            return ResponseHelper::successRes('Delete finding successfully', null);
        } catch (\Exception $th) {
            return ResponseHelper::errorRes($th->getMessage(), 500);
        }
    }
}
