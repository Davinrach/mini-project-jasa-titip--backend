<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OrderController extends Controller
{
    // Membuat pesan/order baru
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|uuid|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1|max:10',
            'note' => 'nullable|string',
            'tujuan' => 'required|string',
        ]);

        // Hitung total harga dan ambil data produk
        $itemsData = [];
        $totalHarga = 0;

        foreach ($request->items as $item) {
            $product = \App\Models\Product::findOrFail($item['product_id']);
            $hargaAsli = $product->price;
            $quantity = $item['quantity'];

            $itemsData[] = [
                'product_id' => $product->id,
                'nama_produk' => $product->name,
                'harga_asli' => $hargaAsli,
                'quantity' => $quantity,
            ];

            $totalHarga += $hargaAsli * $quantity;
        }

        // Simpan ke DB dalam transaction
        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => Auth::id(),
                'note' => $request->note,
                'tujuan' => $request->tujuan,
                'total_harga' => $totalHarga,
                'tanggal' => Carbon::now(),
            ]);

            foreach ($itemsData as $item) {
                $order->items()->create($item);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => $order->load('items')
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: ' . $e->getMessage()
            ], 500);
        }
    }


    // Lihat daftar order user yang login
    public function index()
    {
        $orders = Order::with('items')
            ->where('user_id', Auth::id())
            ->orderBy('tanggal', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $orders,
        ]);
    }
}
