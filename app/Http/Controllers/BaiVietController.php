<?php

namespace App\Http\Controllers;

use App\Models\BaiViet;
use App\Models\ChuDe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BaiVietController extends Controller
{
    public function getDanhSach(Request $request)
    {
        $query = BaiViet::with(['ChuDe', 'User'])->withCount('BinhLuanBaiViet');

        if ($request->filled('tukhoa')) {
            $tukhoa = trim($request->tukhoa);
            $query->where('tieude', 'like', '%' . $tukhoa . '%');
        }

        if ($request->filled('chude_id')) {
            $query->where('chude_id', $request->chude_id);
        }

        $baiviet = $query->orderByDesc('created_at')->orderByDesc('id')->paginate(10)->withQueryString();
        $chude = ChuDe::orderBy('tenchude')->get();

        return view('admin.baiviet.danhsach', compact('baiviet', 'chude'));
    }

    public function getThem()
    {
        $chude = ChuDe::orderBy('tenchude')->get();
        return view('admin.baiviet.them', compact('chude'));
    }

    public function postThem(Request $request)
    {
        $request->validate([
            'chude_id' => ['required', 'integer', 'exists:chude,id'],
            'tieude' => ['required', 'string', 'max:300', 'unique:baiviet,tieude'],
            'hinhanh' => ['nullable', 'image', 'max:4096'],
            'tomtat' => ['nullable', 'string'],
            'noidung' => ['required', 'string', 'min:20'],
        ]);

        $slug = Str::slug($request->tieude, '-');

        $path = null;
        if ($request->hasFile('hinhanh')) {
            $file = $request->file('hinhanh');
            $filename = $slug . '-' . time() . '.' . $file->extension();
            $path = $file->storeAs('baiviet', $filename, 'public');
        }

        $orm = new BaiViet();
        $orm->chude_id = $request->chude_id;
        $orm->user_id = Auth::id();
        $orm->tieude = trim($request->tieude);
        $orm->tieude_slug = $slug;
        $orm->hinhanh = $path;
        $orm->tomtat = $request->tomtat;
        $orm->noidung = $request->noidung;
        $orm->luotxem = 0;
        $orm->kiemduyet = $request->boolean('kiemduyet');
        $orm->kichhoat = $request->boolean('kichhoat');
        $orm->save();

        return redirect()->route('admin.baiviet')
            ->with('success', 'Đã đăng bài viết "' . $orm->tieude . '" thành công.');
    }

    public function getSua($id)
    {
        $baiviet = BaiViet::findOrFail($id);
        $chude = ChuDe::orderBy('tenchude')->get();
        return view('admin.baiviet.sua', compact('baiviet', 'chude'));
    }

    public function postSua(Request $request, $id)
    {
        $request->validate([
            'chude_id' => ['required', 'integer', 'exists:chude,id'],
            'tieude' => ['required', 'string', 'max:300', 'unique:baiviet,tieude,' . $id],
            'hinhanh' => ['nullable', 'image', 'max:4096'],
            'tomtat' => ['nullable', 'string'],
            'noidung' => ['required', 'string', 'min:20'],
        ]);

        $orm = BaiViet::findOrFail($id);
        $slug = Str::slug($request->tieude, '-');

        if ($request->hasFile('hinhanh')) {
            if ($orm->hinhanh && Storage::disk('public')->exists($orm->hinhanh)) {
                Storage::disk('public')->delete($orm->hinhanh);
            }
            $file = $request->file('hinhanh');
            $filename = $slug . '-' . time() . '.' . $file->extension();
            $orm->hinhanh = $file->storeAs('baiviet', $filename, 'public');
        }

        $orm->chude_id = $request->chude_id;
        $orm->tieude = trim($request->tieude);
        $orm->tieude_slug = $slug;
        $orm->tomtat = $request->tomtat;
        $orm->noidung = $request->noidung;
        $orm->kiemduyet = $request->boolean('kiemduyet');
        $orm->kichhoat = $request->boolean('kichhoat');
        $orm->save();

        return redirect()->route('admin.baiviet')
            ->with('success', 'Đã cập nhật bài viết "' . $orm->tieude . '" thành công.');
    }

    public function getKiemDuyet($id)
    {
        $orm = BaiViet::findOrFail($id);
        $orm->kiemduyet = !$orm->kiemduyet;
        $orm->save();

        return redirect()->route('admin.baiviet')
            ->with('success', 'Đã thay đổi trạng thái kiểm duyệt bài viết.');
    }

    public function getKichHoat($id)
    {
        $orm = BaiViet::findOrFail($id);
        $orm->kichhoat = !$orm->kichhoat;
        $orm->save();

        return redirect()->route('admin.baiviet')
            ->with('success', 'Đã thay đổi trạng thái hiển thị bài viết.');
    }

    public function getXoa($id)
    {
        $orm = BaiViet::findOrFail($id);

        if ($orm->hinhanh && Storage::disk('public')->exists($orm->hinhanh)) {
            Storage::disk('public')->delete($orm->hinhanh);
        }

        $orm->delete();

        return redirect()->route('admin.baiviet')
            ->with('success', 'Đã xóa bài viết "' . $orm->tieude . '".');
    }
}