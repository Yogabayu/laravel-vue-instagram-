<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finding extends Model
{
    use HasFactory;
    protected $fillable = [
        'audit_id',
        'area_id',
        'category_id',
        'desc',
        'pic',
        'action_plan',
        'dateline',
        'status',
        'note',
        'isTopFinding'
    ];
    protected $casts = [
        'dateline' => 'date',
        'isTopFinding' => 'boolean',
    ];
    public function audit()
    {
        return $this->belongsTo(Audit::class);
    }
    public function area()
    {
        return $this->belongsTo(Area::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function photoBeforeFindings()
    {
        return $this->hasMany(PhotoBeforeFinding::class);
    }
    public function photoAfterFindings()
    {
        return $this->hasMany(PhotoAfterFinding::class);
    }
}
