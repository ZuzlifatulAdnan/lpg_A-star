<?php

namespace App\Http\Controllers;

use App\Models\pembayaran;
use App\Models\pemesanan;
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
        $pemesanan_list = pemesanan::with('user')->get();

        return view('pages.pembayaran.create', compact('type_menu', 'pemesanan_list'));
    }

    public function store(Request $request)
    {
        // Validasi data dari form
        $validatedData = $request->validate([
            'pemesanan_id' => 'required|exists:pemesanan,id',
            'metode_pembayaran' => 'required',
            'status' => 'required',
            'jumlah_dibayar' => 'required|numeric',
            'bukti_bayar' => 'required|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        // Upload bukti bayar
        $imagePath = null;
        if ($request->hasFile('bukti_bayar')) {
            $image = $request->file('bukti_bayar');
            $imagePath = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img/bukti_bayar'), $imagePath);
        }

        // Generate No Pembayaran
        $tanggal = now()->format('Ymd');
        $jumlahPembayaranHariIni = Pembayaran::whereDate('created_at', now()->toDateString())->count() + 1;
        $no_pembayaran = 'PAY-' . $tanggal . '-' . str_pad($jumlahPembayaranHariIni, 3, '0', STR_PAD_LEFT);

        // Simpan data pembayaran
        $pembayaran = Pembayaran::create([
            'no_pembayaran' => $no_pembayaran,
            'pemesanan_id' => $validatedData['pemesanan_id'],
            'metode_pembayaran' => $validatedData['metode_pembayaran'],
            'status' => $validatedData['status'],
            'jumlah_dibayar' => $validatedData['jumlah_dibayar'],
            'bukti_bayar' => $imagePath
        ]);

        return redirect()->route('pembayaran.index')
            ->with('success', 'Pembayaran dengan No Pembayaran ' . $pembayaran->no_pembayaran . ' berhasil ditambahkan.');
    }


    public function edit($id)
    {
        $type_menu = 'pembayaran';
        $pembayaran = Pembayaran::findOrFail($id);
        $pemesanan_list = pemesanan::with('user')->get();

        return view('pages.pembayaran.edit', compact('type_menu', 'pembayaran', 'pemesanan_list'));
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'pemesanan_id' => 'required',
            'metode_pembayaran' => 'required',
            'jumlah_dibayar' => 'required|numeric',
            'status' => 'required',
            'bukti_bayar' => 'nullable|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $pembayaran = Pembayaran::findOrFail($id);

        if ($request->hasFile('bukti_bayar')) {
            // hapus bukti lama kalau ada
            if ($pembayaran->bukti_bayar && file_exists(public_path('img/bukti_bayar/' . $pembayaran->bukti_bayar))) {
                unlink(public_path('img/bukti_bayar/' . $pembayaran->bukti_bayar));
            }
            $image = $request->file('bukti_bayar');
            $imagePath = uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('img/bukti_bayar/'), $imagePath);
            $pembayaran->bukti_bayar = $imagePath;
        }

        $pembayaran->update([
            'pemesanan_id' => $validatedData['pemesanan_id'],
            'metode_pembayaran' => $validatedData['metode_pembayaran'],
            'jumlah_dibayar' => $validatedData['jumlah_dibayar'],
            'status' => $validatedData['status'],
        ]);

        return redirect()->route('pembayaran.index')->with('success', 'Pembayaran berhasil diperbarui.');
    }

    public function show($id)
    {
        $type_menu = 'pembayaran';
        $pembayaran = Pembayaran::with('pemesanan.user')->findOrFail($id);

        return view('pages.pembayaran.show', compact('type_menu', 'pembayaran'));
    }

    public function destroy(pembayaran $pembayaran)
    {
        // Hapus bukti bayar jika ada
        if ($pembayaran->bukti_bayar && file_exists(public_path('img/bukti_bayar/' . $pembayaran->bukti_bayar))) {
            unlink(public_path('img/bukti_bayar/' . $pembayaran->bukti_bayar));
        }
        // Hapus data pembayaran
        $pembayaran->delete();
        return redirect()->route('pembayaran.index')->with('success', 'Pembayaran dengan No Pembayaran ' . $pembayaran->no_pembayaran . ' berhasil dihapus.');
    }
    // Menampilkan pembayaran yang sedang diproses
    public function editUser($id)
    {
        $type_menu = 'riwayat';
        $pembayaran = Pembayaran::findOrFail($id);

        $pemesanan = Pemesanan::with('user')
            ->findOrFail($pembayaran->pemesanan_id);

        return view('pages.pembayaran.edit-user', compact('type_menu', 'pembayaran', 'pemesanan'));
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'metode_pembayaran' => 'required',
            'bukti_bayar' => 'nullable|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        $pembayaran = Pembayaran::findOrFail($id);

        $data = [
            'metode_pembayaran' => $request->metode_pembayaran,
            'status' => 'Proses Pembayaran',
        ];

        if ($request->hasFile('bukti_bayar')) {
            $file = $request->file('bukti_bayar');
            $filename = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('img/bukti_bayar/'), $filename);

            if ($pembayaran->bukti_bayar && file_exists(public_path('img/bukti_bayar/' . $pembayaran->bukti_bayar))) {
                unlink(public_path('img/bukti_bayar/' . $pembayaran->bukti_bayar));
            }

            $data['bukti_bayar'] = $filename;
        }

        $pembayaran->update($data);

        return redirect()->route('riwayat.index')->with('success', 'Pembayaran berhasil diperbarui.');
    }
}
