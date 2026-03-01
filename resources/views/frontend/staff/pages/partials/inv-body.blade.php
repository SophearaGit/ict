 <div class="invoice-header d-flex align-items-center border-bottom p-3">
     <h4 class="font-medium text-uppercase mb-0">
         Invoice
     </h4>
     <div class="ms-auto">
         <h4 class="invoice-number">
             {{ $invoice->invoice_code }}
         </h4>
     </div>
 </div>
 <div class="p-3" id="custom-invoice">
     <div class="invoice-123" id="printableArea" style="display: block;">
         <div class="row pt-3">
             <div class="col-md-12">
                 <div class="">
                     <address>
                         <h6>&nbsp;From,</h6>
                         <h6 class="fw-bold">&nbsp;
                             ICT Training Center, ( Prepared By: {{ auth()->user()->name }} )
                         </h6>
                         <p class="ms-1">
                             107, Phoum 4 ANZ Road Sangkat Boeung, Phnom Penh 120408
                         </p>
                     </address>
                 </div>
                 <div class="text-end">
                     <address>
                         <h6>To,</h6>
                         <h6 class="fw-bold invoice-customer text-capitalize">
                             {{ $invoice->student->name }}
                         </h6>
                         <p class="ms-4">
                             107, Phoum 4 ANZ Road Sangkat Boeung, Phnom Penh 120408
                         </p>
                         <p class="mt-4 mb-1">
                             <span>Invoice Date :</span>
                             <i class="ti ti-calendar"></i>
                             {{ $invoice->created_at->format('d M Y') }}
                         </p>
                         <p>

                         </p>
                     </address>
                 </div>
             </div>
             <div class="col-md-12">
                 <div class="table-responsive mt-5" style="clear: both">
                     <table class="table table-hover align-middle mb-0 text-nowrap">
                         <thead>
                             <tr class="text-muted fw-semibold">
                                 <th scope="col" class="ps-0">Price</th>
                                 <th scope="col" class="ps-0">Course</th>
                                 <th scope="col" class="ps-0">
                                     Schedule
                                 </th>
                                 <th scope="col" class="ps-0">Class Start</th>
                             </tr>
                         </thead>
                         <tbody>
                             <tr>
                                 <td class="ps-0">
                                     <i class="ti ti-currency-dollar fs-3 fw-semibold"></i>{{ $invoice->course->price }}
                                 </td>
                                 <td class="ps-0">
                                     <div class="d-flex align-items-center gap-3">
                                         <div class="flex-shrink-0">
                                             <img src="{{ asset($invoice->course->thumbnail) }}" class="rounded"
                                                 alt="p1" width="80">
                                         </div>
                                         <div>
                                             <h6 class="mb-1 fw-semibold">
                                                 {{ $invoice->course->title }}
                                             </h6>

                                             <div class="d-flex align-items-center gap-2">
                                                 <img src="{{ $invoice->course->instructor->image }}"
                                                     alt="{{ $invoice->course->instructor->name }}"
                                                     class="rounded-circle"
                                                     style="width: 28px; height: 28px; object-fit: cover;">

                                                 <span class="fs-2 mb-0">
                                                     {{ $invoice->course->instructor->name }}
                                                 </span>
                                             </div>
                                         </div>
                                     </div>
                                 </td>
                                 <td class="ps-0 text-capitalize">
                                     {{ $invoice->course->schedule->study_day }} |
                                     {{ $invoice->course->schedule->shift }} |
                                     {{ \Carbon\Carbon::parse($invoice->course->schedule->start_time)->format('h:i ') }}
                                     -
                                     {{ \Carbon\Carbon::parse($invoice->course->schedule->end_time)->format('h:i A') }}
                                 </td>
                                 <td class="ps-0 text-capitalize">
                                     {{ \Carbon\Carbon::parse($invoice->course->start_date)->format('d M, Y') }}
                                 </td>
                             </tr>
                         </tbody>
                     </table>
                 </div>
             </div>


             <div class="col-md-12">
                 <div class="mt-4 text-end">

                     {{-- ===== PRICE SECTION ===== --}}
                     <div class="mb-2">

                         {{-- Original Price --}}
                         @if ($invoice->discount > 0)
                             <p class="mb-1">
                                 <span class="text-muted">Original Price :</span>
                                 <span class="text-decoration-line-through text-danger">
                                     ${{ number_format($invoice->course->price, 2) }}
                                 </span>
                             </p>

                             <p class="mb-1">
                                 <span class="text-muted">Discount :</span>
                                 <span class="text-danger">
                                     - ${{ number_format($invoice->discount, 2) }}
                                 </span>
                             </p>
                         @endif

                         {{-- Final Total --}}
                         <h4 class="fw-bold">
                             Total :
                             <span class="text-primary">
                                 ${{ number_format($invoice->total_amount, 2) }}
                             </span>
                         </h4>

                     </div>

                     {{-- <hr style="width: 25%; margin-left: 75%;"> --}}
                     <hr>

                     {{-- ===== PAYMENT SECTION ===== --}}
                     <div class="mb-2">

                         <p class="mb-1">
                             <span class="text-muted">Paid :</span>
                             ${{ number_format($invoice->paid_amount, 2) }}
                         </p>

                         @if ($invoice->remaining_amount > 0)
                             <p class="mb-1">
                                 <span class="text-muted">Remaining :</span>
                                 ${{ number_format($invoice->remaining_amount, 2) }}
                             </p>
                         @endif

                     </div>

                     {{-- ===== STATUS BADGE ===== --}}
                     <div class="mb-3">
                         @if ($invoice->payment_status == 'paid')
                             <span class="badge bg-success text-uppercase px-3 py-2">
                                 PAID
                             </span>
                         @elseif ($invoice->payment_status == 'half_paid')
                             <span class="badge bg-warning text-dark text-uppercase px-3 py-2">
                                 HALF PAID
                             </span>
                         @else
                             <span class="badge bg-danger text-uppercase px-3 py-2">
                                 UNPAID
                             </span>
                         @endif
                     </div>

                 </div>

                 {{-- <hr style="width: 25%; margin-left: 75%;"> --}}
                 <hr>

                 {{-- ===== ACTION BUTTONS ===== --}}
                 <div class="text-end no-print">
                     <button class="btn btn-secondary print-page" type="button">
                         <i class="ti ti-printer fs-5"></i> Print Invoice
                     </button>
                 </div>
             </div>
         </div>
     </div>
 </div>
