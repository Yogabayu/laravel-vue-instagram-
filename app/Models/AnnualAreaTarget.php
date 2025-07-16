<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnualAreaTarget extends Model
{
    use HasFactory;
    protected $fillable = [
        'area_id',
        'year',
        'target',
    ];
    protected $casts = [
        'year' => 'string',
        'target' => 'string',
    ];
    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
