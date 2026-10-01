<?php

namespace App\Mail;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InvoiceEmailMailable extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Invoice $invoice)
    {
    }

    public function envelope(): Envelope
    {
        $tenantName = $this->invoice->tenant->name ?? 'MWIFI';
        return new Envelope(
            subject: "Tagihan Internet {$this->invoice->invoice_number} - {$tenantName}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.invoice',
            with: [
                'invoice' => $this->invoice,
                'customer' => $this->invoice->customer,
                'tenant' => $this->invoice->tenant,
            ],
        );
    }

    public function attachments(): array
    {
        $invoice = $this->invoice->load(['customer', 'items', 'tenant']);
        $tenant = $invoice->tenant;

        $pdf = Pdf::loadView('tenant.invoices.pdf', compact('invoice', 'tenant'));

        return [
            Attachment::fromData(fn () => $pdf->output(), "Invoice-{$invoice->invoice_number}.pdf")
                ->withMime('application/pdf'),
        ];
    }
}
