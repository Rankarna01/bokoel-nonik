<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Table;
use App\Models\Menu;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $tableCount = Table::count();
        $menuCount = Menu::count();
        $orderCount = Order::count();
        $userCount = User::count();

        // Ambil 3 bulan terakhir
        $startDate = Carbon::now()->subMonths(2)->startOfMonth(); // 2 bulan sebelumnya
        $endDate = Carbon::now()->endOfMonth();

        $orders = Order::whereBetween('created_at', [$startDate, $endDate])->get();

        // Inisialisasi array bulan
        $period = collect();
        for ($date = $startDate->copy(); $date <= $endDate; $date->addMonth()) {
            $period->push($date->format('F Y')); // contoh: "May 2025"
        }

        // Hitung total order per bulan
        $ordersPerMonth = $orders->groupBy(function ($order) {
            return Carbon::parse($order->created_at)->format('F Y');
        })->map->count();

        $months = $period->toArray();
        $totals = $period->map(fn($month) => $ordersPerMonth[$month] ?? 0)->toArray();

        return view('dashboard', compact(
            'tableCount',
            'menuCount',
            'orderCount',
            'userCount',
            'months',
            'totals'
        ));
    }
}
