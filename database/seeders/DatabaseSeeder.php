<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Admin',
                'email' => 'admin@example.com',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cashier',
                'email' => 'cashier@example.com',
                'password' => Hash::make('password'),
                'role' => 'cashier',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kitchen',
                'email' => 'kitchen@example.com',
                'password' => Hash::make('password'),
                'role' => 'kitchen',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Tables (Meja)
        foreach (range(1, 10) as $i) {
            $tableNumber = 'T' . $i;
            $qrData = url("/order/table/" . $tableNumber); // URL yang diakses dari QR
            $qrPath = "qr_codes/{$tableNumber}.svg";

            // Simpan QR code ke disk public
            Storage::disk('public')->put($qrPath, QrCode::format('svg')->size(300)->generate($qrData));

            DB::table('tables')->insert([
                'table_number' => $tableNumber,
                'status' => 'available',
                'qr_code' => $qrPath,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Menu Categories
        $categories = ['Makanan', 'Minuman', 'Snack'];
        foreach ($categories as $name) {
            DB::table('menu_categories')->insert([
                'name' => $name,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Menus (5 per kategori)
        $menuId = 1;
        foreach (DB::table('menu_categories')->get() as $category) {
            foreach (range(1, 5) as $i) {
                DB::table('menus')->insert([
                    'name' => "{$category->name} {$i}",
                    'description' => "Deskripsi untuk {$category->name} {$i}",
                    'price' => rand(10000, 50000),
                    'photo' => "menu/{$category->name}_{$i}.jpg", // contoh path
                    'category_id' => $category->id,
                    'is_available' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $menuId++;
            }
        }

        // Ambil data
        $menus = DB::table('menus')->get();
        $tables = DB::table('tables')->get();

        // Pastikan ada menu dan meja
        if ($menus->isEmpty() || $tables->isEmpty()) {
            return;
        }

        // Simulasi pemesanan 90 hari terakhir
        $statuses = ['pending', 'in_process', 'served', 'paid'];
        $now = Carbon::now();

        foreach (range(0, 89) as $dayOffset) {
            $date = $now->copy()->subDays($dayOffset);

            foreach (range(1, rand(1, 3)) as $i) {
                $table = $tables->random();
                $orderStatus = $statuses[array_rand($statuses)];
                $orderedAt = $date->copy()->setTime(rand(10, 20), rand(0, 59)); // Waktu antara 10:00 - 20:59

                // Insert order awal (total_amount akan di-update setelah insert item)
                $orderId = DB::table('orders')->insertGetId([
                    'table_id' => $table->id,
                    'status' => $orderStatus,
                    'total_amount' => 0,
                    'ordered_at' => $orderedAt,
                    'paid_at' => in_array($orderStatus, ['paid']) ? $orderedAt->copy()->addMinutes(rand(20, 90)) : null,
                    'created_at' => $orderedAt,
                    'updated_at' => $orderedAt,
                ]);

                // Pilih menu dan buat order_items
                $totalAmount = 0;
                $menuSamples = $menus->random(rand(1, 4));
                foreach ($menuSamples as $menu) {
                    $quantity = rand(1, 3);
                    $subtotal = $menu->price * $quantity;
                    $totalAmount += $subtotal;

                    DB::table('order_items')->insert([
                        'order_id' => $orderId,
                        'menu_id' => $menu->id,
                        'quantity' => $quantity,
                        'price' => $menu->price,
                        'note' => rand(0, 1) ? 'Tanpa sambal' : null,
                        'status' => 'waiting',
                        'created_at' => $orderedAt,
                        'updated_at' => $orderedAt,
                    ]);
                }

                // Update total_amount di orders
                DB::table('orders')->where('id', $orderId)->update([
                    'total_amount' => $totalAmount,
                ]);
            }
        }
    }
}
