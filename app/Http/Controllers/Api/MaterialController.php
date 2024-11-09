<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMaterialRequest;
use App\Http\Requests\UpdateMaterialRequest;
use App\Http\Resources\MaterialResource;
use App\Models\Barangkeluar;
use Illuminate\Support\Facades\Storage;
use App\Models\Material;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return MaterialResource::collection(
        Material::query()->orderBy('id')->paginate(20));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMaterialRequest $request)
    {
        // Retrieve validated form data
    $data = $request->validated();

    // Decode base64 image data
    $base64Image = $request->input('gambar');
    $decodedImage = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64Image));
    Log::info('Decoded image data:', ['data' => $decodedImage]);

    // Generate a unique filename for the image
    $filename = uniqid() . '.jpg';
    Log::info('Generated filename:', ['filename' => $filename]);

    // Store the decoded image data in the storage system
    Storage::disk('public')->put('images/' . $filename, $decodedImage);
    Log::info('Image stored at:', ['path' => 'images/' . $filename]);

    // Update the 'gambar' field in the data array with the filename or URL of the stored image
    $data['gambar'] = $filename; // Or you can store the URL, depending on your storage configuration

    // Create Material
    $material = Material::create($data);
    Log::info('Material created:', ['material' => $material]);
        return response(new MaterialResource($material));
    }

    /**
     * Display the specified resource.
     */
    public function show(Material $material)
    {
        return new MaterialResource($material);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Material $material)
    {
        if ($request->has('stok')) {
            $request->validate([
                'stok' => 'required|integer|min:0',
            ]);
        } else {
            $request->validate([
                'nama' => 'required|string',
                'deskripsi' => 'required|string',
                // Include other fields as necessary
            ]);
        }

        $material->update($request->all());

        return new MaterialResource($material);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $material)
    {
        $material->delete();

        return response( "", 204);

    }

    public function checkoutMaterial(Request $request, Material $material)
    {
        // Validate the request data
        $validated = $request->validate([
            'quantity' => 'required|integer|min:1', // Ensure quantity is valid
        ]);

        $quantity = $validated['quantity'];

        // Step 1: Check if sufficient stock is available
        if ($material->stok < $quantity) {
            return response()->json(['message' => 'Not enough stock available.'], 400);
        }

        // Step 2: Update the stock of the material
        $material->stok -= $quantity;
        $material->save();

        // Step 3: Log the transaction in barangkeluars table
        Barangkeluar::create([
            'material_id' => $material->id,
            'quantity' => $quantity,
            // Add any other details you may want (e.g., user ID, date, etc.)
        ]);

        return response()->json(['message' => 'Material checked out successfully.']);
    }

    public function detailUserBeli(Material $material)
    {
        return new MaterialResource($material);
    }
}
