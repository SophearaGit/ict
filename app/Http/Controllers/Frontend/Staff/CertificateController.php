<?php

namespace App\Http\Controllers\Frontend\Staff;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use KhmerPdf\LaravelKhPdf\Facades\PdfKh;
use App\Models\ICTCourse;


class CertificateController extends Controller
{

    // mPDF

    public function print(Request $request)
    {
        $students = User::whereIn('id', $request->students)->get();
        $course = ICTCourse::find($request->course_id);

        $html = view('pdf.certificate', compact(['students', 'course']))->render();

        return PdfKh::loadHTML($html)
            ->stream('certificate.pdf');
    }













    // public function print(Request $request)
    // {
    //     $students = User::whereIn('id', $request->students)->get();

    //     $pdf = new Fpdi('L', 'mm', [297, 210]);

    //     $pdf->SetMargins(0, 0, 0);
    //     $pdf->SetAutoPageBreak(false);

    //     // 🔴 important
    //     $pdf->setPrintHeader(false);
    //     $pdf->setPrintFooter(false);

    //     foreach ($students as $student) {

    //         $pdf->AddPage();

    //         // Load your certificate template
    //         $pdf->setSourceFile(storage_path('app/certificates/certificate_new.pdf'));
    //         $tplId = $pdf->importPage(1);
    //         $pdf->useTemplate($tplId, 0, 0, 297, 210);

    //         $fontname = \TCPDF_FONTS::addTTFfont(
    //             public_path('fonts/Battambang/Battambang-Regular.ttf'),
    //             'TrueTypeUnicode',
    //             '',
    //             32
    //         );

    //         // ✅ Use Unicode font (Khmer works)
    //         $pdf->SetFont($fontname, '', 12);

    //         // 🟦 English
    //         $pdf->SetXY(220, 82);
    //         $pdf->Write(0, $student->name);

    //         $pdf->SetXY(150, 105);
    //         $pdf->Write(0, $student->dob ?? '-');

    //         $pdf->SetXY(150, 115);
    //         $pdf->Write(0, $student->gender ?? '-');

    //         $pdf->SetXY(190, 115);
    //         $pdf->Write(0, $student->nationality ?? '-');

    //         // 🟩 Khmer
    //         $pdf->SetXY(55, 82);
    //         $pdf->Write(0, $student->khmer_name ?? $student->name);

    //         $pdf->SetXY(55, 105);
    //         $pdf->Write(0, $student->dob ?? '-');

    //         $pdf->SetXY(55, 115);
    //         $pdf->Write(0, $student->gender ?? '-');

    //         $pdf->SetXY(95, 115);
    //         $pdf->Write(0, $student->nationality ?? '-');
    //     }

    //     return response($pdf->Output('S'), 200)
    //         ->header('Content-Type', 'application/pdf')
    //         ->header('Content-Disposition', 'inline; filename="certificate.pdf"');
    // }

}
