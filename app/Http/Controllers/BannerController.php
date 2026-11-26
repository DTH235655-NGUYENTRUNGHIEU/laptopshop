<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BannerController extends Controller
{
    public function getDanhSach()
    {
        $banner = Banner::orderBy('vitri')->orderBy('thutu')->orderByDesc('id')->get();
        return view('admin.banner.danhsach', compact('banner'));
    }

    public function getThem()
    {
        return view('admin.banner.them');
    }

    public function postThem(Request $request)
    {
        $request->validate([
            'tieude' => ['required', 'string', 'max:255'],
            'hinhanh' => ['required', 'image', 'max:4096'],
            'duongdan' => ['nullable', 'string', 'max:255'],
            'vitri' => ['required', 'in:slider_chinh,banner_phu,popup'],
            'thutu' => ['required', 'integer', 'min:0', 'max:255'],
        ]);

        $file = $request->file('hinhanh');
        $filename = Str::slug($request->tieude, '-') . '-' . time() . '.' . $file->extension();
        $path = $file->storeAs('banners', $filename, 'public');

        $orm = new Banner();
        $orm->tieude = trim($request->tieude);
        $orm->hinhanh = $path;
        $orm->duongdan = $request->filled('duongdan') ? trim($request->duongdan) : null;
        $orm->vitri = $request->vitri;
        $orm->thutu = (int) $request->thutu;
        $orm->kichhoat = $request->boolean('kichhoat');
        $orm->save();

        return redirect()->route('admin.banner')
            ->with('success', 'Đã thêm banner "' . $orm->tieude . '" thành công.');
    }

    public function getSua($id)
    {
        $banner = Banner::findOrFail($id);
        return view('admin.banner.sua', compact('banner'));
    }

    public function postSua(Request $request, $id)
    {
        $request->validate([
            'tieude' => ['required', 'string', 'max:255'],
            'hinhanh' => ['nullable', 'image', 'max:4096'],
            'duongdan' => ['nullable', 'string', 'max:255'],
            'vitri' => ['required', 'in:slider_chinh,banner_phu,popup'],
            'thutu' => ['required', 'integer', 'min:0', 'max:255'],
        ]);

        $orm = Banner::findOrFail($id);

        if ($request->hasFile('hinhanh')) {
            if ($orm->hinhanh && Storage::disk('public')->exists($orm->hinhanh)) {
                Storage::disk('public')->delete($orm->hinhanh);
            }
            $file = $request->file('hinhanh');
            $filename = Str::slug($request->tieude, '-') . '-' . time() . '.' . $file->extension();
            $orm->hinhanh = $file->storeAs('banners', $filename, 'public');
        }

        $orm->tieude = trim($request->tieude);
        $orm->duongdan = $request->filled('duongdan') ? trim($request->duongdan) : null;
        $orm->vitri = $request->vitri;
        $orm->thutu = (int) $request->thutu;
        $orm->kichhoat = $request->boolean('kichhoat');
        $orm->save();

        return redirect()->route('admin.banner')
            ->with('success', 'Đã cập nhật banner "' . $orm->tieude . '" thành công.');
    }

    public function getKichHoat($id)
    {
        $orm = Banner::findOrFail($id);
        $orm->kichhoat = !$orm->kichhoat;
        $orm->save();

        return redirect()->route('admin.banner')
            ->with('success', 'Đã thay đổi trạng thái hiển thị banner.');
    }

    public function getXoa($id)
    {
        $orm = Banner::findOrFail($id);

        if ($orm->hinhanh && Storage::disk('public')->exists($orm->hinhanh)) {
            Storage::disk('public')->delete($orm->hinhanh);
        }

        $orm->delete();

        return redirect()->route('admin.banner')
            ->with('success', 'Đã xóa banner "' . $orm->tieude . '".');
    }
}