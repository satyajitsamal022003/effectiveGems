<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderdeliveryDetails extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $messageDetails;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Order $order, $messageDetails)
    {
        $this->order = $order;
        $this->messageDetails = $messageDetails;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Courier Details for Your Order')
                    ->markdown('emails.deliverydetails')
                    ->with([
                        'order' => $this->order,
                        'messageDetails' => $this->messageDetails
                    ]);
    }
}
