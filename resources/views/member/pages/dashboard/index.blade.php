@extends('member.layouts.app')
@section('content')
    <div class="scrollable-content">
        <div class="content-section">

            {{-- ── TOP BAR ────────────────────────────────────── --}}
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    @php
                        $h = now()->hour;
                        $greet = $h < 12 ? 'Good morning' : ($h < 17 ? 'Good afternoon' : 'Good evening');
                    @endphp
                    <div style="font-size:11px;color:#3a4d66;letter-spacing:.6px;margin-bottom:3px;">{{ $greet }} 👋</div>
                    <h5 class="mb-0 fw-bold" style="letter-spacing:-.4px;color:#e2eaf8;">
                        Halo, <span style="color:#00d48a;">{{ $user->username }}</span>
                    </h5>
                </div>
                <div style="width:38px;height:38px;border-radius:11px;background:#0d1120;border:1px solid #1a2235;display:flex;align-items:center;justify-content:center;font-size:15px;color:#7a8fad;position:relative;flex-shrink:0;">
                    <i class="bi bi-bell"></i>
                    <span style="position:absolute;top:7px;right:8px;width:6px;height:6px;border-radius:50%;background:#f5a623;border:1.5px solid #080b12;"></span>
                </div>
            </div>

            {{-- ── BALANCE CARD ────────────────────────────────── --}}
            <div class="mb-3" style="background:linear-gradient(135deg,#0f1c2e 0%,#0d1420 60%,#0a1118 100%);border:1px solid #1a2235;border-radius:20px;padding:20px;position:relative;overflow:hidden;">
                {{-- grid overlay --}}
                <div style="position:absolute;inset:0;pointer-events:none;background-image:linear-gradient(rgba(0,212,138,.03) 1px,transparent 1px),linear-gradient(90deg,rgba(0,212,138,.03) 1px,transparent 1px);background-size:28px 28px;"></div>
                {{-- glow sudut kanan atas --}}
                <div style="position:absolute;top:-30px;right:-30px;width:160px;height:160px;border-radius:50%;background:radial-gradient(circle,rgba(0,212,138,.10) 0%,transparent 65%);pointer-events:none;"></div>

                {{-- Baris 1: Penilaian Aset (kiri) | USD (kanan) --}}
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:6px;">
                    <div style="display:flex;align-items:center;gap:5px;">
                        <span style="font-size:12px;color:#7a8fad;letter-spacing:.3px;">Penilaian Aset</span>
                        <i class="bi bi-eye" style="font-size:11px;color:#3a4d66;cursor:pointer;"></i>
                    </div>
                    <span style="font-size:12px;color:#7a8fad;letter-spacing:.5px;">USDT</span>
                </div>

                {{-- Angka besar --}}
                <div style="font-family:monospace;font-size:36px;font-weight:700;color:#f5a623;letter-spacing:-1px;line-height:1;margin-bottom:4px;">
                    {{ number_format($userBalance, 2) }}
                </div>

                {{-- Baris 2: ≈$ (kiri) | Penghasilan hari ini (kanan) --}}
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;">
                    <span style="font-size:12px;color:#3a4d66;">≈${{ number_format($userBalance, 2) }}</span>
                    <span style="font-size:12px;color:#3a4d66;">Penghasilan hari ini: <span style="color:#f5a623;font-family:monospace;">0.00</span></span>
                </div>

                {{-- Sub balances (tidak diubah) --}}
                <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;">
                    <div style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);border-radius:10px;padding:10px 10px 8px;">
                        <div style="font-size:9px;color:#3a4d66;letter-spacing:.5px;text-transform:uppercase;margin-bottom:4px;">Trade</div>
                        <div style="font-family:monospace;font-size:13px;font-weight:700;color:#e2eaf8;">$&nbsp;{{ number_format(auth()->user()->trade_balance, 2) }}</div>
                    </div>
                    <div style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);border-radius:10px;padding:10px 10px 8px;">
                        <div style="font-size:9px;color:#3a4d66;letter-spacing:.5px;text-transform:uppercase;margin-bottom:4px;">Available</div>
                        <div style="font-family:monospace;font-size:13px;font-weight:700;color:#00d48a;">$&nbsp;{{ number_format(auth()->user()->getAvailableTradeBalance(), 2) }}</div>
                    </div>
                    <div style="background:rgba(255,255,255,.04);border:1px solid rgba(255,255,255,.06);border-radius:10px;padding:10px 10px 8px;">
                        <div style="font-size:9px;color:#3a4d66;letter-spacing:.5px;text-transform:uppercase;margin-bottom:4px;">Locked</div>
                        <div style="font-family:monospace;font-size:13px;font-weight:700;color:#f5a623;">$&nbsp;{{ number_format(auth()->user()->locked_balance, 2) }}</div>
                    </div>
                </div>
            </div>

            {{-- ── ANNOUNCEMENT ─────────────────────────────────── --}}
            @if ($announcement && !empty($announcement))
                <div class="mb-3" style="background:#0d1120;border:1px solid #1a2235;border-left:3px solid #f5a623;border-radius:14px;padding:12px 14px;display:flex;align-items:flex-start;gap:10px;">
                    <i class="bi bi-megaphone-fill" style="font-size:14px;color:#f5a623;flex-shrink:0;margin-top:1px;"></i>
                    <div>
                        <div style="font-size:10px;font-weight:700;color:#f5a623;letter-spacing:.8px;text-transform:uppercase;margin-bottom:3px;">Pengumuman</div>
                        <div style="font-size:12px;color:#7a8fad;line-height:1.55;">{{ $announcement }}</div>
                    </div>
                </div>
            @endif

            {{-- ── MARKET CARD ──────────────────────────────────── --}}
            <div class="mb-3" style="background:#0d1120;border:1px solid #1a2235;border-radius:20px;overflow:hidden;">

                {{-- header --}}
                <div style="display:flex;align-items:center;justify-content:space-between;padding:14px 16px 13px;border-bottom:1px solid #1a2235;">
                    <span style="font-size:13px;font-weight:700;color:#e2eaf8;">Market Overview</span>
                    <span style="display:inline-flex;align-items:center;gap:5px;background:rgba(0,212,138,.07);border:1px solid rgba(0,212,138,.18);border-radius:99px;padding:3px 10px;font-size:10px;font-weight:700;color:#00d48a;letter-spacing:.6px;">
                        <span style="width:5px;height:5px;border-radius:50%;background:#00d48a;display:inline-block;flex-shrink:0;animation:xi-blink 1.6s infinite;"></span>
                        LIVE
                    </span>
                </div>

                {{-- BTC --}}
                <div class="xi-row">
                    <div style="display:flex;align-items:center;gap:12px;flex:1;min-width:0;">
                        <div class="xi-av" style="background:rgba(247,147,26,.12);color:#f7931a;">₿</div>
                        <div>
                            <div class="xi-pair">BTC/USDT</div>
                            <div class="xi-coin-name">Bitcoin</div>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-shrink:0;">
                        <div class="xi-spark {{ $cryptoPrices['BTCUSDT']['isPositive'] ? 'sp-up' : 'sp-dn' }}">
                            @foreach([8,13,10,18,15,22,20] as $bh)<div class="xi-sb" style="height:{{ $bh }}px"></div>@endforeach
                        </div>
                        <div style="text-align:right;">
                            <div class="xi-price">${{ $cryptoPrices['BTCUSDT']['price'] ?? '0.00' }}</div>
                            <span class="xi-pill {{ $cryptoPrices['BTCUSDT']['isPositive'] ? 'up' : 'dn' }}">
                                {{ $cryptoPrices['BTCUSDT']['isPositive'] ? '▲' : '▼' }}&nbsp;{{ $cryptoPrices['BTCUSDT']['change'] ?? '0.00' }}%
                            </span>
                        </div>
                    </div>
                </div>

                {{-- ETH --}}
                <div class="xi-row">
                    <div style="display:flex;align-items:center;gap:12px;flex:1;min-width:0;">
                        <div class="xi-av" style="background:rgba(98,126,234,.12);color:#627eea;">Ξ</div>
                        <div>
                            <div class="xi-pair">ETH/USDT</div>
                            <div class="xi-coin-name">Ethereum</div>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-shrink:0;">
                        <div class="xi-spark {{ $cryptoPrices['ETHUSDT']['isPositive'] ? 'sp-up' : 'sp-dn' }}">
                            @foreach([22,18,20,14,11,8,5] as $bh)<div class="xi-sb" style="height:{{ $bh }}px"></div>@endforeach
                        </div>
                        <div style="text-align:right;">
                            <div class="xi-price">${{ $cryptoPrices['ETHUSDT']['price'] ?? '0.00' }}</div>
                            <span class="xi-pill {{ $cryptoPrices['ETHUSDT']['isPositive'] ? 'up' : 'dn' }}">
                                {{ $cryptoPrices['ETHUSDT']['isPositive'] ? '▲' : '▼' }}&nbsp;{{ $cryptoPrices['ETHUSDT']['change'] ?? '0.00' }}%
                            </span>
                        </div>
                    </div>
                </div>

                {{-- DOGE --}}
                <div class="xi-row">
                    <div style="display:flex;align-items:center;gap:12px;flex:1;min-width:0;">
                        <div class="xi-av" style="background:rgba(195,167,78,.12);color:#c3a74e;">Ð</div>
                        <div>
                            <div class="xi-pair">DOGE/USDT</div>
                            <div class="xi-coin-name">Dogecoin</div>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-shrink:0;">
                        <div class="xi-spark {{ $cryptoPrices['DOGEUSDT']['isPositive'] ? 'sp-up' : 'sp-dn' }}">
                            @foreach([5,9,13,10,17,20,24] as $bh)<div class="xi-sb" style="height:{{ $bh }}px"></div>@endforeach
                        </div>
                        <div style="text-align:right;">
                            <div class="xi-price">${{ $cryptoPrices['DOGEUSDT']['price'] ?? '0.00' }}</div>
                            <span class="xi-pill {{ $cryptoPrices['DOGEUSDT']['isPositive'] ? 'up' : 'dn' }}">
                                {{ $cryptoPrices['DOGEUSDT']['isPositive'] ? '▲' : '▼' }}&nbsp;{{ $cryptoPrices['DOGEUSDT']['change'] ?? '0.00' }}%
                            </span>
                        </div>
                    </div>
                </div>

                {{-- BNB --}}
                <div class="xi-row">
                    <div style="display:flex;align-items:center;gap:12px;flex:1;min-width:0;">
                        <div class="xi-av" style="background:rgba(243,186,47,.12);color:#f3ba2f;">◆</div>
                        <div>
                            <div class="xi-pair">BNB/USDT</div>
                            <div class="xi-coin-name">Binance Coin</div>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-shrink:0;">
                        <div class="xi-spark {{ $cryptoPrices['BNBUSDT']['isPositive'] ? 'sp-up' : 'sp-dn' }}">
                            @foreach([20,16,18,12,10,8,5] as $bh)<div class="xi-sb" style="height:{{ $bh }}px"></div>@endforeach
                        </div>
                        <div style="text-align:right;">
                            <div class="xi-price">${{ $cryptoPrices['BNBUSDT']['price'] ?? '0.00' }}</div>
                            <span class="xi-pill {{ $cryptoPrices['BNBUSDT']['isPositive'] ? 'up' : 'dn' }}">
                                {{ $cryptoPrices['BNBUSDT']['isPositive'] ? '▲' : '▼' }}&nbsp;{{ $cryptoPrices['BNBUSDT']['change'] ?? '0.00' }}%
                            </span>
                        </div>
                    </div>
                </div>

                {{-- SOL --}}
                <div class="xi-row" style="border-bottom:none;">
                    <div style="display:flex;align-items:center;gap:12px;flex:1;min-width:0;">
                        <div class="xi-av" style="background:rgba(153,69,255,.12);color:#9945ff;">◎</div>
                        <div>
                            <div class="xi-pair">SOL/USDT</div>
                            <div class="xi-coin-name">Solana</div>
                        </div>
                    </div>
                    <div style="display:flex;align-items:center;gap:10px;flex-shrink:0;">
                        <div class="xi-spark {{ $cryptoPrices['SOLUSDT']['isPositive'] ? 'sp-up' : 'sp-dn' }}">
                            @foreach([6,10,8,15,19,17,23] as $bh)<div class="xi-sb" style="height:{{ $bh }}px"></div>@endforeach
                        </div>
                        <div style="text-align:right;">
                            <div class="xi-price">${{ $cryptoPrices['SOLUSDT']['price'] ?? '0.00' }}</div>
                            <span class="xi-pill {{ $cryptoPrices['SOLUSDT']['isPositive'] ? 'up' : 'dn' }}">
                                {{ $cryptoPrices['SOLUSDT']['isPositive'] ? '▲' : '▼' }}&nbsp;{{ $cryptoPrices['SOLUSDT']['change'] ?? '0.00' }}%
                            </span>
                        </div>
                    </div>
                </div>

            </div>{{-- end market card --}}

        </div>
    </div>

    <style>
        @keyframes xi-blink   { 0%,100%{opacity:1} 50%{opacity:.15} }
        @keyframes xi-pulse-g { 0%,100%{opacity:1;box-shadow:0 0 0 0 rgba(0,212,138,.4)} 50%{opacity:.7;box-shadow:0 0 0 5px rgba(0,212,138,0)} }

        /* ── coin row — completely self-contained, no theme dependency ── */
        .xi-row {
            display: flex !important;
            align-items: center !important;
            justify-content: space-between !important;
            padding: 13px 16px !important;
            border-bottom: 1px solid rgba(26,34,53,.9) !important;
            transition: background .15s;
            cursor: pointer;
        }
        .xi-row:hover { background: rgba(255,255,255,.02) !important; }

        .xi-av {
            width: 38px; height: 38px;
            border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            font-size: 18px; font-weight: 700;
            flex-shrink: 0;
        }

        .xi-pair      { font-size: 13px; font-weight: 700; color: #e2eaf8; margin-bottom: 2px; }
        .xi-coin-name { font-size: 10px; color: #3a4d66; }

        /* sparkline */
        .xi-spark {
            display: flex;
            align-items: flex-end;
            gap: 2px;
            height: 26px;
            width: 42px;
            flex-shrink: 0;
        }
        .xi-sb {
            width: 4px;
            border-radius: 2px 2px 0 0;
            flex-shrink: 0;
            opacity: .6;
        }
        .sp-up .xi-sb { background: #00d48a; }
        .sp-dn .xi-sb { background: #f04f5a; }
        .xi-row:hover .xi-sb { opacity: .9; }

        /* price */
        .xi-price {
            font-family: monospace;
            font-size: 13px; font-weight: 600;
            color: #e2eaf8;
            margin-bottom: 4px;
            white-space: nowrap;
        }

        /* change pill */
        .xi-pill {
            display: inline-flex; align-items: center; gap: 2px;
            font-family: monospace;
            font-size: 11px; font-weight: 700;
            padding: 2px 7px; border-radius: 5px;
            white-space: nowrap;
        }
        .xi-pill.up { background: rgba(0,212,138,.1);  color: #00d48a; }
        .xi-pill.dn { background: rgba(240,79,90,.1);  color: #f04f5a; }
    </style>

    <script>
        function showToast(message, type = 'success') {
            const bgColor = type === 'success' ? '#28a745' : '#dc3545';
            const toast = document.createElement('div');
            toast.style.cssText = `position:fixed;top:20px;right:20px;background:${bgColor};color:white;padding:12px 20px;border-radius:8px;z-index:9999;font-size:14px;box-shadow:0 4px 12px rgba(0,0,0,0.3);`;
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 2000);
        }
        function copyReferralCode() {
            const code = document.getElementById('referralCode').textContent;
            const icon = document.getElementById('copyCodeIcon');
            navigator.clipboard.writeText(code).then(() => {
                icon.classList.replace('bi-clipboard','bi-check-lg');
                showToast('Kode referral berhasil disalin!');
                setTimeout(() => icon.classList.replace('bi-check-lg','bi-clipboard'), 2000);
            }).catch(() => showToast('Gagal menyalin kode referral','error'));
        }
        function copyReferralLink() {
            const link = document.getElementById('referralLink').textContent;
            const icon = document.getElementById('copyLinkIcon');
            navigator.clipboard.writeText(link).then(() => {
                icon.classList.replace('bi-link-45deg','bi-check-lg');
                showToast('Link referral berhasil disalin!');
                setTimeout(() => icon.classList.replace('bi-check-lg','bi-link-45deg'), 2000);
            }).catch(() => showToast('Gagal menyalin link referral','error'));
        }
    </script>
@endsection