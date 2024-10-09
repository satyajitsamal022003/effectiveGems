<?php
namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $status;
    public $cancellationReason;

    public function __construct(Order $order, string $status, string $cancellationReason = null)
    {
        $this->order = $order;
        $this->status = $status;
        $this->cancellationReason = $cancellationReason;
    }

    public function build()
    {
        if ($this->status === 'approved') {
            return $this->view('emails.order_approved')
                        ->subject('Congratulations your order has been approved and ready to dispatch');
        } elseif ($this->status === 'canceled') {
            return $this->view('emails.order_canceled')
                         ->subject('Sorry! Your order has been canceled')
                        ->with([
                                'cancellationReason' => $this->cancellationReason,
                            ]);
        }

        return $this; // Fallback to default behavior
    }
}
