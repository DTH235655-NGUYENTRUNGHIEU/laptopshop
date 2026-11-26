<?php

namespace App\Http\Controllers;

use App\Models\ChuDe;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChuDeController extends Controller
{
    public function getDanhSach()
    {
        $chude = ChuDe::withCount('BaiViet')->orderBy('id')->get();
        return view('admin.chude.danhsach', compact('chude'));
    }

    public function getThem()
    {
        return view('admin.chude.them');
    }

    public function postThem(Request $request)
    {
        $request->validate([
            'tenchude' => ['required', 'string', 'max:255', 'unique:chude,tenchude'],
        ]);

        $orm = new ChuDe();
        $orm->tenchude = trim($request->tenchude);
        $orm->tenchude_slug = Str::slug($request->tenchude, '-');
        $orm->save();

        return redirect()->route('admin.chude')
            ->with('success', 'Đã thêm chủ đề "' . $orm->tenchude . '" thành công.');
    }

    public function getSua($id)
    {
        $chude = ChuDe::findOrFail($id);
        return view('admin.chude.sua', compact('chude'));
    }

    public function postSua(Request $request, $id)
    {
        $request->validate([
            'tenchude' => ['required', 'string', 'max:255', 'unique:chude,tenchude,' . $id],
        ]);

        $orm = ChuDe::findOrFail($id);
        $orm->tenchude = trim($request->tenchude);
        $orm->tenchude_slug = Str::slug($request->tenchude, '-');
        $orm->save();

        return redirect()->route('admin.chude')
            ->with('success', 'Đã cập nhật chủ đề "' . $orm->tenchude . '" thành công.');
    }

    public function getXoa($id)
    {
        $orm = ChuDe::findOrFail($id);

        if ($orm->BaiViet()->count() > 0) {
            return redirect()->route('admin.chude')
                ->with('error', 'Không thể xóa vì chủ đề "' . $orm->tenchude . '" đang chứa bài viết.');
        }

        $orm->delete();

        return redirect()->route('admin.chude')
            ->with('success', 'Đã xóa chủ đề "' . $orm->tenchude . '".');
    }
}