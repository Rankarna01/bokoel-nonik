<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\MenuCategory;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Table;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class OrderController extends Controller
{
    public function showMenu($tableNumber)
    {
        // Ambil data meja berdasarkan table_number atau id
        $table = Table::where('table_number', $tableNumber)->firstOrFail();

        $tableName = $table->table_number;

        // Jika meja sudah ditempati
        if ($table->status === 'occupied') {
            // Cari order aktif untuk meja ini
            $activeOrder = Order::where('table_id', $table->id)
                ->whereIn('status', ['pending', 'in_process'])
                ->latest()
                ->first();

            if ($activeOrder) {
                return redirect()->route('order.status', $activeOrder->id);
            }
        }

        $menus = Menu::all();
        $categories = MenuCategory::all();
        $cart = session()->get('cart_' . $tableNumber, []);

        return view('order.menu', [
            'tableNumber' => $tableNumber,
            'tableName' => $tableName,
            'menus' => $menus,
            'categories' => $categories,
            'cart' => $cart,
        ]);
    }

    // AJAX tambah ke keranjang
    public function addToCartAjax(Request $request)
    {
        $menuId = $request->menu_id;
        $tableNumber = $request->table_number;

        $menu = Menu::findOrFail($menuId);

        $cartKey = 'cart_' . $tableNumber;
        $cart = session()->get($cartKey, []);

        if (isset($cart[$menuId])) {
            $cart[$menuId]['qty'] += 1;
        } else {
            $cart[$menuId] = [
                'name' => $menu->name,
                'price' => $menu->price,
                'qty' => 1
            ];
        }

        session()->put($cartKey, $cart);

        return response()->json(['success' => true]);
    }

    // AJAX hapus item dari keranjang
    public function removeCartItem(Request $request)
    {
        $menuId = $request->menu_id;
        $tableNumber = $request->table_number;
        $cartKey = 'cart_' . $tableNumber;
        $cart = session()->get($cartKey, []);

        if (isset($cart[$menuId])) {
            unset($cart[$menuId]);
            session()->put($cartKey, $cart);
        }

        return response()->json(['success' => true]);
    }

    // AJAX tampilkan preview keranjang
    public function getCartPreview($tableNumber)
    {
        $cart = session()->get('cart_' . $tableNumber, []);
        $totalQty = collect($cart)->sum('qty');
        $totalPrice = collect($cart)->reduce(fn($carry, $item) => $carry + ($item['price'] * $item['qty']), 0);

        return view('order.cart_preview', compact('cart', 'tableNumber', 'totalQty', 'totalPrice'))->render();
    }

    public function checkout(Request $request, $tableNumber)
    {
        $cartKey = 'cart_' . $tableNumber;
        $cart = session()->get($cartKey, []);

        if (empty($cart)) {
            return back()->with('error', 'Keranjang kosong.');
        }

        DB::beginTransaction();

        try {
            $table = Table::where('table_number', $tableNumber)->firstOrFail();

            $totalAmount = collect($cart)->reduce(function ($sum, $item) {
                if (!isset($item['qty'], $item['price'])) {
                    throw new \Exception('Format item tidak valid');
                }
                return $sum + ($item['qty'] * $item['price']);
            }, 0);

            $order = Order::create([
                'table_id' => $table->id,
                'status' => 'pending',
                'total_amount' => $totalAmount,
                'ordered_at' => now(),
            ]);

            foreach ($cart as $menuId => $item) {
                if (!is_numeric($menuId) || $item['qty'] <= 0) {
                    throw new \Exception('Data item tidak valid');
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $menuId,
                    'quantity' => $item['qty'],
                    'price' => $item['price'],
                    'status' => 'waiting',
                ]);
            }

            $table->update(['status' => 'occupied']);

            DB::commit();
            session()->forget($cartKey);

            return redirect()->route('order.status', $order->id)
                ->with('success', 'Pesanan berhasil dibuat.');
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            Log::error('Meja tidak ditemukan untuk checkout: ' . $e->getMessage());
            return back()->with('error', 'Nomor meja tidak ditemukan.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Gagal melakukan checkout: ' . $e->getMessage(), ['cart' => $cart]);
            return back()->with('error', 'Terjadi kesalahan saat memproses pesanan. Silakan coba lagi.');
        }
    }

    public function status(Order $order)
    {
        $order->load(['items.menu', 'table']);
        return view('order.status', compact('order'));
    }

    public function statusAjax(Order $order)
    {
        $order->load('items.menu');
        return view('order.status_partial', compact('order'))->render();
    }

    public function getStatusPartial($orderId)
    {
        $order = Order::with(['orderItems.menu'])->findOrFail($orderId);

        return view('order.status_partial', compact('order'))->render();
    }
}
