<?php

namespace App\Http\Controllers;

use App\Models\Table;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TableController extends Controller
{
    public function index()
    {
        $tables = Table::all();
        return view('tables.index', compact('tables'));
    }

    public function create()
    {
        return view('tables.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_number' => 'required|unique:tables,table_number',
            'status' => 'required|in:available,occupied',
        ]);

        $tableNumber = $request->table_number;
        $qrPath = "qr_codes/{$tableNumber}.svg";
        $qrData = url("/order/table/" . $tableNumber);

        // Simpan QR code dalam bentuk SVG ke storage/public/qr_codes
        Storage::disk('public')->put($qrPath, QrCode::format('svg')->size(300)->generate($qrData));

        Table::create([
            'table_number' => $tableNumber,
            'status' => $request->status,
            'qr_code' => $qrPath,
        ]);

        return redirect()->route('tables.index')->with('success', 'Meja berhasil ditambahkan.');
    }

    public function edit(Table $table)
    {
        return view('tables.edit', compact('table'));
    }

    public function update(Request $request, Table $table)
    {
        $request->validate([
            'table_number' => 'required|unique:tables,table_number,' . $table->id,
            'status' => 'required|in:available,occupied',
        ]);

        // Hapus QR code lama (jika ada dan file-nya masih tersedia)
        if ($table->qr_code && Storage::disk('public')->exists($table->qr_code)) {
            Storage::disk('public')->delete($table->qr_code);
        }

        $newTableNumber = $request->table_number;
        $qrPath = "qr_codes/{$newTableNumber}.svg";
        $qrData = url("/order/table/" . $newTableNumber);

        // Simpan QR code baru dalam bentuk SVG
        Storage::disk('public')->put($qrPath, QrCode::format('svg')->size(300)->generate($qrData));

        // Update record
        $table->update([
            'table_number' => $newTableNumber,
            'status' => $request->status,
            'qr_code' => $qrPath,
        ]);

        return redirect()->route('tables.index')->with('success', 'Data meja berhasil diperbarui dan QR baru digenerate.');
    }

    public function destroy(Table $table)
    {
        if ($table->qr_code) {
            Storage::disk('public')->delete($table->qr_code);
        }

        $table->delete();
        return redirect()->route('tables.index')->with('success', 'Meja berhasil dihapus.');
    }
}
