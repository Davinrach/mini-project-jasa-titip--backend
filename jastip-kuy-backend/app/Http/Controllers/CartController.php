<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Str;

class CartController extends Controller
{
    // Tampilkan isi keranjang user
    public function index(Request $request)
    {
        $user = $request->user();

        $cart = Cart::with('items.product')->where('user_id', $user->id)->first();

        if (!$cart) {
            return response()->json([
                'success' => true,
                'data' => null,
                'message' => 'Keranjang kosong',
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $cart,
        ]);
    }

    // Tambah produk ke keranjang
    public function addItem(Request $request)
    {
        $request->validate([
            'product_id' => 'required|uuid|exists:products,id',
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $user = $request->user();

        // Cari keranjang user, kalau belum ada buat baru
        $cart = Cart::firstOrCreate(
            ['user_id' => $user->id],
            ['id' => Str::uuid()]
        );

        // Cek apakah produk sudah ada di keranjang
        $cartItem = CartItem::where('cart_id', $cart->id)
            ->where('product_id', $request->product_id)
            ->first();

        if ($cartItem) {
            // Jumlah baru
            $newQty = $cartItem->quantity + $request->quantity;

            if ($newQty > 10) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maksimal jumlah produk per item adalah 10',
                ], 400);
            }

            $cartItem->quantity = $newQty;
            $cartItem->save();
        } else {
            // Tambah item baru
            CartItem::create([
                'id' => Str::uuid(),
                'cart_id' => $cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Produk berhasil ditambahkan ke keranjang',
        ]);
    }

    // Update quantity item di keranjang
    public function updateItem(Request $request, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1|max:10',
        ]);

        $user = $request->user();

        // Cari cart item berdasarkan id dan user ownership
        $cartItem = CartItem::where('id', $itemId)
            ->whereHas('cart', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Item tidak ditemukan di keranjang Anda',
            ], 404);
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return response()->json([
            'success' => true,
            'message' => 'Jumlah produk berhasil diperbarui',
        ]);
    }

    // Hapus item dari keranjang
    public function removeItem(Request $request, $itemId)
    {
        $user = $request->user();

        $cartItem = CartItem::where('id', $itemId)
            ->whereHas('cart', function ($q) use ($user) {
                $q->where('user_id', $user->id);
            })->first();

        if (!$cartItem) {
            return response()->json([
                'success' => false,
                'message' => 'Item tidak ditemukan di keranjang Anda',
            ], 404);
        }

        $cartItem->delete();

        return response()->json([
            'success' => true,
            'message' => 'Item berhasil dihapus dari keranjang',
        ]);
    }
}
