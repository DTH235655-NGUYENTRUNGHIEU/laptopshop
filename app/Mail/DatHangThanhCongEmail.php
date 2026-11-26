<?php

namespace App\Mail;

use App\Models\DonHang;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DatHangThanhCongEmail extends Mailable
{
    use Queueable, SerializesModels;

    public DonHang $donhang;

    public function __construct(DonHang $dh)
    {
        $this->donhang = $dh;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Xác nhận đặt hàng #' . $this->donhang->madonhang . ' - ' . config('app.name', 'LaptopShop'),
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.dathangthanhcong',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}