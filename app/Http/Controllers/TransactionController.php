<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Table;
use App\Models\Order;
use App\Models\OrderItem;

class TransactionController extends Controller
{
    public function index()
    {
        $orders = Order::with(['table', 'items.menu'])
            ->whereIn('status', ['pending', 'in_process'])
            ->orderBy('ordered_at', 'desc')
            ->get();

        return view('admin.kitchen.orders', compact('orders'));
    }

    public function updateItemStatus(Request $request, OrderItem $item)
    {
        $request->validate([
            'status' => 'required|in:waiting,cooking,done',
            'order_id' => 'required|exists:orders,id'
        ]);

        $item->update(['status' => $request->status]);

        // Update status order sesuai kondisi
        $order = Order::with('items')->find($request->order_id);
        $itemStatuses = $order->items->pluck('status')->unique();

        if ($itemStatuses->contains('cooking')) {
            $order->update(['status' => 'in_process']);
        } elseif ($itemStatuses->every(fn($s) => $s === 'done')) {
            $order->update(['status' => 'served']);
        }

        return response()->json([
            'success' => true,
            'message' => 'Status item berhasil diperbarui.'
        ]);
    }

    public function status()
    {
        $tables = Table::all();
        return view('admin.tables.status', compact('tables'));
    }

    public function served()
    {
        $orders = Order::with('table')
            ->where('status', 'served')
            ->orderByDesc('ordered_at')
            ->get();

        return view('admin.orders.served', compact('orders'));
    }

    public function pay(Order $order)
    {
        // Update status pesanan
        $order->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        // Update status meja menjadi available
        if ($order->table) {
            $order->table->update([
                'status' => 'available',
            ]);
        }

        return redirect()->route('admin.orders.served')->with('success', 'Pembayaran berhasil disimpan.');
    }
}
