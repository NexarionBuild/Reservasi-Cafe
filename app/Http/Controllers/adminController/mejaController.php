<?php

namespace App\Http\Controllers\adminController;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Meja;

class mejaController extends Controller
{
        /**
        * MENAMBAHKAN DATA MEJA
        */

        public function showMeja()
        {
            $mejaIndoor = Meja::where('ruangan', 'Indoor')->orWhere('ruangan', 'Indoor')->get();
            $mejaOutdoor = Meja::where('ruangan', 'Outdoor')->get();
            return view('admin.dataMeja', compact('mejaIndoor', 'mejaOutdoor'));
        }

        /**
        * MENAMBAHKAN DATA MEJA
        */

        public function showFormMeja()
        {
            return view('admin.tambahMeja');
        }


        public function store(Request $request)
        {
            $request->validate([
                'kode_meja' => 'required|unique:meja,kode_meja',
                'ruangan'  => 'required|string',
            ]);

            $meja = Meja::create([
                'kode_meja' => $request->kode_meja,
                'ruangan'  => $request->ruangan,
            ]);

            return redirect()
                ->route('dataMeja.showMeja')
                ->with('success', 'Meja berhasil ditambahkan');
        }


        /**
         * MENGHAPUS DATA MEJA
         */
        public function destroy($id)
        {
            $meja = Meja::findOrFail($id);
            $meja->delete();

            return redirect()
                ->back()
                ->with('success', 'Meja berhasil dihapus');
        }
}
