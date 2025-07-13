<?php

namespace App\Http\Controllers;

use App\Models\Lokasi;
use Illuminate\Http\Request;

class TokoController extends Controller
{
    public function index(Request $request)
    {
        $type_menu = 'toko';
        $errorMessage = null;

        $startLat = $request->query('lat');
        $startLng = $request->query('lng');
        $errorCode = $request->query('error');

        if ($errorCode) {
            $errorMessage = match ($errorCode) {
                'PERMISSION_DENIED' => 'Gagal mendeteksi lokasi. Mohon izinkan akses lokasi di browser Anda.',
                'POSITION_UNAVAILABLE' => 'Lokasi tidak tersedia saat ini.',
                'TIMEOUT' => 'Permintaan lokasi habis.',
                'GEOLOCATION_NOT_SUPPORTED' => 'Browser Anda tidak mendukung geolocation.',
                default => 'Terjadi kesalahan saat mendeteksi lokasi.'
            };
        }

        if (!$startLat || !$startLng || $errorMessage) {
            return view('pages.toko.index', [
                'type_menu' => $type_menu,
                'errorMessage' => $errorMessage
            ]);
        }

        $start = 'User';
        $coords[$start] = [(float)$startLat, (float)$startLng];

        // Ambil toko dari DB
        $lokasis = Lokasi::whereNotNull('latitude')->whereNotNull('longitude')->get();

        if ($lokasis->isEmpty()) {
            return view('pages.toko.index', [
                'type_menu' => $type_menu,
                'errorMessage' => 'Tidak ada data toko di database.'
            ]);
        }

        $graph = [
            $start => [] // User → semua toko
        ];

        foreach ($lokasis as $lokasi) {
            $nodeName = 'Toko_' . $lokasi->id;
            $coords[$nodeName] = [(float)$lokasi->latitude, (float)$lokasi->longitude];

            // tambahkan edge dari user ke setiap toko (karena tidak ada node jalan)
            $graph[$start][$nodeName] = $this->haversine(
                $coords[$start][0],
                $coords[$start][1],
                $coords[$nodeName][0],
                $coords[$nodeName][1]
            );

            // node toko tanpa neighbor
            $graph[$nodeName] = [];
        }

        // tentukan goal → toko dengan f terkecil
        $best = null;
        $minCost = INF;
        $bestPath = null;

        foreach ($lokasis as $lokasi) {
            $goal = 'Toko_' . $lokasi->id;

            $result = $this->aStar($graph, $coords, $start, $goal);

            if ($result && $result['cost'] < $minCost) {
                $minCost = $result['cost'];
                $best = $lokasi;
                $bestPath = $result['path'];
            }
        }

        if (!$best) {
            $errorMessage = 'Tidak ditemukan rute ke toko.';
        }

        return view('pages.toko.index', [
            'type_menu' => $type_menu,
            'path' => $bestPath ?? null,
            'cost' => $minCost !== INF ? $minCost : null,
            'errorMessage' => $errorMessage,
            'nearest' => $best
        ]);
    }

    public function aStar($graph, $coords, $start, $goal)
    {
        $open = [];
        $closed = [];
        $g = [];
        $f = [];
        $cameFrom = [];

        $open[] = $start;
        $g[$start] = 0;
        $f[$start] = $this->heuristic($coords[$start], $coords[$goal]);

        while (!empty($open)) {
            usort($open, fn($a, $b) => $f[$a] <=> $f[$b]);
            $current = array_shift($open);

            if ($current === $goal) {
                $path = [$goal];
                while (isset($cameFrom[$current])) {
                    $current = $cameFrom[$current];
                    array_unshift($path, $current);
                }
                return [
                    'path' => $path,
                    'cost' => $g[$goal]
                ];
            }

            $closed[] = $current;

            foreach ($graph[$current] as $neighbor => $cost) {
                if (in_array($neighbor, $closed)) continue;

                $tentative_g = $g[$current] + $cost;

                if (!in_array($neighbor, $open)) {
                    $open[] = $neighbor;
                } elseif ($tentative_g >= ($g[$neighbor] ?? INF)) {
                    continue;
                }

                $cameFrom[$neighbor] = $current;
                $g[$neighbor] = $tentative_g;
                $f[$neighbor] = $g[$neighbor] + $this->heuristic($coords[$neighbor], $coords[$goal]);
            }
        }

        return null;
    }

    private function heuristic($a, $b)
    {
        $lat1 = $a[0];
        $lon1 = $a[1];
        $lat2 = $b[0];
        $lon2 = $b[1];

        $earthRadius = 6371;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $s = sin($dLat / 2) ** 2 +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($dLon / 2) ** 2;

        $c = 2 * atan2(sqrt($s), sqrt(1 - $s));

        return $earthRadius * $c;
    }

    private function haversine($lat1, $lon1, $lat2, $lon2)
    {
        return $this->heuristic([$lat1, $lon1], [$lat2, $lon2]);
    }
}
