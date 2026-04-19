<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PDF;
use App\Models\Order; 
use Auth;
ini_set('max_execution_time', 120);
class PdfController extends Controller
{
    public function orderPdf($orderId)
    {
        $print = 'pdf.order';
        $font = 'arial';
        $position = 'portrait';
        $control_number = 'OR-'.date('Y').'-'.str_pad($orderId, 4, '0', STR_PAD_LEFT);
        $user = Auth::user();
        if($user->is_admin == 1):
            $order = Order::with('orderItems.product')->findOrFail($orderId);
        else:
            $order = Order::where('id', $orderId)->where('user_id', $user->id)->firstOrFail();
        endif;
        
        $image = base64_encode(file_get_contents(public_path('assets/images/icon/logo.png')));
        $pdf = Pdf::setOptions([
            'isHtml5ParserEnabled' => true, 
            'isRemoteEnabled' => true,
            'defaultMediaType'=> 'all',
            'isFontSubsettingEnabled'=>true,
            'defaultFont'=>$font
        ])->loadView($print,compact('orderId','control_number', 'order', 'user', 'image'))->setPaper('LETTER', $position);
        $pdf->getDomPDF()->set_option("enable_php", true);

        return $pdf->stream('order.pdf',array('Attachment' => false));
    }
}
