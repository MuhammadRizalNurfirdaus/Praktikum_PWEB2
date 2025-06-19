<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{
    // Menampilkan halaman keranjang
    public function index()
    {
        $cartItems = Session::get('cart', []);
        $totalPrice = 0;
        foreach ($cartItems as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }
        return view('cart.index', compact('cartItems', 'totalPrice'));
    }

    // Menambahkan produk ke keranjang
    public function add(Request $request, Product $product)
    {
        $cart = Session::get('cart', []);
        $quantity = $request->input('quantity', 1); // Ambil kuantitas dari form, default 1

        if (isset($cart[$product->id])) {
            // Jika produk sudah ada di keranjang, tambahkan kuantitasnya
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            // Jika produk belum ada, tambahkan sebagai item baru
            $cart[$product->id] = [
                "name" => $product->name,
                "quantity" => $quantity,
                "price" => $product->price,
                "image_path" => $product->image_path,
                "size" => $product->size, // Tambahkan info ukuran jika perlu
            ];
        }

        Session::put('cart', $cart);
        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    // Mengupdate kuantitas item di keranjang
    public function update(Request $request, $productId)
    {
        $cart = Session::get('cart', []);
        $quantity = $request->input('quantity');

        if (isset($cart[$productId]) && $quantity > 0) {
            $cart[$productId]['quantity'] = $quantity;
            Session::put('cart', $cart);
            return redirect()->route('cart.index')->with('success', 'Kuantitas produk berhasil diperbarui.');
        } elseif (isset($cart[$productId]) && $quantity <= 0) {
            // Jika kuantitas 0 atau kurang, hapus item
            unset($cart[$productId]);
            Session::put('cart', $cart);
            return redirect()->route('cart.index')->with('success', 'Produk dihapus dari keranjang.');
        }
        return redirect()->route('cart.index')->with('error', 'Gagal memperbarui keranjang.');
    }

    // Menghapus item dari keranjang
    public function remove($productId)
    {
        $cart = Session::get('cart', []);
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            Session::put('cart', $cart);
            return redirect()->route('cart.index')->with('success', 'Produk berhasil dihapus dari keranjang.');
        }
        return redirect()->route('cart.index')->with('error', 'Produk tidak ditemukan di keranjang.');
    }

    // Menghapus semua item dari keranjang
    public function clear()
    {
        Session::forget('cart');
        return redirect()->route('cart.index')->with('success', 'Keranjang berhasil dikosongkan.');
    }
}
// End of CartController.php