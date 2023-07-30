<?php

namespace App\Http\Controllers;

use App\Models\siswa;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;

class siswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $katakunci = $request->katakunci;
        $jumlahbaris = 10;
        if(strlen($katakunci)) {
            $data = siswa::where('nis', 'like', "%$katakunci%")
                ->orWhere('nama', 'like', "%$katakunci%")
                ->orWhere('kelas', 'like', "%$katakunci%")
                ->paginate($jumlahbaris);
        } else {
            $data = siswa::orderBy('nis', 'asc')->paginate($jumlahbaris);
        }
        return view('siswa.index')->with('data', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('siswa.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Session()->flash('nis',$request->nis);
        Session()->flash('nama',$request->nama);
        Session()->flash('kelas',$request->kelas);
        $request->validate([
            'nis' => 'required|numeric|unique:siswa,nis',
            'nama' => 'required',
            'kelas' => 'required'
        ], [
            'nis.required' => 'NIS wajib diisi',
            'nis.numeric' => 'NIS hanya diisi angka',
            'nis.unique' => 'NIS sudah ada',
            'nama.required' => 'Nama wajib diisi',
            'kelas.required' => 'kelas wajib diisi'
        ]);
        $data = [
            'nis' => $request->nis,
            'nama' => $request->nama,
            'kelas' => $request->kelas
        ];
        siswa::create($data);
        return redirect()->to('siswa')->with('success', 'Data Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = siswa::where('nis', $id)->first();
        return view('siswa.edit')->with('data', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'nama' => 'required',
            'kelas' => 'required'
        ], [
            'nama.required' => 'Nama wajib diisi',
            'kelas.required' => 'kelas wajib diisi'
        ]);
        $data = [
            'nama' => $request->nama,
            'kelas' => $request->kelas
        ];
        siswa::where('nis', $id)->update($data);
        return redirect()->to('siswa')->with('success', 'Data Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        siswa::where('nis',$id)->delete();
        return redirect()->to('siswa')->with('success', 'Data Berhasil Dihapus');
    }
}
