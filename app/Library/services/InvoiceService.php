<?php

namespace ProjectManagement\Services;

use Dompdf\Dompdf;
use ProjectManagement\Repositories\Invoice\InvoiceRepositoryInterface;

class InvoiceService
{
    protected $invoiceRepository;

    public function __construct(InvoiceRepositoryInterface $invoiceRepository){
        $this->invoiceRepository = $invoiceRepository;
    }

    public function generatePaymentPdf($invoiceData)
    {
        $invoiceData = $invoiceData->toArray(request());
        $dompdf = new Dompdf();
        $html = view('payments.payment_pdf', $invoiceData)->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $output = $dompdf->output();
        $pdfDirectory = storage_path('app/public/invoices');
        if (!file_exists($pdfDirectory)) {
            mkdir($pdfDirectory, 0755, true);
        }
        $pdfPath = $pdfDirectory . '/payment_receipt_' . $invoiceData['id'] . '.pdf';
        file_put_contents($pdfPath, $output);
        return response()->json([
            'message' => 'PDF generated successfully.',
            'path' => '/storage/invoices/payment_receipt_' . $invoiceData['id'] . '.pdf'
        ]);
    }
}
