<?php

namespace App\Http\Controllers;

use App\Models\layanan as ModelsLayanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class layanan extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $layanans = ModelsLayanan::all();

        return view("admin.index", compact('layanans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("admin.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'nama_layanan' => 'required',
            'deskripsi_layanan' => 'required',
            'gambar' => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'harga' => 'required',
            'status' => 'required',
        ]);

        $gambar = $request->file('gambar');
        $gambar->storeAs('public/layanan', $gambar->hashName());

        ModelsLayanan::create([
            'nama_layanan' => $request->nama_layanan,
            'deskripsi_layanan' => $request->deskripsi_layanan,
            'gambar' => $gambar->hashName(),
            'harga' => $request->harga,
            'status' => $request->status,
        ]);

        return redirect()->route('admin.index')->with(['success' => 'data kesimpen']);
    }

/**
 * Display the specified resource.
 */
    public function show(string $id)
    {
        $layanan = ModelsLayanan::find($id);
        return view('admin.detail', compact('layanan'));
    }

/**
 * Show the form for editing the specified resource.
 */
    public function edit(string $id)
    {
        $layanan = ModelsLayanan::find($id);
        return view('admin.edit', compact('layanan'));
    }

/**
 * Update the specified resource in storage.
 */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'nama_layanan' => 'required',
            'deskripsi_layanan' => 'required',
            'harga' => 'required',
            'status' => 'required',
        ]);

        $layanan = ModelsLayanan::find($id);

        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $gambar->storeAs('public/layanan', $gambar->hashName());
            Storage::delete('public/posts/' . $layanan->image);

            $layanan->update([
                'nama_layanan' => 'required',
                'deskripsi_layanan' => 'required',
                'gambar' => $gambar->hashName(),
                'harga' => 'required',
                'status' => 'required',
            ]);

        } else {
            $layanan->update([
                'nama_layanan' => 'required',
                'deskripsi_layanan' => 'required',
                'harga' => 'required',
                'status' => 'required',
            ]);

        }

        return redirect()->route('layanan.index')->with(['success' => 'Data Berhasil Diperbarui!']);
    }

/**
 * Remove the specified resource from storage.
 */
    public function destroy(string $id)
    {
        $layanan = ModelsLayanan::find($id);
        $layanan->delete();

        return redirect()->route('layanan.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }

}
