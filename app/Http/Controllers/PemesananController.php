<?php

namespace App\Http\Controllers;

use App\Models\lokasi;
use App\Models\pembayaran;
use App\Models\pemesanan;
use App\Models\User;
use Illuminate\Http\Request;

class PemesananController extends Controller
{
    public function index(Request $request)
    {
        $type_menu = 'pemesanan';

        // Ambil filter dari request
        $keyword = trim($request->input('search'));
        $status = $request->input('status');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        // Query pemesanans dengan filter yang diterapkan
        $pemesanans = pemesanan::with('user')
            ->when($keyword, function ($query) use ($keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->whereHas('user', function ($q2) use ($keyword) {
                        $q2->where('name', 'like', '%' . $keyword . '%');
                    })
                        ->orWhere('no_pemesanan', 'like', '%' . $keyword . '%');
                });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($bulan, function ($query, $bulan) {
                $query->whereMonth('created_at', $bulan);
            })
            ->when($tahun, function ($query, $tahun) {
                $query->whereYear('created_at', $tahun);
            })
            ->latest()
            ->paginate(10);

        // Tambahkan parameter query ke pagination
        $pemesanans->appends([
            'search' => $keyword,
            'status' => $status,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]);

        // Arahkan ke view (misalnya: resources/views/pages/pemesanans/index.blade.php)
        return view('pages.pemesanan.index', compact('type_menu', 'pemesanans'));
    }
    public function create()
    {
        $type_menu = 'pemesanan';
        $users = User::all();
        $lokasis = Lokasi::all();

        return view('pages.pemesanan.create', compact('type_menu', 'users', 'lokasis'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'lokasi_id' => 'required|exists:lokasis,id',
            'jumlah' => 'required|integer|min:1',
            'status' => 'required|in:Diterima,Diproses,Ditunda,Selesai',
            'catatan' => 'nullable|string',
            'total_harga' => 'required|numeric|min:0',
        ]);

        $tanggal = now()->format('Ymd');
        $jumlahHariIni = Pemesanan::whereDate('created_at', now()->toDateString())->count() + 1;
        $no_pemesanan = 'ORD-' . $tanggal . '-' . str_pad($jumlahHariIni, 3, '0', STR_PAD_LEFT);

        $pemesanan = Pemesanan::create([
            'no_pemesanan' => $no_pemesanan,
            'user_id' => $validated['user_id'],
            'lokasi_id' => $validated['lokasi_id'],
            'jumlah' => $validated['jumlah'],
            'status' => $validated['status'],
            'catatan' => $validated['catatan'],
            'total_harga' => $validated['total_harga'],
        ]);

        // 🔷 Kurangi stok jika status Diterima
        if ($validated['status'] === 'Diterima') {
            $lokasi = Lokasi::find($validated['lokasi_id']);

            if ($lokasi->stock_lpg >= $validated['jumlah']) {
                $lokasi->stock_lpg -= $validated['jumlah'];
                $lokasi->save();
            } else {
                return redirect()->route('pemesanan.index')
                    ->with('error', 'Stok LPG tidak cukup di lokasi ini.');
            }
        }

        return redirect()->route('pemesanan.index')
            ->with('success', 'Pemesanan ' . $pemesanan->no_pemesanan . ' berhasil ditambahkan.');
    }


    public function edit(Pemesanan $kelolapemesanan)
    {
        $type_menu = 'kelolapemesanan';
        $users = User::all();
        $lokasis = Lokasi::all();

        return view('pages.pemesanan.edit', [
            'type_menu' => $type_menu,
            'pemesanan' => $kelolapemesanan,
            'users' => $users,
            'lokasis' => $lokasis,
        ]);
    }

    public function update(Request $request, Pemesanan $pemesanan)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'lokasi_id' => 'required|exists:lokasis,id',
            'jumlah' => 'required|integer|min:1',
            'status' => 'required|in:Diterima,Diproses,Ditunda,Selesai',
            'catatan' => 'nullable|string',
            'total_harga' => 'required|numeric|min:0',
        ]);

        $lokasi = Lokasi::findOrFail($validated['lokasi_id']);

        // Cek apakah status lama bukan Diterima, dan status baru adalah Diterima
        if ($pemesanan->status !== 'Diterima' && $validated['status'] === 'Diterima') {
            // pastikan stok cukup
            if ($lokasi->stock_lpg >= $validated['jumlah']) {
                $lokasi->stock_lpg -= $validated['jumlah'];
                $lokasi->save();
            } else {
                return redirect()->back()->withInput()->with('error', 'Stok LPG tidak cukup di lokasi ini.');
            }
        }

        // Update data pemesanan
        $pemesanan->update($validated);

        return redirect()->route('pemesanan.index')->with('success', 'Pemesanan berhasil diperbarui.');
    }

    public function show(pemesanan $pemesanan)
    {
        $type_menu = 'pemesanan';
        // ambil data pembayaran terkait
        $pembayaran = pembayaran::where('pemesanan_id', $pemesanan->id)->first();

        return view('pages.pemesanan.show', [
            'pemesanan' => $pemesanan,
            'pembayaran' => $pembayaran,
            'type_menu' => $type_menu,
        ]);
    }
    public function destroy(pemesanan $kelolapemesanan)
    {
        $kelolapemesanan->delete();
        return redirect()->route('kelolapemesanan.index')->with('success', 'pemesanan dengan No pemesanan ' . $kelolapemesanan->no_pemesanan . ' berhasil dihapus.');
    }
    // ✅ Menampilkan pemesanan yang statusnya "Diproses"
    public function showProses()
    {
        $type_menu = 'kelolapemesanan';
        $pemesanans = pemesanan::where('status', 'Diproses')->latest()->paginate(10);
        return view('pages.kelolapemesanan.proses', compact('pemesanans', 'type_menu'));
    }

    // ✅ Update status pemesanan via modal
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => ['required', 'in:Diproses,Diterima,Ditolak,Dibatalkan,Selesai,Ditunda'],
            'status_pembayaran' => ['nullable', 'in:Menunggu Pembayaran,Proses Pembayaran,Pembayaran Berhasil'],
        ]);

        $pemesanan = pemesanan::with('pembayaran')->findOrFail($id);

        $pemesanan->status = $request->status;
        $pemesanan->save();

        if ($pemesanan->pembayaran) {
            $pemesanan->pembayaran->status = $request->status_pembayaran;
            $pemesanan->pembayaran->save();
        }
        // // Kirim notifikasi
        // $fonnteService = app(FonnteService::class);
        // // 🔹 Kirim ke pelanggan
        // $customerPhone = $pemesanan->user->no_handphone;

        // if ($customerPhone) {
        //     $messageCustomer = "🧺 *Pesanan Self Service Anda!*\n\n" .
        //         "🧾 *No pemesanan:* {$pemesanan->no_pemesanan}\n" .
        //         "📅 *Tanggal pemesanan:* {$pemesanan->tanggal_pemesanan}\n" .
        //         "⏰ *Jam pemesanan:* {$pemesanan->jam_pemesanan}\n" .
        //         "⏳ *Durasi:* {$pemesanan->durasi} menit\n" .
        //         "🪙 *Mesin:* {$pemesanan->mesin->nama}\n" .
        //         "💰 *Koin:* {$pemesanan->koin}\n" .
        //         "📝 *Catatan:* " . ($pemesanan->catatan ?: '-') . "\n" .
        //         "💵 *Total Biaya:* Rp " . number_format($pemesanan->total_biaya, 0, ',', '.') . "\n\n" .
        //         "📌 *Layanan:* {$pemesanan->service_type}\n\n" .
        //         "💳 *Status Pembayaran:* " . ($pemesanan->pembayaran->status ?? '-') . "\n" .
        //         "💰 *Jumlah yang harus dibayar:* Rp " . number_format($pemesanan->pembayaran->jumlah_dibayar ?? 0, 0, ',', '.') . "\n\n" .
        //         "Terima kasih 🙏";

        //     $fonnteService->sendMessage($customerPhone, $messageCustomer);
        // }

        return redirect()->back()->with('success', 'Status pemesanan ' . $pemesanan->no_pemesanan . ' berhasil diperbarui.');
    }
    public function showDiterima()
    {
        $type_menu = 'pemesanan';
        $pemesanans = pemesanan::where('status', 'Diterima')->latest()->paginate(10);
        return view('pages.kelolapemesanan.terima', compact('pemesanans', 'type_menu'));
    }

}
