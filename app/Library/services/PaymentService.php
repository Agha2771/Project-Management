<?php

namespace ProjectManagement\Services;
use Dompdf\Dompdf;

use ProjectManagement\Repositories\Payment\PaymentRepositoryInterface;

class PaymentService
{
    protected $paymentRepository;

    public function __construct(PaymentRepositoryInterface $paymentRepository){
        $this->paymentRepository = $paymentRepository;
    }

    public function generatePaymentPdf($payment)
    {
        $dompdf = new Dompdf();
        $html = view('payments.payment_pdf', $payment)->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        return $dompdf->stream('payment_receipt_' . $payment['id'] . '.pdf');
    }
}
