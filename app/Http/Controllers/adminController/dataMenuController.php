<?php

namespace App\Http\Controllers\adminController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\admin\menuModel;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class dataMenuController extends Controller
{
    // home admin
    public function home()
    {

        if(Session::get('id_pengelola') === null)
        {
            abort(403, 'Unauthorize');
        }
        return view('Admin.dashboard');
    }
    
    //DATA MENU 
    public function showMakanan(Request $request)
    {
        $perPage = $request->get('per_page', 5);
        $search  = $request->get('search');

        $makanan = menuModel::where('kategori', 'makanan')
            ->when($search, function ($query, $search) {
                $query->where('nama_menu', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            })
            ->paginate($perPage)
            ->withQueryString(); // supaya search & per_page tidak hilang

        return view('Admin.makanan', compact('makanan', 'search'));
    }

    public function showMinuman(Request $request)
    {

        $perPage = $request->get('per_page', 5);
        $search  = $request->get('search');

        $minuman = menuModel::where('kategori', 'minuman')
            ->when($search, function ($query, $search) {
                $query->where('nama_menu', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            })
            ->paginate($perPage)
            ->withQueryString(); // supaya search & per_page tidak hilang
        return view('Admin.minuman', compact('minuman', 'search'));
    }

    public function formMenu($kategori)
    {
        return view('Admin.tambahMenu', compact('kategori'));
    }

    // public function formMinuman()
    // {
    //     return view('Admin.tambahMinuman');
    // }


    public function destroy($id)
    {
        $menu = menuModel::findOrFail($id);

        $duplicate = menuModel::whereRaw('LOWER(nama_menu) = LOWER(?)', [trim($request->nama_menu)])
            ->where('id_menu', '!=', $id)
            ->exists();
        if ($duplicate) {
            return back()->withInput()->with('gagal', 'Nama menu sudah digunakan. Gunakan nama menu lain.');
        }

        // Hapus gambar dari storage
        if ($menu->gambar && Storage::disk('public')->exists($menu->gambar)) {
            Storage::disk('public')->delete($menu->gambar);
        }

        $menu->delete();

        return redirect()
            ->back()
            ->with('success', 'Menu berhasil dihapus');
    }

    public function edit($id)
    {
        $menu = menuModel::where('id_menu', $id)->findOrFail($id);
        return view('Admin.menuEdit', compact('menu'));

    }


    public function update(Request $request, $id)
    {
        // dd($request->all());
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga'     => 'required|numeric',
            'stok'      => 'required|integer|min:0',
            'deskripsi' => 'nullable|string',
            'gambar'    => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);


        $menu = menuModel::findOrFail($id);

        // Jika upload gambar baru
        if ($request->hasFile('gambar')) {
            if ($menu->gambar && Storage::disk('public')->exists($menu->gambar)) {
                Storage::disk('public')->delete($menu->gambar);
            }

            $namaFile = Str::random(10) . '.' . $request->gambar->extension();
            $menu->gambar = $request->gambar->storeAs('menu', $namaFile, 'public');
        }

        
        $update = $menu->update([
            'nama_menu' => $request->nama_menu,
            'harga'     => $request->harga,
            'stok'      => $request->stok,
            'deskripsi' => $request->deskripsi,
        ]);

        // dd($update);

        return redirect()
            ->back()
            ->with('success', 'Menu berhasil diperbarui');
    }

    public function show($id)
    {
        return response()->json(
            menuModel::findOrFail($id)
        );
    }


    // private function generateIdMenu()
    // {
    //     // Ambil ID terbesar terakhir
    //     $last = \App\Models\admin\menuModel::orderBy('id_menu', 'DESC')->first();

    //     if (!$last) {
    //         return "MEN001";
    //     }
        
    //     // Ambil angka setelah "MEN"
    //     $num = intval(substr($last->id_menu, 3)) + 1;

    //     // Generate format baru
    //     return "MEN" . str_pad($num, 3, '0', STR_PAD_LEFT);
    // }


    public function addMenu(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'kategori'  => 'required',
            'harga'     => 'required|numeric',
            'deskripsi' => 'nullable|string',
            'gambar'    => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'stok' => 'required|integer|min:0',
            'jenis' => 'required',
        ]);

        // dd($request->all());

        if (menuModel::whereRaw('LOWER(nama_menu) = LOWER(?)', [trim($request->nama_menu)])->exists()) {
            return back()->withInput()->with('gagal', 'Nama menu sudah digunakan. Gunakan nama menu lain.');
        }

        // Simpan gambar
        $namaFile = Str::random(10) . '.' . $request->gambar->extension();
        $path = $request->gambar->storeAs('menu', $namaFile, 'public');

        // dd($request->all());

        try {
            // Simpan data
            DB::table('menu')->insert([
                // 'id_menu'   => $this->generateIdMenu(),
                'id_pengelola' => Session::get('id_pengelola'),
                'nama_menu' => $request->nama_menu,
                'kategori'  => $request->jenis,
                'harga'     => $request->harga,
                'deskripsi' => $request->deskripsi,
                'gambar'    => $path, 
                'stok'      => $request->stok, 
                'jenis'     => $request->kategori,
            ]);

            

            if ($request->jenis === 'minuman') {
                # code...
                return redirect()
                ->route('showMinuman')
                ->with('success', 'Menu berhasil ditambahkan!');
            }else{
                return redirect()
                ->route('showMakanan')
                ->with('success', 'Menu berhasil ditambahkan!');

            }
            
        } catch (\Throwable $th) {
            // dd($th);
             if ($request->jenis === 'minuman') {
                return redirect()
                ->route('showMinuman')
                ->with('gagal', 'Menu gagal ditambahkan!');
            }else{
                return redirect()
                ->route('showMakanan')
                ->with('gagal', 'Menu gagal ditambahkan!');

            }
       }

        // dd($request->all());



      
    }
}
