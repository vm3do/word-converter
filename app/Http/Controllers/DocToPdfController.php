<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use phpOffice\phpWord\IOFactory;
use Mpdf\Mpdf;

class DocToPdfController extends Controller
{
    public function show(){
        return view('homepage');
    }

    public function convert(Request $request){

        $request->validate([
            'word_file' => 'required|mimes:docx|max:10000', 
        ]);

        $file = $request->file('word_file');
        $phpWord = IOFactory::load($file->getPathname());

        // Save as HTML
        $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
        $tempHtmlPath = storage_path('app/temp.html');
        $htmlWriter->save($tempHtmlPath);

        $htmlContent = file_get_contents($tempHtmlPath);

        $mpdf = new Mpdf();
        $mpdf->WriteHTML($htmlContent);

        // Generate PDF
        return response($mpdf->Output('', 'S'), 200)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', 'attachment; filename="converted.pdf"');

    }
}
