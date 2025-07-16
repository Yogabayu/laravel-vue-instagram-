<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;
    protected $fillable = ['building_id', 'name', 'pic'];
    public function building()
    {
        return $this->belongsTo(Building::class);
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }
    public function annualAreaTargets()
    {
        return $this->hasMany(AnnualAreaTarget::class);
    }
    public function measurements()
    {
        return $this->hasMany(Measurement::class);
    }
    public function findings()
    {
        return $this->hasMany(Finding::class);
    }
}
