<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->string('table_number')->unique();
            $table->enum('status', ['available', 'occupied'])->default('available');
            $table->string('qr_code')->nullable();
            $table->timestamps();
        });

        // Kategori menu
        Schema::create('menu_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Menu
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->string('photo')->nullable(); // Path ke file foto
            $table->foreignId('category_id')->constrained('menu_categories')->onDelete('cascade');
            $table->boolean('is_available')->default(true);
            $table->timestamps();
        });

        // Pesanan
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('table_id')->constrained('tables')->onDelete('cascade');
            $table->enum('status', ['pending', 'in_process', 'served', 'paid'])->default('pending');
            $table->decimal('total_amount', 10, 2)->default(0);
            $table->timestamp('ordered_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });

        // Detail pesanan
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('menu_id')->constrained('menus')->onDelete('restrict');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->text('note')->nullable();
            $table->enum('status', ['waiting', 'cooking', 'done'])->default('waiting');
            $table->timestamps();
        });

        // Pengguna (admin, kasir, dapur)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'cashier', 'kitchen']);
            $table->rememberToken();
            $table->timestamps();
        });

        // Pembayaran
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('paid_by')->constrained('users')->onDelete('restrict');
            $table->enum('payment_method', ['cash', 'qr', 'debit']);
            $table->decimal('amount', 10, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('menus');
        Schema::dropIfExists('menu_categories');
        Schema::dropIfExists('tables');
        Schema::dropIfExists('users');
    }
};
