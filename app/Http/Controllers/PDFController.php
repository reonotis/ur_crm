<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\VisitHistory;
use PDF;

class PDFController extends Controller
{
    /**
     */
    public static function index(){

        list($visitHistorys, $fromMonth, $toMonth) = VisitHistory::get_monthAndStylist('2021-08-21', 1);
        // dd($visitHistorys);
        $pdf = PDF::loadView('pdf.index', compact('visitHistorys', 'fromMonth', 'toMonth' ));

        // PDFを表示
        return $pdf->stream('pdf_file_name.pdf');
    }

    /**
     * 指定されたフPDFファイルを表示させる
     */
    public function show_pdfFile($file_name){
        $file_path = storage_path('app/pdf/'.$file_name);
        $headers = ['Content-disposition' => 'inline; filename="'.$file_name.'"'];
        return response()->file($file_path, $headers);
    }

}
