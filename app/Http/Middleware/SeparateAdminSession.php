<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SeparateAdminSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->is('admin') || $request->is('admin/*')) {
            config(['session.cookie' => 'laptopshop_admin_session']);
        } else {
            config(['session.cookie' => 'laptopshop_client_session']);
        }

        return $next($request);
    }
}