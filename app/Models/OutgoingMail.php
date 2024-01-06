<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class OutgoingMail extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'type',
        'number',
        'letter_number',
        'city',
        'date',
        'subject',
        'attachment',
        'receiver',
        're_location',
        'content',
        'file',
        'member_id',
    ];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}
