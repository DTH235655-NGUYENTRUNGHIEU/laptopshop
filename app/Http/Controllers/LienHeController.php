<?php

namespace App\Http\Controllers;

use App\Models\LienHe;
use Illuminate\Http\Request;

class LienHeController extends Controller
{
    public function getDanhSach(Request $request)
    {
        $query = LienHe::query();

        if ($request->filled('trangthai')) {
            $query->where('trangthai', $request->trangthai);
        }

        if ($request->filled('tukhoa')) {
            $tukhoa = trim($request->tukhoa);
            $query->where(function ($q) use ($tukhoa) {
                $q->where('hoten', 'like', '%' . $tukhoa . '%')
                    ->orWhere('email', 'like', '%' . $tukhoa . '%')
                    ->orWhere('tieude', 'like', '%' . $tukhoa . '%');
            });
        }

        $lienhe = $query->orderByRaw("FIELD(trangthai, 'chua_xuly', 'dang_xuly', 'da_xuly')")
            ->orderByDesc('created_at')
            ->paginate(10)
            ->withQueryString();

        return view('admin.lienhe.danhsach', compact('lienhe'));
    }

    public function getSua($id)
    {
        $lienhe = LienHe::findOrFail($id);
        return view('admin.lienhe.sua', compact('lienhe'));
    }

    public function postSua(Request $request, $id)
    {
        $request->validate([
            'trangthai' => ['required', 'in:chua_xuly,dang_xuly,da_xuly'],
            'phanhoi_admin' => ['nullable', 'string'],
        ]);

        $orm = LienHe::findOrFail($id);
        $trangThaiMoi = $request->input('trangthai');

        // Tự động chuyển sang "Đã xử lý" nếu đã nhập nội dung phản hồi mà quên đổi dropdown
        if ($trangThaiMoi === 'chua_xuly' && $request->filled('phanhoi_admin')) {
            $trangThaiMoi = 'da_xuly';
        }

        $orm->trangthai = $trangThaiMoi;
        $orm->phanhoi_admin = $request->input('phanhoi_admin');
        $orm->save();

        return redirect()->route('admin.lienhe')
            ->with('success', 'Đã cập nhật trạng thái xử lý yêu cầu liên hệ của "' . $orm->hoten . '".');
    }

    public function getXoa($id)
    {
        $orm = LienHe::findOrFail($id);
        $orm->delete();

        return redirect()->route('admin.lienhe')
            ->with('success', 'Đã xóa yêu cầu liên hệ.');
    }
}