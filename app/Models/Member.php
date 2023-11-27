<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory, HasUuids;

    protected $guarded = [
        'id',
    ];

    public function comunity_experience()
    {
        return $this->hasMany(ComunityExperience::class);
    }

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function incoming_mails()
    {
        return $this->hasMany(IncomingMail::class);
    }
    
    public function outgoing_mails()
    {
        return $this->hasMany(OutgoingMail::class);
    }
}
