<?php
namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderRequestToCustomer extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $content;

    public function __construct(Order $order, string $content)
    {
        $this->order = $order;
        $this->content = $content;
    }

    public function build()
    {
        return $this->view('emails.order_request')
                    ->subject('Action Required: Order #'.$this->order->id.' Request')
                    ->with([
                        'order' => $this->order,
                        'content' => $this->content, 
                    ]);
    }
}
