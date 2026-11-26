<?php

namespace App\Providers;

use App\Models\ChuDe;
use App\Models\HangSanXuat;
use App\Models\LoaiSanPham;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useBootstrapFive();

        View::composer('layouts.frontend', function ($view) {
            $view->with([
                'navLoaiSanPham' => LoaiSanPham::orderBy('id')->get(),
                'navHangSanXuat' => HangSanXuat::orderBy('tenhang')->get(),
                'navChuDe' => ChuDe::orderBy('id')->get(),
            ]);
        });
    }
}