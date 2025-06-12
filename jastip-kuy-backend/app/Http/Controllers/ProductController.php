<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    // Tampilkan semua produk
    public function index()
    {
        $products = Product::all()->map(function ($product) {
            return [
                'id' => $product->id,
                'nama' => $product->name,
                'harga' => $product->price,
                'kategori' => $product->kategori,
                'gambar' => $product->image,
                'waktu_scraping' => $product->waktu_scraping,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $products,
        ]);
    }

    // Simpan produk baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'image' => 'nullable|string|max:255',
        ]);

        $product = Product::create([
            'id' => Str::uuid(),
            'name' => $validated['name'],
            'price' => $validated['price'],
            'image' => $validated['image'] ?? null,
            'kategori' => $request->input('kategori'),
            'waktu_scraping' => now()->format('m/d/y H:i'),
        ]);


        return response()->json([
            'success' => true,
            'message' => 'Product created successfully',
            'data' => $product,
        ], 201);
    }

    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $product->id,
                'nama' => $product->name,
                'harga' => $product->price,
                'kategori' => $product->kategori,
                'gambar' => $product->image,
                'waktu_scraping' => $product->waktu_scraping,
            ]
        ]);
    }

    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        // Validasi input, boleh sesuaikan fields yang boleh diupdate
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric',
            'image' => 'sometimes|required|string',
        ]);

        // Update data produk
        $product->update($validatedData);

        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully',
            'data' => $product,
        ]);
    }

    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found',
            ], 404);
        }

        $product->delete();

        return response()->json([
            'success' => true,
            'message' => 'Product deleted successfully',
        ]);
    }

}
