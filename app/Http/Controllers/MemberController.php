<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.members.members', [
            'members' => Member::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.members.create-member');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'expertise' => 'required',
            'nik' => 'required|unique:members',
            'birth_date' => 'required|date',
            'gender' => 'required',
            'address' => 'required',
            'phone' => 'required|unique:members',
            'email' => 'required|email:dns|unique:members',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|file|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            $photo = $request->file('photo');
            $photoName = Str::slug($request->input('name')) . '_' . time() . '.' . $photo->getClientOriginalExtension();
            $photoPath = $photo->storeAs('public/photos', $photoName);
            $validatedData['photo'] = 'photos/' . $photoName;
        }
        
        try {
            Member::create($validatedData);

            return redirect()->route('members.index')
                ->with('success', 'Member data has been added successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors('Failed to Add Member data. Please try again.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        return view('dashboard.members.member-details', [
            'member' => $member,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        return view('dashboard.members.edit-member', [
            'member' => $member,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        $rules = [
            'expertise' => 'required',
            'name' => 'required',
            'birth_date' => 'required|date',
            'gender' => 'required',
            'address' => 'required',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|file|max:2048',
        ];

        if($request->input('phone') != $member->phone) {
            $rules['phone'] = 'required|unique:members';
        }if($request->input('nik') != $member->nik) {
            $rules['nik'] = 'required|unique:members';
        }if($request->input('email') != $member->email) {
            $rules['email'] = 'required|email:dns|unique:members';
        }
        
        $validatedData = $request->validate($rules);
            
        if ($request->hasFile('photo')) {
            if($request->oldPhoto) {
                Storage::delete($request->oldPhoto);
            }

            $photo = $request->file('photo');
            $photoName = Str::slug($request->input('name')) . '_' . time() . '.' . $photo->getClientOriginalExtension();
            $photoPath = $photo->storeAs('public/photos', $photoName);
            
            $validatedData['photo'] = 'photos/' . $photoName;
        } else {
            unset($validatedData['photo']);
        }

        try {
            $member->update($validatedData);

            return redirect()->route('members.index')
                ->with('success', 'Member data has been Updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors('Failed to update member data. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        if($member->photo) {
            Storage::delete($member->photo);
        }

        Member::destroy($member->id);

        return redirect()->route('members.index')
                ->with('success', 'Member data has been deleted successfully.');
    }
}
