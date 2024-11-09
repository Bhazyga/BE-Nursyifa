<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Transaksi;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{

    public function index()
    {
        $transaksi = Transaksi::orderBy('id')->paginate(10);
        return response()->json($transaksi, 200);
    }

    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'namapenyewa' => 'required|string|max:255',
            'alamatpenyewa' => 'required|string|max:255',
            'notelp' => 'required|string|max:15',
            'bis' => 'nullable|string|max:255',
            'berapalama' => 'nullable|string|max:255',
            'harga' => 'required|numeric',
            'tanggal' => 'required|date',
        ]);

        // Create new transaction
        $transaksi = new Transaksi();
        $transaksi->namapenyewa = $validated['namapenyewa'];
        $transaksi->alamatpenyewa = $validated['alamatpenyewa'];
        $transaksi->notelp = $validated['notelp'];
        $transaksi->bis = $validated['bis'];
        $transaksi->berapalama = $validated['berapalama'];
        $transaksi->harga = $validated['harga'];
        $transaksi->tanggal = $validated['tanggal'];

        // Save the transaction to the database
        $transaksi->save();

        // Return success response
        return response()->json([
            'message' => 'Transaksi berhasil disimpan',
            'data' => $transaksi
        ], 201);
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();

        return response()->json(['message' => 'Transaksi Berhasil Dihapus!!!'], 200);
    }

}
