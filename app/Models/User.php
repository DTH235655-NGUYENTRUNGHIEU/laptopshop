<?php

namespace App\Models;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notifiable;


class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'users';

    protected $fillable = [
        'name',
        'email',
        'username',
        'sodienthoai',
        'diachi',
        'role',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function DonHang(): HasMany
    {
        return $this->hasMany(DonHang::class, 'user_id', 'id');
    }

    public function PhieuNhap(): HasMany
    {
        return $this->hasMany(PhieuNhap::class, 'nhanvien_id', 'id');
    }

    public function HoaDon(): HasMany
    {
        return $this->hasMany(HoaDon::class, 'user_id', 'id');
    }

    public function HoaDonLap(): HasMany
    {
        return $this->hasMany(HoaDon::class, 'nhanvien_id', 'id');
    }

    public function DanhGia(): HasMany
    {
        return $this->hasMany(DanhGia::class, 'user_id', 'id');
    }

    public function BaiViet(): HasMany
    {
        return $this->hasMany(BaiViet::class, 'user_id', 'id');
    }

    public function BinhLuanBaiViet(): HasMany
    {
        return $this->hasMany(BinhLuanBaiViet::class, 'user_id', 'id');
    }

    public function sendPasswordResetNotification($token): void
    {
        $this->notify(new CustomResetPasswordNotification($token));
    }
}

class CustomResetPasswordNotification extends ResetPassword
{
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Khôi phục mật khẩu - ' . config('app.name'))
            ->line('Bạn vừa yêu cầu ' . config('app.name') . ' khôi phục mật khẩu cho tài khoản của mình.')
            ->line('Liên kết đặt lại mật khẩu này sẽ hết hạn sau 60 phút.')
            ->action('Khôi phục mật khẩu', url(config('app.url') . route('password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()], false)))
            ->line('Nếu bạn không yêu cầu đặt lại mật khẩu, xin vui lòng bỏ qua email này.');
    }
}