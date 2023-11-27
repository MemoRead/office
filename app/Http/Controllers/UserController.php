<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Member;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.users.users', [
            'users' => User::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $members = Member::pluck('name', 'id');

        return view('dashboard.users.create-user', compact('members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'member_id' => 'required|exists:members,id|unique:users',
            'username' => 'required|unique:users',
            'password' => 'required|min:8',
            'role' => 'required',
        ]);
    
        $member = Member::findOrFail($validatedData['member_id']);
    
        User::create([
            'member_id' => $member->id,
            'name' => $member->name,
            'email' => $member->email,
            'photo' => $member->photo,
            'username' => $validatedData['username'],
            'password' => bcrypt($validatedData['password']),
            'role' => $validatedData['role'],
        ]);
    
        return redirect('/dashboard/users')->with('success', 'User has been created');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $member = Member::where('id', $user->id)->first();
    
        return view('dashboard.users.profile', compact('user', 'member'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('dashboard.users.edit-user', [
            'user' => $user,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $validatedData = $request->validate([
            'username' => 'required',
            'role' => 'required',
            'password' => 'nullable|min:8',
        ]);
    
        if ($validatedData['password']) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }
    
        $user->update($validatedData);
    
        return redirect('/dashboard/users')->with('success', 'User details have been updated successfully.');
    }

    public function editProfile(User $user)
    {
        return view('dashboard.users.edit-user', [
            'user' => $user,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $validatedData = $request->validate([
            'username' => 'nullable',
            'password' => 'nullable|min:8',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('photo')) {
            if($request->oldPhoto) {
                Storage::delete($request->oldPhoto);
            }

            $photo = $request->file('photo');
            $photoName = Str::slug($user->name) . '_' . time() . '.' . $photo->getClientOriginalExtension();
            $photoPath = $photo->storeAs('public/photos', $photoName);
            
            $validatedData['photo'] = 'photos/' . $photoName;
        } else {
            unset($validatedData['photo']);
        }
        
        $user->username = $validatedData['username'];
        if ($validatedData['password']) {
            $validatedData['password'] = bcrypt($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $user->update($validatedData);

        return redirect('/dashboard/profile/' . $user->id)->with('success', 'Profile updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if($user->photo) {
            Storage::delete($user->photo);
        }

        User::destroy($user->id);

        return redirect('/dashboard/users')->with('success', 'User data has been deleted successfully.');
    }
}
