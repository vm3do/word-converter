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
            
            // Add custom CSS with font support for Chinese characters
            $customCss = '<style>
                @font-face {
                    font-family: "Noto Sans SC";
                    src: url("https://fonts.googleapis.com/css2?family=Noto+Sans+SC&display=swap");
                }
                body {
                    font-family: "Noto Sans SC", Arial, sans-serif;
                }
                table { 
                    width: 100%; 
                    border-collapse: collapse; 
                    table-layout: fixed;
                }
                td, th { 
                    overflow-wrap: break-word;
                    max-width: 100%;
                }
                * {
                    max-width: 100%;
                    box-sizing: border-box;
                }
            </style>';
            
            // Add meta tag for UTF-8 encoding
            $htmlContent = str_replace('<head>', '<head><meta charset="UTF-8">', $htmlContent);
            $htmlContent = str_replace('</head>', $customCss . '</head>', $htmlContent);
            
            // Configure Dompdf
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isPhpEnabled', true);
            $options->set('isRemoteEnabled', true); // Enable remote resources for fonts
            $options->set('dpi', 150);
            $options->set('defaultFont', 'Noto Sans SC');
            
            // Dompdf
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($htmlContent, 'UTF-8');
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();
            
            // Clean up temporary file
            unlink($tempHtmlPath);
            
            // Return the PDF
            return $dompdf->stream('converted.pdf', [
                'Attachment' => true
            ]);
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error converting file: ' . $e->getMessage());
        }
    }
}
