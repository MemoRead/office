<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class ComunityExperience extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [
        'id',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
