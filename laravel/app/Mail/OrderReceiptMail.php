<?php

namespace App\Mail;

use App\Models\Narudzba;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public Narudzba $order;

    public function __construct(Narudzba $order)
    {
        $this->order = $order;
    }

    public function build()
    {
        return $this
            ->subject('Vaš račun za narudžbu #' . $this->order->Narudzba_ID)
            ->view('emails.order-receipt')
            ->with([
                'order' => $this->order,
            ]);
    }
}
