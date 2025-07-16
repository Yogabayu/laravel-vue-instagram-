<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;
    protected $fillable = [
        'building_id',
        'periode',
        'start_date',
        'end_date',
    ];
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];
    public function building()
    {
        return $this->belongsTo(Building::class);
    }
    public function findings()
    {
        return $this->hasMany(Finding::class);
    }
}
