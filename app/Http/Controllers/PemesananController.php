<?php

namespace App\Http\Controllers;

use App\Models\lokasi;
use App\Models\pembayaran;
use App\Models\pemesanan;
use App\Models\stok_lpg;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PemesananController extends Controller
{
    public function index(Request $request)
    {
        $type_menu = 'pemesanan';

        $keyword = trim($request->input('search'));
        $status = $request->input('status');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        $authUser = Auth::user();

        // Mulai query dasar
        $pemesanans = Pemesanan::with('user')
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
            });

        // Filter jika user adalah Pangkalan
        if ($authUser->role === 'Pangkalan') {
            $lokasi = Lokasi::where('user_id', $authUser->id)->first();

            if ($lokasi) {
                $pemesanans = $pemesanans
                    ->where('lokasi_id', $lokasi->id)
                    ->whereHas('user', function ($q) {
                        $q->where('role', 'Pelanggan');
                    });
            }
        }

        // Pagination & query string
        $pemesanans = $pemesanans->latest()->paginate(10)->appends([
            'search' => $keyword,
            'status' => $status,
            'bulan' => $bulan,
            'tahun' => $tahun,
        ]);

        return view('pages.pemesanan.index', compact('type_menu', 'pemesanans'));
    }


    public function create()
    {
        $type_menu = 'pemesanan';

        $user = Auth::user();

        if ($user->role === 'Admin') {
            // Jika role Admin, ambil semua user dan semua lokasi
            $users = User::all();
        } else {
            // Role lainnya tidak diperbolehkan mengakses
            abort(403, 'Unauthorized action.');
        }

        return view('pages.pemesanan.create', compact('type_menu', 'users'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'jumlah' => 'required|integer|min:1',
            'status' => 'required|in:Diterima,Diproses,Ditunda,Selesai',
            'catatan' => 'nullable|string',
            'total_harga' => 'required|numeric|min:0',
        ]);

        $tanggal = now()->format('Ymd');
        $jumlahHariIni = Pemesanan::whereDate('created_at', now()->toDateString())->count() + 1;
        $no_pemesanan = 'ORD-' . $tanggal . '-' . str_pad($jumlahHariIni, 3, '0', STR_PAD_LEFT);

        // 🔷 Hanya kurangi stok jika status = Diterima
        if ($validated['status'] === 'Diterima') {
            // cari stok_lpg yg sesuai
            $stok = User::all();

            if (!$stok) {
                return redirect()->route('pemesanan.index')
                    ->with('error', 'Stok LPG untuk pengguna & lokasi ini tidak ditemukan.');
            }

            if ($stok->jumlah < $validated['jumlah']) {
                return redirect()->route('pemesanan.index')
                    ->with('error', 'Stok LPG tidak cukup. Tersedia: ' . $stok->jumlah);
            }

            // kurangi stok
            $stok->jumlah -= $validated['jumlah'];
            $stok->save();
        }

        // buat pemesanan
        $pemesanan = Pemesanan::create([
            'no_pemesanan' => $no_pemesanan,
            'user_id' => $validated['user_id'],
            'jumlah' => $validated['jumlah'],
            'status' => $validated['status'],
            'catatan' => $validated['catatan'],
            'total_harga' => $validated['total_harga'],
        ]);

        // Buat no_pembayaran
        $jumlahPembayaranHariIni = Pembayaran::whereDate('created_at', now()->toDateString())->count() + 1;
        $no_pembayaran = 'PAY-' . $tanggal . '-' . str_pad($jumlahPembayaranHariIni, 3, '0', STR_PAD_LEFT);

        // Buat data pembayaran awal
        $pembayaran = Pembayaran::create([
            'no_pembayaran' => $no_pembayaran,
            'pemesanan_id' => $pemesanan->id,
            'metode_pembayaran' => 'Belum dipilih',
            'jumlah_dibayar' => $pemesanan->total_harga,
            'status' => 'Menunggu Pembayaran',
        ]);

        return redirect()->route('pemesanan.index')
            ->with('success', 'Pemesanan ' . $pemesanan->no_pemesanan . ' berhasil ditambahkan');
    }
    public function edit($id)
    {
        $type_menu = 'pemesanan';
        $pemesanan = Pemesanan::findOrFail($id);
        $user = Auth::user();

        if ($user->role === 'Admin') {
            $users = User::all();
        } else {
            abort(403, 'Unauthorized action.');
        }

        return view('pages.pemesanan.edit', compact('pemesanan', 'users', 'type_menu'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'jumlah' => 'required|integer|min:1',
            'status' => 'required|in:Diterima,Diproses,Ditunda,Ditolak,Selesai',
            'catatan' => 'nullable|string',
            'total_harga' => 'required|numeric|min:0',
        ]);

        $pemesanan = Pemesanan::findOrFail($id);
        $user = User::findOrFail($validated['user_id']);
        $stok_lpg = stok_lpg::first(); // diasumsikan hanya ada satu stok LPG

        if (!$user || !$stok_lpg) {
            return redirect()->back()->with('error', 'Stok LPG atau pengguna tidak ditemukan.');
        }

        $statusLama = $pemesanan->status;
        $jumlahLama = $pemesanan->jumlah;
        $statusBaru = $validated['status'];
        $jumlahBaru = $validated['jumlah'];

        DB::beginTransaction();
        try {
            // LOGIKA STOK BERDASARKAN STATUS
            if ($statusLama !== 'Diterima' && $statusBaru === 'Diterima') {
                // dari bukan Diterima → jadi Diterima → kurangi stok
                if ($stok_lpg->jumlah < $jumlahBaru || $user->jumlah < $jumlahBaru) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'Stok LPG atau kuota user tidak cukup.');
                }

                $stok_lpg->jumlah -= $jumlahBaru;
                $user->jumlah -= $jumlahBaru;
            }

            if ($statusLama === 'Diterima' && $statusBaru === 'Ditolak') {
                // dari Diterima → Ditolak → kembalikan stok
                $stok_lpg->jumlah += $jumlahLama;
                $user->jumlah += $jumlahLama;
            }

            // Jika dari Diterima ke Selesai/Proses/Tunda → stok tidak berubah

            $pemesanan->update([
                'user_id' => $validated['user_id'],
                'jumlah' => $validated['jumlah'],
                'status' => $validated['status'],
                'catatan' => $validated['catatan'],
                'total_harga' => $validated['total_harga'],
            ]);

            $stok_lpg->save();
            $user->save();

            DB::commit();
            return redirect()->route('pemesanan.index')
                ->with('success', 'Pemesanan ' . $pemesanan->no_pemesanan . ' berhasil diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(pemesanan $pemesanan)
    {
        $pemesanan->delete();
        return redirect()->route('pemesanan.index')->with('success', 'pemesanan dengan No pemesanan ' . $pemesanan->no_pemesanan . ' berhasil dihapus.');
    }
    public function show($id)
    {
        $type_menu = 'pemesanan';
        $pemesanan = Pemesanan::with(['user', 'lokasi', 'pembayaran'])->findOrFail($id);

        return view('pages.pemesanan.show', compact('pemesanan', 'type_menu'));
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:Diterima,Diproses,Ditunda,Selesai',
            'status_pembayaran' => 'required|in:Menunggu Pembayaran,Proses Pembayaran,Pembayaran Berhasil',
        ]);

        $pemesanan = Pemesanan::findOrFail($id);
        $statusLama = $pemesanan->status;
        $statusBaru = $validated['status'];
        $jumlah = $pemesanan->jumlah;

        $user = $pemesanan->user; // relasi user
        $stok_lpg = stok_lpg::first();

        if (!$user || !$stok_lpg) {
            return back()->with('error', 'Stok pengguna atau stok LPG tidak ditemukan.');
        }

        DB::beginTransaction();

        try {
            if (!($statusLama === 'Diterima' && $statusBaru === 'Selesai')) {
                if ($statusLama === 'Diterima' && $statusBaru !== 'Diterima') {
                    $user->jumlah += $jumlah;
                    $stok_lpg->jumlah += $jumlah;
                } elseif ($statusLama !== 'Diterima' && $statusBaru === 'Diterima') {
                    if ($user->jumlah < $jumlah || $stok_lpg->jumlah < $jumlah) {
                        DB::rollBack();
                        return back()->with('error', 'Stok tidak cukup.');
                    }
                    $user->jumlah -= $jumlah;
                    $stok_lpg->jumlah -= $jumlah;
                }

                $user->save();
                $stok_lpg->save();
            }

            $pemesanan->status = $statusBaru;
            $pemesanan->save();

            $pembayaran = $pemesanan->pembayaran;
            if ($pembayaran) {
                $pembayaran->status = $validated['status_pembayaran'];
                $pembayaran->save();
            } else {
                DB::rollBack();
                return back()->with('error', "Pembayaran untuk pemesanan {$pemesanan->no_pemesanan} belum tersedia.");
            }

            DB::commit();
            return redirect()->back()->with('success', "Status pemesanan {$pemesanan->no_pemesanan} berhasil diperbarui.");

        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function showDiterima(Request $request)
    {
        $type_menu = 'pemesanan';

        $user = Auth::user();
        $query = pemesanan::with(['user', 'pembayaran'])
            ->where('status', 'Diterima');

        if ($request->q) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('user', function ($qq) use ($request) {
                    $qq->where('name', 'like', '%' . $request->q . '%');
                })
                    ->orWhere('no_pemesanan', 'like', '%' . $request->q . '%');
            });
        }
        // Filter lokasi untuk role Pangkalan
        if ($user->role === 'Pangkalan') {
            $lokasi = lokasi::where('user_id', $user->id)->first();
            if ($lokasi) {
                $query->where('lokasi_id', $lokasi->id);
            }
        }

        $pemesanans = $query->latest()->paginate(10)->withQueryString();

        return view('pages.pemesanan.terima', [
            'pemesanans' => $pemesanans,
            'title' => 'Diterima',
            'request' => $request,
            'type_menu' => $type_menu,
        ]);
    }
    public function showProses(Request $request)
    {
        $type_menu = 'pemesanan';
        $user = Auth::user();

        $query = pemesanan::with(['user', 'pembayaran'])
            ->where('status', 'Diproses');

        if ($request->q) {
            $query->where(function ($q) use ($request) {
                $q->whereHas('user', function ($qq) use ($request) {
                    $qq->where('name', 'like', '%' . $request->q . '%');
                })
                    ->orWhere('no_pemesanan', 'like', '%' . $request->q . '%');
            });
        }

        // Filter lokasi untuk role Pangkalan
        if ($user->role === 'Pangkalan') {
            $lokasi = lokasi::where('user_id', $user->id)->first();
            if ($lokasi) {
                $query->where('lokasi_id', $lokasi->id);
            }
        }

        $pemesanans = $query->latest()->paginate(10)->withQueryString();

        return view('pages.pemesanan.proses', [
            'pemesanans' => $pemesanans,
            'title' => 'Diproses',
            'request' => $request,
            'type_menu' => $type_menu,
        ]);
    }
    public function order(Request $request)
    {
        $type_menu = 'pemesanan';
        $lokasi_id = $request->lokasi_id;

        return view('pages.pemesanan.order', compact('type_menu'));
    }

    public function storeOrder(Request $request)
    {
        $validated = $request->validate([
            'jumlah' => 'required|integer|min:1',
            'total_harga' => 'required|numeric',
            'catatan' => 'nullable|string',
        ]);

        $user = Auth::user();

        // Ambil stok pengecer (jumlah LPG user pengecer)
        $stok_user = $user->jumlah ?? 0;

        // Ambil stok pangkalan (asumsinya hanya satu untuk saat ini)
        $stok_pangkalan = Stok_lpg::first(); // sesuaikan jika menggunakan relasi atau berdasarkan lokasi

        if (!$stok_pangkalan || $stok_user <= 0 || $stok_user < $validated['jumlah'] || $stok_pangkalan->jumlah < $validated['jumlah']) {
            return redirect()
                ->route('toko.index') // arahkan ke halaman toko terdekat
                ->with('error', 'Stok LPG tidak mencukupi. Silakan cari toko terdekat yang tersedia.');
        }

        // Generate No. Pemesanan
        $tanggal = now()->format('Ymd');
        $jumlahHariIni = Pemesanan::whereDate('created_at', now()->toDateString())->count() + 1;
        $no_pemesanan = 'ORD-' . $tanggal . '-' . str_pad($jumlahHariIni, 3, '0', STR_PAD_LEFT);

        // Simpan pemesanan
        $pemesanan = Pemesanan::create([
            'user_id' => $user->id,
            'no_pemesanan' => $no_pemesanan,
            'jumlah' => $validated['jumlah'],
            'status' => 'Diproses',
            'catatan' => $validated['catatan'],
            'total_harga' => $validated['total_harga'],
        ]);

        // Kurangi stok pengecer
        // $user->jumlah -= $validated['jumlah'];
        // $user->save();

        // Kurangi stok pangkalan
        // $stok_pangkalan->jumlah -= $validated['jumlah'];
        // $stok_pangkalan->save();

        // Generate No. Pembayaran
        $jumlahPembayaranHariIni = Pembayaran::whereDate('created_at', now()->toDateString())->count() + 1;
        $no_pembayaran = 'PAY-' . $tanggal . '-' . str_pad($jumlahPembayaranHariIni, 3, '0', STR_PAD_LEFT);

        // Simpan data pembayaran
        $pembayaran = Pembayaran::create([
            'no_pembayaran' => $no_pembayaran,
            'pemesanan_id' => $pemesanan->id,
            'metode_pembayaran' => 'Belum dipilih',
            'jumlah_dibayar' => $pemesanan->total_harga,
            'status' => 'Menunggu Pembayaran',
        ]);

        return redirect()
            ->route('pembayaran.edit_user', $pembayaran->id)
            ->with('success', 'Pemesanan dengan No Order ' . $no_pemesanan . ' berhasil dibuat. Silakan lengkapi pembayaran Anda.');
    }

}