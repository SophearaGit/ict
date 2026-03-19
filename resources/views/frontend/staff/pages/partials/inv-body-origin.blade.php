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

                         <hr>

                         <p class="mb-1">
                             <strong>Invoice Date :</strong>
                             {{ $invoice->created_at->format('d M Y') }}
                         </p>

                         {{-- <p class="mb-1 text-warning">
                             <strong>Half Paid On :</strong>
                             {{ $invoice->updated_at->format('d M Y') }}
                         </p> --}}

                         @if ($invoice->payment_status == 'half_paid')
                             <p class="mb-1 text-warning">
                                 <strong>Half Paid On :</strong>
                                 {{ $invoice->updated_at->format('d M Y') }}
                             </p>
                         @endif

                         @if ($invoice->payment_status == 'paid')
                             @if ($invoice->paid_amount < $invoice->total_amount)
                                 <p class="mb-1 text-warning">
                                     <strong>Half Paid On :</strong>
                                     {{ $invoice->updated_at->format('d M Y') }}
                                 </p>
                             @endif
                             <p class="mb-1 text-success">
                                 <strong>Fully Paid On :</strong>
                                 {{ $invoice->updated_at->format('d M Y') }}
                             </p>
                         @endif

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
                                 <th scope="col" class="ps-0">Schedule</th>
                             </tr>
                         </thead>
                         <tbody>
                             <tr>
                                 <td class="ps-0">
                                     <i class="ti ti-currency-dollar fs-3 fw-semibold"></i>{{ $invoice->course->price }}
                                 </td>
                                 <td class="ps-0">
                                     <div class="d-flex align-items-center gap-3">
                                         <div>
                                             <h6 class="mb-1 fw-semibold">
                                                 {{ $invoice->course->title }}
                                             </h6>
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
                     @if ($invoice->payment_status != 'paid')
                         <a href="javascript:void(0)"
                             data-url="{{ route('staff.invoice.confirm-payment', $invoice->id) }}"
                             class="btn btn-info btn_confirm_payment">
                             <i class="ti ti-cash me-2"></i> Confirm Payment
                         </a>
                     @endif
                     <button class="btn btn-secondary print-page" type="button">
                         <i class="ti ti-printer fs-5"></i> Print Invoice
                     </button>
                 </div>
             </div>
         </div>
     </div>
 </div>

 <div class="modal fade" id="dynamic_invoice_modal" tabindex="-1" aria-labelledby="bs-example-modal-lg"
     aria-hidden="true">
     <div class="modal-dialog modal-xl dynamic_invoice_modal_dialog">

         <!-- /.modal-content -->
     </div>
     <!-- /.modal-dialog -->
 </div>



 <script>
     $('.btn_confirm_payment').on('click', function(e) {
         e.preventDefault();

         let url = $(this).data('url');

         Swal.fire({
             title: "Are you sure?",
             text: "You are about to confirm the payment for this invoice.",
             icon: "warning",
             showCancelButton: true,
             confirmButtonColor: "#3085d6",
             cancelButtonColor: "#d33",
             confirmButtonText: "Yes, confirm it!"
         }).then((result) => {
             if (result.isConfirmed) {
                 $.ajax({
                     method: "PUT",
                     url: url,
                     data: {
                         _token: csrf_token,
                     },
                     success: function(data) {
                         iziToast.success({
                             message: data.message,
                             position: 'bottomRight'
                         });
                         setTimeout(() => {
                             window.location.reload();
                         }, 500);
                     },
                     error: function(xhr, status, data) {},
                 })
             }
         });
     })
 </script>
