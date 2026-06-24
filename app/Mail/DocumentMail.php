<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $docType;       // "Invoice" or "Quotation"
    public string $docNumber;
    public string $clientName;
    public string $studioName;
    public array  $lines;         // body lines
    public string $pdfData;       // raw PDF bytes
    public string $pdfName;

    public function __construct(
        string $docType,
        string $docNumber,
        string $clientName,
        string $studioName,
        array  $lines,
        string $pdfData,
        string $pdfName
    ) {
        $this->docType    = $docType;
        $this->docNumber  = $docNumber;
        $this->clientName = $clientName;
        $this->studioName = $studioName;
        $this->lines      = $lines;
        $this->pdfData    = $pdfData;
        $this->pdfName    = $pdfName;
    }

    public function build()
    {
        return $this->subject($this->docType . ' ' . $this->docNumber . ' from ' . $this->studioName)
            ->view('emails.document')
            ->attachData($this->pdfData, $this->pdfName, [
                'mime' => 'application/pdf',
            ]);
    }
}
