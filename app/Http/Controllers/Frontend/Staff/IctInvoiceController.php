<?php

namespace App\Http\Controllers\Frontend\Staff;

use App\Http\Controllers\Controller;
use App\Models\ICTInvoice;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class IctInvoiceController extends Controller
{

    public function getInvoiceDetail(string $invoice_id): string
    {
        $data = [
            'invoice' => ICTInvoice::with(['student', 'course.schedule'])->findOrFail($invoice_id),
        ];
        return view('frontend.staff.pages.partials.inv-body', $data)->render();
    }


    public function invoices(): View
    {
        $data = [
            'page_title' => 'ICT Center | Invoices',
            'invoices' => ICTInvoice::with(['student', 'course'])
                ->latest()
                ->get(),
        ];
        return view('frontend.staff.pages.invoice', $data);
    }

}
