<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Settings;
use Dompdf\Dompdf;
use Dompdf\Options;

class WordToPdfController extends Controller
{
    public function index()
    {
        return view('wordtopdf');
    }

    public function convert(Request $request)
    {
        $request->validate([
            'word_file' => 'required|mimes:doc,docx|max:10240',
        ]);

        try {
            $file = $request->file('word_file');
            $phpWord = IOFactory::load($file->getPathname());
            
            // Create temporary HTML
            $htmlWriter = new \PhpOffice\PhpWord\Writer\HTML($phpWord);
            $tempHtmlPath = storage_path('app/temp.html');
            $htmlWriter->save($tempHtmlPath);
            
            // Read the HTML content
            $htmlContent = file_get_contents($tempHtmlPath);

            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
            
            // Create Dompdf instance
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($htmlContent);
            
            $dompdf->setPaper('A4', 'portrait');
            
            // Render PDF
            $dompdf->render();
            
            // Clean up temporary file
            unlink($tempHtmlPath);
            
            // Return the PDF for download
            return $dompdf->stream('converted.pdf', [
                'Attachment' => true
            ]);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error converting file: ' . $e->getMessage());
        }
    }
}
