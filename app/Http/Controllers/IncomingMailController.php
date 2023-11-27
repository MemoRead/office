<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\IncomingMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class IncomingMailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.mailbox.incoming-mails', [
            'mails' => IncomingMail::with('member')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.mailbox.create-incoming-mail', [
            'members' => Member::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'number' => 'required',
            'date' => 'required',
            'sender' => 'required',
            'content' => 'required|max:255',
            'type' => 'required',
            'subject' => 'required',
            'member_id' => 'required|exists:members,id',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $validatedData['content'] = strip_tags($request->content);
    
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $originalName = $file->getClientOriginalName();

            $incomingMail = new IncomingMail();
            $incomingMail->number = $validatedData['number'];
            $number = $incomingMail->number; // Get the number from the database column

            // Replace forward slash (/) and backslash (\) with underscore (_)
            $newNumber = str_replace(['/', '\\'], '_', $number);

            $fileName = $newNumber . '_' . time() . '.' . $extension;
            $filePath = $file->storeAs('public/files', $fileName);
            
            $validatedData['file'] = 'files/' . $fileName;
        }
    
        // Save the incoming mail record to the database
        IncomingMail::create($validatedData);
    
        return redirect('/dashboard/mails/incoming-mails')->with('success', 'Incoming mail created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(IncomingMail $incomingMail)
    {
        $fileName = $incomingMail->file;
        $fileName = str_replace('files/', '', $fileName);

        $encodeFileName = urlencode($fileName);

        $fileUrl = asset('storage/files/' . $encodeFileName);
    
        return view('dashboard.mailbox.incoming-mail-details', [
            'mail' => $incomingMail,
            'fileName' => $fileName,
            'fileUrl' => $fileUrl
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(IncomingMail $incomingMail)
    {
        return view('dashboard.mailbox.edit-incoming-mail', [
            'mail' => $incomingMail,
            'members' => Member::all(),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, IncomingMail $incomingMail)
    {
        $rules = [
            'date' => 'nullable|date',
            'sender' => 'nullable',
            'content' => 'nullable',
            'type' => 'nullable',
            'subject' => 'nullable',
            'member_id' => 'nullable|exists:members,id',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ];
    
        if ($request->input('content') != $incomingMail->content) {
            $rules['content'] = 'required|max:255';
            $validatedData['content'] = strip_tags($request->content);
        }

        if ($request->input('number') != $incomingMail->number) {
            $rules['number'] = 'required|unique:incoming_mails,number,' . $incomingMail->id;
        }
    
        $validatedData = $request->validate($rules);
        $validatedData['content'] = strip_tags($request->content);
    
        if ($request->hasFile('file')) {
            if ($request->oldFile) {
                Storage::delete($request->oldFile);
            }
    
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $number = $incomingMail->number;

            $newNumber = str_replace(['/', '\\'], '_', $number);
    
            $fileName = $newNumber . '_' . time() . '.' . $extension;
            $filePath = $file->storeAs('public/files', $fileName);
    
            $validatedData['file'] = 'files/' . $fileName;
        } else {
            unset($validatedData['file']);
        }
    
        try {
            $incomingMail->update($validatedData);
    
            return redirect()->route('incoming-mails.index')
                ->with('success', 'Incoming mail updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors('Failed to update IncomingMails data. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(IncomingMail $incomingMail)
    {
        if($incomingMail->file) {
            Storage::delete($incomingMail->file);
        }

        IncomingMail::destroy($incomingMail->id);

        return redirect('/dashboard/mails/incoming-mails')->with('success', 'IncomingMails data has been deleted successfully.');
    }
}
