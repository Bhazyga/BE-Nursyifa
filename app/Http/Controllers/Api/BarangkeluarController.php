<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Material;
use Illuminate\Http\Request;
use App\Models\Barangkeluar;
use Illuminate\Support\Facades\DB;

class BarangkeluarController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */

    public function index()
    {
        $barangkeluar = Barangkeluar::with('material')->paginate(10);

        return response()->json($barangkeluar, 200);
    }

    public function store(Request $request)
    {
        // Validate the request input
        $request->validate([
            'material_id' => 'required|exists:materials,id',
            'quantity' => 'required|integer|min:1',
            'tanggal_keluar' => 'required|date',
            'keterangan' => 'nullable|string',
        ]);

        // Find the material and check stock availability
        $material = Material::findOrFail($request->material_id);

        if ($material->stok < $request->quantity) {
            return response()->json(['error' => 'Insufficient stock'], 400);
        }

        // Use a transaction to ensure atomicity
        DB::transaction(function () use ($material, $request) {
            // Reduce stock in the materials table
            $material->stok -= $request->quantity;
            $material->save();

            // Create the barangkeluar record
            Barangkeluar::create([
                'material_id' => $request->material_id,
                'quantity' => $request->quantity,
                'tanggal_keluar' => $request->tanggal_keluar,
                'keterangan' => $request->keterangan,
            ]);
        });

        return response()->json([
            'success' => 'Item released successfully',
            'data' => [
                'material_id' => $request->material_id,
                'quantity' => $request->quantity,
                'tanggal_keluar' => $request->tanggal_keluar,
                'keterangan' => $request->keterangan,
            ]
        ], 201);
    }

    public function destroy($id)
    {
        $barangkeluar = Barangkeluar::findOrFail($id);

        // Retrieve the associated material
        $material = Material::findOrFail($barangkeluar->material_id);

        DB::transaction(function () use ($barangkeluar, $material) {
            // Increment the stock in the materials table
            $material->stok += $barangkeluar->quantity;
            $material->save();

            // Delete the barangkeluar record
            $barangkeluar->delete();
        });

        return response()->json(['success' => 'Item deleted and stock updated successfully'], 200);
    }
}
