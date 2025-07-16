<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
    ];
    public function measurements()
    {
        return $this->hasMany(Measurement::class);
    }
    public function audits()
    {
        return $this->hasManyThrough(Audit::class, Measurement::class);
    }
    public function areas()
    {
        return $this->hasManyThrough(Area::class, Measurement::class);
    }
}
