<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhotoAfterFinding extends Model
{
    use HasFactory;
    protected $fillable = [
        'finding_id',
        'photo',
    ];
    public function finding()
    {
        return $this->belongsTo(Finding::class);
    }
}
