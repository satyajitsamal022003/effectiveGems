<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderinvoiceDetails extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $invoiceFilename;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order, $invoiceFilename)
    {
        $this->order = $order;
        $this->invoiceFilename = $invoiceFilename;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Attach the invoice PDF and build the email
        return $this->subject('Your Invoice is Attached')
                    ->markdown('emails.invoice')  // Email content defined in the `emails.invoice` template
                    ->with([
                        'order' => $this->order,
                    ])
                    ->attach(public_path('uploads/' . $this->invoiceFilename), [
                        'as' => 'Invoice.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }
}
