<?php

namespace App\Http\Controllers;

use App\Models\pelangganModel;
use App\Models\PengelolaModel;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Hash; 

class RegisController extends Controller
{
    // show halaman regis
    public function showRegisAdmin()
    {

        // return view('auth.regis');
        return view('auth.regisAdmin');
    }

    public function showRegis()
    {

        // return view('auth.regis');
        return view('auth.regis');
    }


    // private function generateIdPelanggan()
    // {
    //     // Ambil ID terbesar terakhir
    //     $last = \App\Models\pelangganModel::orderBy('id_pelanggan', 'DESC')->first();

    //     if (!$last) {
    //         return "PL001";
    //     }

    //     // Ambil angka dari PL001 → 001
    //     $num = intval(substr($last->id_pelanggan, 2)) + 1;

    //     // Generate format baru
    //     return "PL" . str_pad($num, 3, '0', STR_PAD_LEFT);
    // }

    public function storeAdmin(Request $request)
    {
        // dd($request->all()); 
        $request->validate([
            'nama_pengelola' => 'required',
            'username'       => 'required',
            'password'       => 'required',
        ]);

        // Generate ID format: PEL-001, PEL-002, dst.
        // $last = pelangganModel::orderBy('id_pelanggan', 'DESC')->first();
        // $next = $last ? intval(substr($last->id_pelanggan, 4)) + 1 : 1;
        // $id = 'PEL-' . str_pad($next, 3, '0', STR_PAD_LEFT);


        // Simpan data
        PengelolaModel::create([
            // 'id_pelanggan'   => $id, // atau ubah sesuai format ID kamu
            // 'id_pelanggan'   => Str::uuid(), // atau ubah se suai format ID kamu
            'nama_pengelola' => $request->nama_pengelola,
            'username'       => $request->username,
            'password'       => $request->password,
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }


    


    public function store(Request $request)
    {
        // dd($request->all()); 
        $request->validate([
            'nama_pelanggan' => 'required',
            'username'       => 'required|string|max:50|unique:pelanggan,username',
            'email'          => 'required|email|max:255|unique:pelanggan,email',
            'password'       => 'required|min:6|confirmed',
            'no_telepon'     => ['required','regex:/^[0-9]{9,15}$/'],
        ]);

        // Generate ID format: PEL-001, PEL-002, dst.
        // $last = pelangganModel::orderBy('id_pelanggan', 'DESC')->first();
        // $next = $last ? intval(substr($last->id_pelanggan, 4)) + 1 : 1;
        // $id = 'PEL-' . str_pad($next, 3, '0', STR_PAD_LEFT);


        // Simpan data
        pelangganModel::create([
            // 'id_pelanggan'   => $this->generateIdPelanggan(), // atau ubah sesuai format ID kamu
            // 'id_pelanggan'   => Str::uuid(), // atau ubah se suai format ID kamu
            'nama_pelanggan' => $request->nama_pelanggan,
            'username'       => $request->username,
            'email'          => $request->email,
            // 'password'       => Hash::make($request->password),
            'password'       => $request->password,
            'no_telepon'     => $request->no_telepon,
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login.');
    }
   
    
}
