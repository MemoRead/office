<?php

namespace App\Http\Controllers;

use App\Models\Publication;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PublicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('dashboard.publications.publications', [
            'publications' => Publication::all(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.publications.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'type' => 'required',
            'another_type' => 'nullable',
            'descriptions' => 'required|max:255',
            'file' => 'required|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $validatedData['descriptions'] = strip_tags($request->descriptions);
    
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $originalName = $file->getClientOriginalName();

            $publication = new Publication();
            $publication->title = $validatedData['title'];
            $title = $publication->title;
            $title = str_replace(' ', '_', $title);

            $fileName = $title . '_' . time() . '.' . $extension;
            $filePath = $file->storeAs('public/files', $fileName);
            
            $validatedData['file'] = 'files/' . $fileName;
        }
    
        // Save the incoming mail record to the database
        Publication::create($validatedData);
    
        return redirect('/dashboard/archive/publications')->with('success', 'New Archive Publication has been added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Publication $publication)
    {
        $fileName = $publication->file;
    
        // Menghapus "files/" dari nama file
        $fileName = str_replace('files/', '', $fileName);
        $nameView = str_replace('_', ' ', $fileName);

        $encodeFileName = urlencode($fileName);

        // Membentuk URL file dengan menggunakan fungsi url() pada instance Storage
        $fileUrl = asset('storage/files/' . $encodeFileName);
    
        return view('dashboard.publications.show', [
            'pub' => $publication,
            'fileName' => $nameView,
            'fileUrl' => $fileUrl
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Publication $publication)
    {
        return view('dashboard.publications.edit', [
            'pub' => $publication
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Publication $publication)
    {
        $validatedData = $request->validate([
            'title' => 'nullable',
            'type' => 'nullable',
            'another_type' => 'nullable',
            'descriptions' => 'nullable',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        if ($request->input('descriptions') != $publication->descriptions) {
            $rules['descriptions'] = 'required|max:255';
            $validatedData = $request->validate($rules);
            
            $validatedData['descriptions'] = strip_tags($request->descriptions);
        }
        
        $validatedData['descriptions'] = strip_tags($request->descriptions);
    
        if ($request->hasFile('file')) {
            if ($request->oldFile) {
                Storage::delete($request->oldFile);
            }

            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $originalName = $file->getClientOriginalName();

            $publication = new Publication();
            $publication->title = $validatedData['title'];
            $title = $publication->title;
            $title = str_replace(' ', '_', $title);

            $fileName = $title . '_' . time() . '.' . $extension;
            $filePath = $file->storeAs('files', $fileName);
            
            $validatedData['file'] = 'files/' . $fileName;
        }else {
            unset($validatedData['file']);
        }
        
        try {
            $publication->update($validatedData);
    
            return redirect('/dashboard/archive/publications')->with('success', 'New Archive Publication has been Updated successfully.');
        } catch (\Exception $e) {
            return back()->withInput()->withErrors('Failed to update Publications data. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Publication $publication)
    {
        if($publication->file) {
            Storage::delete($publication->file);
        }

        Publication::destroy($publication->id);

        return redirect('/dashboard/archive/publications')->with('success', 'Archive Files data has been deleted successfully.');
    }
}
