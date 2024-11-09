<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembelian;
use Illuminate\Support\Facades\Log;

class PembelianController extends Controller
{
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'nohp' => 'required|integer|min:8',
            'namamaterial' => 'required|string',
            'alamat' => 'required|string',
            'status' => 'required|string',
            'namapembeli' => 'required|string|',
            'quantity' => 'required|integer|min:1',
        ]);

        // Create a new instance of Pembelian model and save it to the database
        $pembelian = new Pembelian();
        $pembelian->material_id = $validatedData['material_id'];
        $pembelian->nohp = $validatedData['nohp'];
        $pembelian->namamaterial = $validatedData['namamaterial'];
        $pembelian->alamat = $validatedData['alamat'];
        $pembelian->status = 'Belum Dikonfirmasi';
        $pembelian->namapembeli = $validatedData['namapembeli'];
        $pembelian->quantity = $validatedData['quantity'];
        $pembelian->save();

        // Return a response indicating success
        return response()->json(['message' => 'Pembelian berhasil disimpan'], 201);
    }

    public function index()
    {
        $pembelian = Pembelian::all();
        return response()->json($pembelian);
    }

    public function destroy($id)
    {

        $pembelian = Pembelian::find($id);
        if ($pembelian) {
            $pembelian->status = 'Pembelian Berhasil';
            $pembelian->save();
            return response()->json(['message' => 'Status pembelian updated successfully'], 200);
        } else {
            return response()->json(['message' => 'Pembelian not found.'], 404);
        }
    }

}
