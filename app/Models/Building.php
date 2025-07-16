<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;
    protected $fillable = ['name'];
    public function areas()
    {
        return $this->hasMany(Area::class);
    }
    public function findings()
    {
        return $this->hasMany(Finding::class);
    }
}
