<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\OutgoingMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class OutgoingMailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.mailbox.outgoing-mails', [
            'mails' => OutgoingMail::with('member')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.mailbox.create-outgoing-mail', [
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
            'receiver' => 'required',
            'content' => 'required',
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

            $outgoingMail = new OutgoingMail();
            $outgoingMail->number = $validatedData['number'];
            $number = $outgoingMail->number; // Get the number from the database column

            // Replace forward slash (/) and backslash (\) with underscore (_)
            $newNumber = str_replace(['/', '\\'], '_', $number);

            $fileName = $newNumber . '_' . time() . '.' . $extension;
            $filePath = $file->storeAs('public/files', $fileName);
            
            $validatedData['file'] = 'files/' . $fileName;
        }
    
        // Save the incoming mail record to the database
        OutgoingMail::create($validatedData);
    
        return redirect('/dashboard/mails/outgoing-mails')->with('success', 'Outgoing mail created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(OutgoingMail $outgoingMail)
    {
        $fileName = $outgoingMail->file;
        // $filePath = $incomingMail->file;
    
        // Menghapus "files/" dari nama file
        $fileName = str_replace('files/', '', $fileName);

        $encodeFileName = urlencode($fileName);

        $fileUrl = asset('storage/files/' . $encodeFileName);
    
        return view('dashboard.mailbox.outgoing-mail-details', [
            'mail' => $outgoingMail,
            'fileName' => $fileName,
            'fileUrl' => $fileUrl
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(OutgoingMail $outgoingMail)
    {
        return view('dashboard.mailbox.edit-outgoing-mail', [
            'mail' => $outgoingMail,
            'members' => Member::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, OutgoingMail $outgoingMail)
    {
        $rules = [
            'date' => 'nullable|date',
            'sender' => 'nullable',
            'content' => 'nullable',
            'type' => 'nullable',
            'receiver' => 'nullable',
            'subject' => 'nullable',
            'employee_id' => 'nullable|exists:employees,id',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ];

        if ($request->input('content') != $outgoingMail->content) {
            $validatedData['content'] = strip_tags($request->content);
        }
    
        if ($request->input('number') != $outgoingMail->number) {
            $rules['number'] = 'required|unique:outgoing_mails,number,' . $outgoingMail->id;
        }
    
        $validatedData = $request->validate($rules);
        $validatedData['content'] = strip_tags($request->content);
    
        if ($request->hasFile('file')) {
            if ($request->oldFile) {
                Storage::delete($request->oldFile);
            }
    
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
    
            $fileName = $outgoingMail->number . '_' . time() . '.' . $extension;
            $filePath = $file->storeAs('public/files', $fileName);
    
            $validatedData['file'] = 'files/' . $fileName;
        } else {
            unset($validatedData['file']);
        }
    
        try {
            $outgoingMail->update($validatedData);
    
            return redirect()->route('outgoing-mails.index')
                ->with('success', 'Outgoing mail updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors('Failed to update OutgoingMails data. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OutgoingMail $outgoingMail)
    {
        if($outgoingMail->file) {
            Storage::delete($outgoingMail->file);
        }

        OutgoingMail::destroy($outgoingMail->id);

        return redirect('/dashboard/mails/outgoing-mails')->with('success', 'ougoingMails data has been deleted successfully.');
    }
}
