<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use App\Models\Publication;
use App\Models\IncomingMail;
use App\Models\OutgoingMail;
use App\Models\ComunityExperience;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $numberOfMembers = Member::count();
        $numberOfUsers = User::count();
        $numberOfPublications = Publication::count();
        $numberOfExperience = ComunityExperience::count();
        $numberOfIncomingMails = IncomingMail::count();
        $numberOfOutgoingMails = OutgoingMail::count();

        return view('dashboard.index', compact('numberOfMembers', 'numberOfUsers', 'numberOfIncomingMails', 'numberOfOutgoingMails', 'numberOfPublications', 'numberOfExperience'));
    }
}
