<?php

namespace App\Exports;

use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class RiwayatExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $exports = DB::table('fileviews')
            ->select('users.name', 'positions.name as position', DB::raw('COUNT(fileviews.id) AS views_count'))
            ->join('users', 'fileviews.user_uuid', '=', 'users.uuid')
            ->join('positions', 'users.position_id', '=', 'positions.id')
            ->groupBy('users.name', 'positions.name')
            ->orderByDesc('views_count')
            ->get();
        return $exports;
    }

    public function headings(): array
    {
        return [
            'Nama',
            'Jabatan',
            'views_count',
        ];
    }
}
