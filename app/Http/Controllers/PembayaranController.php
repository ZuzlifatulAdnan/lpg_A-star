<?php

namespace App\Http\Controllers;

use App\Models\pembayaran;
use Illuminate\Http\Request;

class PembayaranController extends Controller
{
    public function index(Request $request)
    {
        $type_menu = 'pembayaran';

        $keyword = trim($request->input('search'));
        $status = $request->input('status');
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        $pembayarans = pembayaran::with(['pemesanan.user'])
            ->when($keyword, function ($query, $keyword) {
                $query->where(function ($q) use ($keyword) {
                    $q->whereHas('pemesanan.user', function ($q2) use ($keyword) {
                        $q2->where('name', 'like', '%' . $keyword . '%');
                    })->orWhere('no_pembayaran', 'like', '%' . $keyword . '%');
                });
            })
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($bulan, function ($query, $bulan) {
                $query->whereHas('pembayaran', function ($q) use ($bulan) {
                    $q->whereMonth('tanggal_bayar', $bulan);
                });
            })
            ->when($tahun, function ($query, $tahun) {
                $query->whereHas('pembayaran', function ($q) use ($tahun) {
                    $q->whereYear('tanggal_bayar', $tahun);
                });
            })
            ->latest()
            ->paginate(10);

        $pembayarans->appends($request->all());

        return view('pages.pembayaran.index', [
            'type_menu' => $type_menu,
            'pembayaran' => $pembayarans
        ]);
    }
    public function create()
    {
        $type_menu = 'pembayaran';
        $pembayarans = pembayaran::all();
        return view('pages.pembayaran.create', compact('type_menu', 'pembayarans'));
    }

    public function store(Request $request)
    {
        // validasi data dari form tambah user
        $validatedData = $request->validate([
            'pemesanan_id' => 'required',
            'metode_pembayaran' => 'required',
            'jumlah_dibayar' => 'required',
            'bukti_bayar' => 'required|mimes:jpg,jpeg,png,gif',
        ]);
        // Handle the image upload if present
        $imagePath = null;
        if ($request->hasFile('bukti_bayar')) {
            $image = $request->file('bukti_bayar');
            $imagePath = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move('img/bukti_bayar/', $imagePath);
        }
        // Buat no_pembayaran
        $tanggal = date('Ymd');
        $jumlahPembayaranHariIni = Pembayaran::whereDate('created_at', now()->toDateString())->count() + 1;
        $no_pembayaran = 'PAY-' . $tanggal . '-' . str_pad($jumlahPembayaranHariIni, 3, '0', STR_PAD_LEFT);

        //masukan data kedalam tabel users
        $pembayaran = pembayaran::create([
            'no_pembayaran' => $no_pembayaran,
            'pemesanan_id' => $validatedData['pemesanan_id'],
            'metode_pembayaran' => $validatedData['metode_pembayaran'],
            'jumlah_dibayar' => $validatedData['jumlah_dibayar'],
            'bukti_bayar' => $imagePath,
            'status' => 'Proses Pembayaran'
        ]);
        return redirect()->route('pembayaran.index')->with('success', 'Pembayaran dengan No Pembayaran '.$pembayaran->no_pembayaran .' berhasil ditambahkan.');
    }

    public function edit(pembayaran $pembayaran)
    {
        $type_menu = 'pembayaran';
        $pembayarans = pembayaran::all();
        return view('pages.pembayaran.edit', [
            'type_menu' => $type_menu,
            'pembayaran' => $pembayaran,
            'pembayarans' => $pembayarans,
        ]);
    }

    public function update(Request $request, pembayaran $pembayaran)
    {
        // Validate the form data
        $request->validate([
            'pemesanan_id' => 'required',
            'metode_pembayaran' => 'required',
            'jumlah_dibayar' => 'required',
            'bukti_bayar' => 'nullable|mimes:jpg,jpeg,png,gif',
        ]);

        // Update the user data
        $pembayaran->update([
            'pemesanan_id' => $request->pemesanan_id,
            'metode_pembayaran' => $request->metode_pembayaran,
            'jumlah_dibayar' => $request->jumlah_dibayar,
            'status' => $request->status,
        ]);

        if ($request->hasFile('bukti_bayar')) {
            $image = $request->file('bukti_bayar');
            $path = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move('img/bukti_bayar/', $path);
            $pembayaran->update([
                'bukti_bayar' => $path
            ]);
        }

        // Redirect kembali ke halaman index dengan pesan sukses
        return redirect()->route('pembayaran.index')->with('success', 'Pembayaran dengan No Pembayaran '. $pembayaran->no_pembayaran .' berhasil diperbarui.');
    }


    public function show(pembayaran $pembayaran)
    {
        $type_menu = 'pembayaran';
        return view('pages.pembayaran.show', compact('pembayaran', 'type_menu'));
    }
    public function destroy(pembayaran $pembayaran)
    {
        // Hapus bukti bayar jika ada
        if ($pembayaran->bukti_bayar && file_exists(public_path('img/bukti_bayar/' . $pembayaran->bukti_bayar))) {
            unlink(public_path('img/bukti_bayar/' . $pembayaran->bukti_bayar));
        }
        // Hapus data pembayaran
        $pembayaran->delete();
        return redirect()->route('pembayaran.index')->with('success', 'Pembayaran dengan No Pembayaran '.$pembayaran->no_pembayaran.' berhasil dihapus.');
    }
    // Menampilkan pembayaran yang sedang diproses
    public function showProses()
    {
        $type_menu = 'pembayaran';
        $pembayarans = pembayaran::where('status', 'Proses Pembayaran')
            ->latest()
            ->paginate(10);

        return view('pages.pembayaran.proses', compact('pembayarans', 'type_menu'));
    }

    // Mengubah status pembayaran
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:Menunggu Pembayaran,Proses Pembayaran,Pembayaran Berhasil',
        ]);

        $pembayaran = pembayaran::find($id);
        if (!$pembayaran) {
            return redirect()->back()->with('error', 'Data pembayaran tidak ditemukan.');
        }

        $pembayaran->status = $request->status;
        $pembayaran->save();

        // Opsional: Update status pembayaran jika dibutuhkan
        if ($request->status === 'Pembayaran Berhasil' && $pembayaran->pembayaran) {
            $pembayaran->pembayaran->status = 'Selesai';
            $pembayaran->pembayaran->save();
        }

        return redirect()->back()->with('success', 'Status pembayaran dengan No Pembayaran '. $pembayaran->no_pembayaran.' berhasil diperbarui.');
    }
}
