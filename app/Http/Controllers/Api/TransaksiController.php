<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{

    public function index()
    {
        $transaksi = Transaksi::orderBy('id')->paginate(10);
        return response()->json($transaksi, 200);
    }


    public function getTotalHarga()
    {
        $totalHarga = Transaksi::sum('harga');
        return response()->json(['Total_Harga' => $totalHarga], 200);
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


    public function getMonthlyTotalHarga()
    {
        // Get the monthly sum of 'harga' grouped by month
        $monthlyTotal = Transaksi::selectRaw('
                YEAR(tanggal) as year,
                MONTH(tanggal) as month,
                SUM(harga) as total')
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->get();

        // Format the data to match the chart format
        $monthlyData = $monthlyTotal->map(function($item) {
            return [
                'month' => Carbon::createFromFormat('Y-m', "{$item->year}-{$item->month}")->format('F'), // Format month name
                'total' => $item->total,
            ];
        });

        // Return the data
        return response()->json($monthlyData, 200);
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();

        return response()->json(['message' => 'Transaksi Berhasil Dihapus!!!'], 200);
    }

}
