<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\OutgoingMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpWord\TemplateProcessor;
use Carbon\Carbon;
use PhpOffice\PhpWord\Settings;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Converter;
use PhpOffice\PhpWord\PhpWord;

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
            'members' => Member::all(),
            'now' => Carbon::now()->format('Y-m-d'),
        ]);
    }

    public function getLastNumber()
    {
        $lastDocument = OutgoingMail::latest()->first();

        $lastNumber = ($lastDocument) ? (int)$lastDocument->number : null;

        return response()->json(['number' => $lastNumber]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        function getRomanMonth($month)
        {
            $romanMonths = [
                1 => 'I',
                2 => 'II',
                3 => 'III',
                4 => 'IV',
                5 => 'V',
                6 => 'VI',
                7 => 'VII',
                8 => 'VIII',
                9 => 'IX',
                10 => 'X',
                11 => 'XI',
                12 => 'XII'
            ];

            return $romanMonths[$month];
        }

        // Validasi input jika diperlukan
        $validatedData = $request->validate([
            'type' => 'required',
            'number' => 'required',
            'date' => 'required',
            'city' => 'required',
            'subject' => 'required',
            'attachment' => 'required',
            'receiver' => 'required',
            're_location' => 'required',
            'content' => 'required',
            'member_id' => 'required|exists:members,id',
        ]);

        
        // Ambil data dari formulir
        $number = $request->input('number');
        $type = $request->input('type');
        $city = $request->input('city');
        $subject = $request->input('subject');
        $attachment = $request->input('attachment');
        $receiver = $request->input('receiver');
        $re_location = $request->input('re_location');


        $content = html_entity_decode(strip_tags($request->input('content')), ENT_QUOTES, 'UTF-8');
        $content = str_replace("\n", "<w:br/>", $content);

        $pic = $request->input('pic');
        $position = $request->input('position');
        
        $validatedData['content'] = $content;
        
        // Format tanggal
        $formattedDate = Carbon::now()->format('j F Y');
        $month = Carbon::now()->format('n');
        $romanMonth = getRomanMonth($month);
        $year = Carbon::now()->format('Y');

        // Ekstrak nilai dari formulir format nomor surat
        $number = $validatedData['number'];
        $letter_number = $number . '/B/' . $romanMonth . '/' . $year;
        $validatedData['letter_number'] = $letter_number;

        // penggantian placeholder dalam template
        $templateProcessor = new TemplateProcessor(storage_path('app/public/template/undangan.docx'));
        $templateProcessor->setValues([
            'number' => $number,
            'month' => $romanMonth,
            'year' => $year,
            'city' => $city,
            'date' => $formattedDate,
            'subject' => $subject,
            'attachment' => $attachment,
            'receiver' => $receiver,
            're_location' => $re_location,
            'content' => $content,
            'pic' => $pic,
            'position' => $position,
        ]);
        
        $previewFilePath = storage_path('app/public/undangan/' . $number . '_undangan.docx');
        $templateProcessor->saveAs($previewFilePath);

        // Konversi DOCX ke PDF
        $pdfFilePath = $this->convertDocxToPdf($previewFilePath, $number, $type);

        // Simpan file PDF ke storage
        $pdfFileName = $number . '_' . $type . '_outgoing.pdf';
        Storage::disk('public')->put('pdf/' . $pdfFileName, file_get_contents($pdfFilePath));
        
        $pdfFile = 'pdf/' . $number . '_' . $type . '_outgoing.pdf';
        // Save the incoming mail record to the database
        $validatedData['file'] = $pdfFile;
        OutgoingMail::create($validatedData);

        $previewFileUrl = asset('storage/undangan/' . $number . '_undangan.docx');

        return redirect()->route('outgoing-mails.index')->with('success', ['message' => 'Outgoing mail created successfully.', 'filePreviewUrl' => $previewFileUrl]);
    }

    private function convertDocxToPdf($docxFilePath, $number, $type)
    {
        Settings::setPdfRenderer(Settings::PDF_RENDERER_DOMPDF, base_path('vendor/dompdf/dompdf/'));

        // Load PHPWord
        $phpWord = IOFactory::load($docxFilePath);

        // Nama file PDF sesuai format
        $pdfFileName = $number . '_' . $type . '_outgoing.pdf';
        
        // Save as PDF
        $pdfFilePath = storage_path('app/public/pdf/') . $pdfFileName;
        $phpWord->save($pdfFilePath, 'PDF');

        return $pdfFilePath;
    }


    /**
     * Display the specified resource.
     */
    public function show(OutgoingMail $outgoingMail)
    {
        $fileName = $outgoingMail->file;
        // $filePath = $incomingMail->file;
    
        // Menghapus "files/" dari nama file
        $fileName = str_replace('pdf/', '', $fileName);

        $encodeFileName = urlencode($fileName);

        $fileUrl = asset('storage/pdf/' . $encodeFileName);
    
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
