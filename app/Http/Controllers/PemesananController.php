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
            $lokasis = lokasi::all();
        } elseif ($user->role === 'Pangkalan') {
            // Jika role Pangkalan, ambil user dan lokasi berdasarkan user yang login
            $users = User::where('role', 'Pelanggan')->get();
            $lokasis = lokasi::where('user_id', $user->id)->get();
        } else {
            // Role lainnya tidak diperbolehkan mengakses
            abort(403, 'Unauthorized action.');
        }

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

        // 🔷 Hanya kurangi stok jika status = Diterima
        if ($validated['status'] === 'Diterima') {
            // cari stok_lpg yg sesuai
            $stok = stok_lpg::where('user_id', $validated['user_id'])
                ->where('lokasi_id', $validated['lokasi_id'])
                ->first();

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
            'lokasi_id' => $validated['lokasi_id'],
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

        // role admin
        $pemesanan = Pemesanan::findOrFail($id);
        $user = Auth::user();

        if ($user->role === 'Admin') {
            // Jika role Admin, ambil semua user dan semua lokasi
            $users = User::all();
            $lokasis = lokasi::all();
        } elseif ($user->role === 'Pangkalan') {
            // Jika role Pangkalan, ambil user dan lokasi berdasarkan user yang login
            $users = User::where('role', 'Pelanggan')->get();
            $lokasis = lokasi::where('user_id', $user->id)->get();
        } else {
            // Role lainnya tidak diperbolehkan mengakses
            abort(403, 'Unauthorized action.');
        }

        return view('pages.pemesanan.edit', compact('pemesanan', 'users', 'lokasis', 'type_menu'));
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'lokasi_id' => 'required|exists:lokasis,id',
            'jumlah' => 'required|integer|min:1',
            'status' => 'required|in:Diterima,Diproses,Ditunda,Selesai',
            'catatan' => 'nullable|string',
            'total_harga' => 'required|numeric|min:0',
        ]);

        $pemesanan = Pemesanan::findOrFail($id);

        // Ambil stok_lpg untuk user & lokasi
        $stok = stok_lpg::where('user_id', $validated['user_id'])
            ->where('lokasi_id', $validated['lokasi_id'])
            ->first();

        if (!$stok) {
            return redirect()->back()->with('error', 'Stok LPG untuk pengguna & lokasi ini tidak ditemukan.');
        }

        $statusLama = $pemesanan->status;
        $jumlahLama = $pemesanan->jumlah;
        $statusBaru = $validated['status'];
        $jumlahBaru = $validated['jumlah'];

        DB::beginTransaction();
        try {
            // Jika status baru adalah "Selesai", stok tidak berubah
            if ($statusBaru !== 'Selesai') {

                if ($statusLama === 'Diterima' && $statusBaru === 'Diterima') {
                    // tetap Diterima, cek selisih jumlah
                    $selisih = $jumlahBaru - $jumlahLama;
                    if ($selisih > 0) {
                        if ($stok->jumlah < $selisih) {
                            DB::rollBack();
                            return redirect()->back()->with('error', 'Stok LPG tidak cukup untuk menambah jumlah pesanan.');
                        }
                        $stok->jumlah -= $selisih;
                    } elseif ($selisih < 0) {
                        $stok->jumlah += abs($selisih);
                    }
                } elseif ($statusLama !== 'Diterima' && $statusBaru === 'Diterima') {
                    // baru menjadi Diterima
                    if ($stok->jumlah < $jumlahBaru) {
                        DB::rollBack();
                        return redirect()->back()->with('error', 'Stok LPG tidak cukup untuk menerima pesanan.');
                    }
                    $stok->jumlah -= $jumlahBaru;
                } elseif ($statusLama === 'Diterima' && !in_array($statusBaru, ['Diterima', 'Selesai'])) {
                    // sebelumnya Diterima → sekarang bukan Diterima & bukan Selesai
                    $stok->jumlah += $jumlahLama;
                }

                $stok->save();
            }

            $pemesanan->update([
                'user_id' => $validated['user_id'],
                'lokasi_id' => $validated['lokasi_id'],
                'jumlah' => $validated['jumlah'],
                'status' => $validated['status'],
                'catatan' => $validated['catatan'],
                'total_harga' => $validated['total_harga'],
            ]);

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

        $pemesanan = pemesanan::findOrFail($id);

        $stok = stok_lpg::where('user_id', $pemesanan->user_id)
            ->where('lokasi_id', $pemesanan->lokasi_id)
            ->first();

        if (!$stok) {
            return back()->with('error', 'Stok LPG untuk pengguna & lokasi ini tidak ditemukan.');
        }

        $statusLama = $pemesanan->status;
        $statusBaru = $validated['status'];
        $jumlah = $pemesanan->jumlah;

        DB::beginTransaction();

        try {
            // Jika sebelumnya Diterima lalu status baru BUKAN Diterima, kembalikan stok
            if ($statusLama === 'Diterima' && $statusBaru !== 'Diterima') {
                $stok->jumlah == $jumlah;
                $stok->save();
            }

            // Jika sebelumnya BUKAN Diterima lalu status baru Diterima, kurangi stok
            if ($statusLama !== 'Diterima' && $statusBaru === 'Diterima') {
                if ($stok->jumlah < $jumlah) {
                    DB::rollBack();
                    return back()->with('error', 'Stok LPG tidak cukup.');
                }
                $stok->jumlah -= $jumlah;
                $stok->save();
            }


            // Update status pemesanan
            $pemesanan->update([
                'status' => $statusBaru,
            ]);

            // Update atau buat status pembayaran
            $pembayaran = $pemesanan->pembayaran;
            if ($pembayaran) {
                $pembayaran->status = $validated['status_pembayaran'];
                $pembayaran->save();
            } else {
                DB::rollBack();
                return back()->with('error', "Pembayaran untuk pemesanan {$pemesanan->no_pemesanan} belum ada.");
            }

            DB::commit();

            return redirect()->back()
                ->with('success', "Status pemesanan {$pemesanan->no_pemesanan} berhasil diperbarui.");
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
        $type_menu = 'order';
        $lokasis = Lokasi::all(); // atau query sesuai kebutuhan
        $lokasi_id = $request->lokasi_id;

        return view('pages.pemesanan.order', compact('type_menu', 'lokasis', 'lokasi_id'));
    }

    public function storeOrder(Request $request)
    {
        $validated = $request->validate([
            'lokasi_id' => 'required|exists:lokasis,id',
            'jumlah' => 'required|integer|min:1',
            'catatan' => 'nullable|string',
            'total_harga' => 'required|numeric|min:0',
        ]);

        // Buat no_pemesanan
        $tanggal = now()->format('Ymd');
        $jumlahHariIni = Pemesanan::whereDate('created_at', now()->toDateString())->count() + 1;
        $no_pemesanan = 'ORD-' . $tanggal . '-' . str_pad($jumlahHariIni, 3, '0', STR_PAD_LEFT);

        $pemesanan = Pemesanan::create([
            'user_id' => Auth::id(),
            'no_pemesanan' => $no_pemesanan,
            'lokasi_id' => $validated['lokasi_id'],
            'jumlah' => $validated['jumlah'],
            'status' => 'Diproses',
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

        return redirect()
            ->route('pembayaran.edit_user', $pembayaran->id)
            ->with('success', 'Pemesanan anda dengan No Order ' . $pemesanan->no_pemesanan . ' berhasil dibuat. Silakan lengkapi pembayaran Anda.');
    }

}