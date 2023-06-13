<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\Karyawan;
// use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AbsensiController extends Controller
{
    public function index()
    {
        $absensi = Absensi::all();

        return view('admin.absensi', compact('absensi'));
    }

    public function create()
    {
        $karyawan = Karyawan::all();
        return view('admin.absen', compact('karyawan'));
    }

 public function store(Request $request)
{
    // $request->validate([
    //     'karyawan_id' => 'required',
    //     'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
    //     // 'tanggal_masuk' => 'required',
    //     'keterangan' => 'required',
    // ]);

    $data = $request->all();
    // dd($data);
    if ($request->hasFile('foto')) {
        $foto = $request->file('foto');

        // Upload gambar ke ImgBB
        $response = Http::attach(
            'image',
            file_get_contents($foto),
            $foto->getClientOriginalName()
        )->post('https://api.imgbb.com/1/upload?key=' . env('IMGBB_API_KEY'));

        // Periksa respon dan dapatkan URL gambar
        if ($response->ok()) {
            $responseData = $response->json();
            $data['foto'] = $responseData['data']['url'];
        } else {
            // Handle error jika upload gagal
            return back()->with('error', 'Upload gambar gagal: ' . $response->body());
        }
    }

    Absensi::create($data);

    return redirect('/absensi')->with('success', 'Data berhasil disimpan.');
}
}