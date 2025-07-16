<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoBeforeFinding extends Model
{
    use HasFactory;
    protected $fillable = [
        'finding_id',
        'photo',
    ];
    protected $table = 'photo_before_findings';
    public function finding()
    {
        return $this->belongsTo(Finding::class);
    }
}
