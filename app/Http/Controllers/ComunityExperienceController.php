<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use App\Models\ComunityExperience;

class ComunityExperienceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.experiences.index', [
            'experiences' => ComunityExperience::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.experiences.create', [
            'members' => Member::pluck('name', 'id')
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'type' => 'required',
            'locations' => 'required',
            'date' => 'required',
            'target' => 'required',
            'member_id' => 'required|exists:members,id',
            'results' => 'required',
            'descriptions' => 'required|max:255',
            'notes' => 'required|max:255',
        ]);
    
        ComunityExperience::create($validatedData);
    
        return redirect('/dashboard/experiences')->with('success', 'New Experience has Recorded successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(ComunityExperience $comunityExperience)
    {
        $member = Member::where('id', $comunityExperience->member_id)->first();
        
        return view('dashboard.experiences.details', [
            'exp' => $comunityExperience,
            'member' => $member
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ComunityExperience $comunityExperience)
    {
        return view('dashboard.experiences.edit', [
            'exp' => $comunityExperience,
            'members' => Member::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ComunityExperience $comunityExperience)
    {
        $validatedData = $request->validate([
            'name' => 'nullable',
            'type' => 'nullable',
            'locations' => 'nullable',
            'date' => 'nullable',
            'target' => 'nullable',
            'member_id' => 'nullable|exists:members,id',
            'results' => 'nullable',
            'descriptions' => 'nullable|max:255',
            'notes' => 'nullable|max:255',
        ]);
    
        $comunityExperience->update($validatedData);
    
        return redirect('/dashboard/experiences')->with('success', 'New Experience has Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ComunityExperience $comunityExperience)
    {
        ComunityExperience::destroy($comunityExperience->id);
        return redirect()->route('experiences.index')
                ->with('success', 'Sellected Experiences data has been deleted successfully.');
    }
}
