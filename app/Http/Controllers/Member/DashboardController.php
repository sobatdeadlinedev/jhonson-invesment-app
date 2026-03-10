<?php

namespace App\Http\Controllers\Member;

use App\Models\User;
use App\Models\Config;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $user = User::current();
        $announcement = Config::get('app_announcement')['value'] ?? null;
        $userBalance = Transaction::getUserBalance(auth()->id());

        // Generate referral link
        $referralLink = route('register', ['ref' => $user->refferal_code]);

        // Get real-time crypto prices from Binance API
        $cryptoPrices = $this->getCryptoPrices();

        return view('member.pages.dashboard.index', compact('user', 'announcement', 'userBalance', 'referralLink', 'cryptoPrices'));
    }

    private function getCryptoPrices()
    {
        try {
            // Call Binance API untuk ambil harga
            $response = Http::timeout(5)->get('https://api.binance.us/api/v3/ticker/24hr', [
                'symbols' => json_encode(['BTCUSDT', 'ETHUSDT', 'DOGEUSDT', 'BNBUSDT', 'SOLUSDT'])
            ]);

            if ($response->successful()) {
                $data = $response->json();

                $prices = [];
                foreach ($data as $item) {
                    $prices[$item['symbol']] = [
                        'price' => number_format($item['lastPrice'], 2),
                        'change' => number_format($item['priceChangePercent'], 2),
                        'isPositive' => $item['priceChangePercent'] >= 0
                    ];
                }

                return $prices;
            }
        } catch (\Exception $e) {
            Log::error('Failed to fetch crypto prices: ' . $e->getMessage());
        }

        // Fallback data jika API gagal
        return [
            'BTCUSDT' => ['price' => '43,250.50', 'change' => '+2.45', 'isPositive' => true],
            'ETHUSDT' => ['price' => '2,320.75', 'change' => '+1.83', 'isPositive' => true],
            'DOGEUSDT' => ['price' => '0.08', 'change' => '-0.52', 'isPositive' => false],
            'BNBUSDT' => ['price' => '315.40', 'change' => '+3.12', 'isPositive' => true],
            'SOLUSDT' => ['price' => '98.65', 'change' => '+5.27', 'isPositive' => true],
        ];
    }
}
