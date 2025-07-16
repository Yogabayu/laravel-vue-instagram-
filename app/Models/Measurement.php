<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    use HasFactory;
    protected $fillable = [
        'audit_id',
        'area_id',
        'score',
    ];
    protected $casts = [
        'score' => 'decimal:2',
    ];
    public function area()
    {
        return $this->belongsTo(Area::class);
    }
    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }
}
