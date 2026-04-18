@extends('member.layouts.app')
@section('content')
    <div class="scrollable-content">
        <div class="content-section">

            {{-- ── STATS CARDS ─────────────────────────────────── --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-bottom:12px;">

                {{-- Total Network --}}
                <div style="background:linear-gradient(135deg,#0f1c2e 0%,#0d1420 100%);border:1px solid #1a2235;border-radius:16px;padding:16px;position:relative;overflow:hidden;">
                    <div style="position:absolute;top:-20px;right:-20px;width:80px;height:80px;border-radius:50%;background:radial-gradient(circle,rgba(0,212,138,.12) 0%,transparent 65%);pointer-events:none;"></div>
                    <div style="width:34px;height:34px;border-radius:10px;background:rgba(0,212,138,.10);border:1px solid rgba(0,212,138,.18);display:flex;align-items:center;justify-content:center;margin-bottom:10px;">
                        <i class="bi bi-people-fill" style="font-size:15px;color:#00d48a;"></i>
                    </div>
                    <div style="font-size:10px;color:#3a4d66;letter-spacing:.6px;text-transform:uppercase;margin-bottom:4px;">Total Network</div>
                    <div style="font-family:monospace;font-size:26px;font-weight:700;color:#f5a623;letter-spacing:-1px;line-height:1;">{{ $totalTeam }}</div>
                </div>

                {{-- Direct Team --}}
                <div style="background:linear-gradient(135deg,#0f1c2e 0%,#0d1420 100%);border:1px solid #1a2235;border-radius:16px;padding:16px;position:relative;overflow:hidden;">
                    <div style="position:absolute;top:-20px;right:-20px;width:80px;height:80px;border-radius:50%;background:radial-gradient(circle,rgba(100,160,255,.10) 0%,transparent 65%);pointer-events:none;"></div>
                    <div style="width:34px;height:34px;border-radius:10px;background:rgba(100,160,255,.10);border:1px solid rgba(100,160,255,.18);display:flex;align-items:center;justify-content:center;margin-bottom:10px;">
                        <i class="bi bi-person-plus-fill" style="font-size:15px;color:#64a0ff;"></i>
                    </div>
                    <div style="font-size:10px;color:#3a4d66;letter-spacing:.6px;text-transform:uppercase;margin-bottom:4px;">Direct Team</div>
                    <div style="font-family:monospace;font-size:26px;font-weight:700;color:#64a0ff;letter-spacing:-1px;line-height:1;">{{ $directTeam }}</div>
                </div>

            </div>

            {{-- ── MANUAL LEVEL BADGE ───────────────────────────── --}}
            @if (!is_null($manualLevel))
                @php
                    $lvlColors = [
                        1  => ['bg' => 'rgba(0,212,138,.12)',   'border' => 'rgba(0,212,138,.25)',   'text' => '#00d48a'],
                        2  => ['bg' => 'rgba(100,160,255,.12)', 'border' => 'rgba(100,160,255,.25)', 'text' => '#64a0ff'],
                        3  => ['bg' => 'rgba(245,166,35,.12)',  'border' => 'rgba(245,166,35,.25)',  'text' => '#f5a623'],
                        4  => ['bg' => 'rgba(153,69,255,.12)',  'border' => 'rgba(153,69,255,.25)',  'text' => '#9945ff'],
                        5  => ['bg' => 'rgba(240,79,90,.12)',   'border' => 'rgba(240,79,90,.25)',   'text' => '#f04f5a'],
                        6  => ['bg' => 'rgba(20,184,166,.12)',  'border' => 'rgba(20,184,166,.25)',  'text' => '#14b8a6'],
                        7  => ['bg' => 'rgba(251,146,60,.12)',  'border' => 'rgba(251,146,60,.25)',  'text' => '#fb923c'],
                        8  => ['bg' => 'rgba(236,72,153,.12)',  'border' => 'rgba(236,72,153,.25)',  'text' => '#ec4899'],
                        9  => ['bg' => 'rgba(99,102,241,.12)',  'border' => 'rgba(99,102,241,.25)',  'text' => '#6366f1'],
                        10 => ['bg' => 'rgba(234,179,8,.12)',   'border' => 'rgba(234,179,8,.25)',   'text' => '#eab308'],
                    ];
                    $c = $lvlColors[$manualLevel] ?? ['bg' => 'rgba(122,143,173,.10)', 'border' => 'rgba(122,143,173,.20)', 'text' => '#7a8fad'];
                @endphp
                <div class="mb-3" style="background:linear-gradient(135deg,#0f1c2e 0%,#0d1420 100%);border:1px solid {{ $c['border'] }};border-radius:16px;padding:14px 16px;display:flex;align-items:center;gap:12px;">
                    <div style="width:40px;height:40px;border-radius:12px;background:{{ $c['bg'] }};border:1px solid {{ $c['border'] }};display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-award-fill" style="font-size:18px;color:{{ $c['text'] }};"></i>
                    </div>
                    <div style="flex:1;">
                        <div style="font-size:10px;color:#3a4d66;letter-spacing:.6px;text-transform:uppercase;margin-bottom:3px;">Level Keanggotaan</div>
                        <div style="font-family:monospace;font-size:20px;font-weight:700;color:{{ $c['text'] }};">Level {{ $manualLevel }}</div>
                       
                    </div>
                    <div style="display:flex;gap:3px;">
                        @for ($s = 1; $s <= min($manualLevel, 5); $s++)
                            <i class="bi bi-star-fill" style="font-size:12px;color:{{ $c['text'] }};opacity:{{ 0.4 + $s * 0.12 }};"></i>
                        @endfor
                    </div>
                </div>
            @endif

            {{-- ── REFERRAL CARD ────────────────────────────────── --}}
            <div class="mb-3" style="background:linear-gradient(135deg,#0f1c2e 0%,#0d1420 60%,#0a1118 100%);border:1px solid #1a2235;border-radius:20px;padding:16px;position:relative;overflow:hidden;">
                <div style="position:absolute;top:-25px;right:-25px;width:120px;height:120px;border-radius:50%;background:radial-gradient(circle,rgba(245,166,35,.08) 0%,transparent 65%);pointer-events:none;"></div>

                {{-- Header --}}
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;">
                    <div style="width:30px;height:30px;border-radius:9px;background:rgba(245,166,35,.10);border:1px solid rgba(245,166,35,.20);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="bi bi-gift-fill" style="font-size:13px;color:#f5a623;"></i>
                    </div>
                    <span style="font-size:13px;font-weight:700;color:#e2eaf8;">Referral Saya</span>
                </div>

                {{-- Kode Referral --}}
                <div style="margin-bottom:12px;">
                    <div style="font-size:10px;color:#3a4d66;letter-spacing:.5px;text-transform:uppercase;margin-bottom:6px;">Kode Referral</div>
                    <div style="display:flex;align-items:center;justify-content:space-between;background:rgba(255,255,255,.04);border:1px solid #1a2235;border-radius:10px;padding:10px 12px;">
                        <span style="font-family:monospace;font-size:15px;font-weight:700;color:#f5a623;letter-spacing:1px;" id="referralCode">{{ $user->refferal_code }}</span>
                        <button onclick="copyReferralCode()" style="background:rgba(245,166,35,.12);border:1px solid rgba(245,166,35,.22);border-radius:7px;padding:5px 10px;color:#f5a623;font-size:13px;cursor:pointer;">
                            <i class="bi bi-clipboard" id="copyCodeIcon"></i>
                        </button>
                    </div>
                </div>

                {{-- Link Referral --}}
                <div>
                    <div style="font-size:10px;color:#3a4d66;letter-spacing:.5px;text-transform:uppercase;margin-bottom:6px;">Link Referral</div>
                    <div style="display:flex;align-items:center;gap:8px;background:rgba(255,255,255,.04);border:1px solid #1a2235;border-radius:10px;padding:10px 12px;">
                        <span style="font-family:monospace;font-size:11px;color:#7a8fad;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;flex:1;" id="referralLink">{{ $referralLink }}</span>
                        <button onclick="copyReferralLink()" style="background:rgba(0,212,138,.08);border:1px solid rgba(0,212,138,.18);border-radius:7px;padding:5px 10px;color:#00d48a;font-size:13px;cursor:pointer;flex-shrink:0;">
                            <i class="bi bi-link-45deg" id="copyLinkIcon"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- ── LEVEL FILTER TABS ────────────────────────────── --}}
            @if ($totalTeam > 0)
                <div class="mb-3" style="background:#0d1120;border:1px solid #1a2235;border-radius:14px;padding:10px;">
                    <div style="display:flex;gap:8px;overflow-x:auto;padding-bottom:2px;">
                        <button class="xt-tab active" onclick="filterLevel('all', this)">
                            <i class="bi bi-grid-fill"></i> Semua ({{ $totalTeam }})
                        </button>
                        @foreach ($levelStats as $level => $stats)
                            <button class="xt-tab" onclick="filterLevel({{ $level }}, this)">
                                L{{ $level }} ({{ $stats['count'] }})
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- ── TEAM LIST ────────────────────────────────────── --}}
            <div style="background:#0d1120;border:1px solid #1a2235;border-radius:20px;overflow:hidden;margin-bottom:12px;">

                {{-- Header --}}
                <div style="padding:14px 16px 13px;border-bottom:1px solid #1a2235;display:flex;align-items:center;justify-content:space-between;">
                    <span style="font-size:13px;font-weight:700;color:#e2eaf8;">Daftar Team Member</span>
                    @if($totalTeam > 0)
                        <span style="font-size:11px;color:#3a4d66;">{{ $totalTeam }} member</span>
                    @endif
                </div>

                @forelse($teamMembers->sortBy('level') as $member)
                    <div class="xt-member" data-level="{{ $member->level }}" style="display:flex;align-items:flex-start;gap:12px;padding:13px 16px;border-bottom:1px solid rgba(26,34,53,.8);transition:background .15s;cursor:default;">

                        {{-- Level Badge --}}
                        <div class="xt-lvl xt-lvl-{{ $member->level }}" style="flex-shrink:0;padding:5px 9px;border-radius:8px;font-size:10px;font-weight:700;text-align:center;min-width:34px;">
                            L{{ $member->level }}
                        </div>

                        {{-- Avatar --}}
                        <div style="width:36px;height:36px;border-radius:11px;background:#0a1118;border:1px solid #1a2235;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                            <i class="bi bi-person-circle" style="font-size:18px;color:#3a4d66;"></i>
                        </div>

                        {{-- Info --}}
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:13px;font-weight:700;color:#e2eaf8;margin-bottom:3px;">{{ $member->username }}</div>

                            <div style="display:flex;align-items:center;gap:5px;margin-bottom:3px;">
                                <i class="bi bi-telephone-fill" style="font-size:10px;color:#3a4d66;"></i>
                                <span style="font-size:11px;color:#7a8fad;">{{ $member->phone }}</span>
                            </div>

                            @if ($member->level > 1)
                                <div style="display:inline-flex;align-items:center;gap:5px;background:rgba(245,166,35,.06);border-left:2px solid rgba(245,166,35,.35);padding:3px 8px;border-radius:4px;margin-bottom:3px;">
                                    <i class="bi bi-arrow-return-right" style="font-size:10px;color:#f5a623;"></i>
                                    <span style="font-size:11px;color:#7a8fad;">Direferral oleh: <span style="color:#f5a623;">{{ $member->referrer_name }}</span></span>
                                </div>
                            @endif

                            <div style="display:flex;align-items:center;gap:4px;">
                                <i class="bi bi-calendar3" style="font-size:10px;color:#3a4d66;"></i>
                                <span style="font-size:11px;color:#3a4d66;">Bergabung {{ \Carbon\Carbon::parse($member->created_at)->diffForHumans() }}</span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div style="padding:40px 20px;text-align:center;">
                        <div style="width:60px;height:60px;border-radius:18px;background:rgba(255,255,255,.04);border:1px solid #1a2235;display:flex;align-items:center;justify-content:center;margin:0 auto 14px;">
                            <i class="bi bi-people" style="font-size:26px;color:#3a4d66;"></i>
                        </div>
                        <div style="font-size:14px;color:#7a8fad;margin-bottom:6px;">Belum ada team member</div>
                        <div style="font-size:12px;color:#3a4d66;line-height:1.5;">Bagikan kode atau link referral kamu<br>untuk mendapatkan team member</div>
                    </div>
                @endforelse

            </div>

        </div>
    </div>

    <style>
        /* ── Filter Tabs ── */
        .xt-tab {
            background: rgba(255,255,255,.04);
            border: 1px solid #1a2235;
            border-radius: 8px;
            padding: 7px 14px;
            color: #7a8fad;
            font-size: 12px;
            font-weight: 500;
            white-space: nowrap;
            cursor: pointer;
            transition: all .2s;
            flex-shrink: 0;
        }
        .xt-tab.active {
            background: #f5a623;
            color: #080b12;
            border-color: #f5a623;
            font-weight: 700;
        }
        .xt-tab:hover:not(.active) {
            background: rgba(255,255,255,.08);
            color: #e2eaf8;
        }

        /* ── Level badge colors ── */
        .xt-lvl-1  { background: rgba(0,212,138,.12);   color: #00d48a; }
        .xt-lvl-2  { background: rgba(100,160,255,.12); color: #64a0ff; }
        .xt-lvl-3  { background: rgba(245,166,35,.12);  color: #f5a623; }
        .xt-lvl-4  { background: rgba(153,69,255,.12);  color: #9945ff; }
        .xt-lvl-5  { background: rgba(240,79,90,.12);   color: #f04f5a; }
        .xt-lvl-6  { background: rgba(20,184,166,.12);  color: #14b8a6; }
        .xt-lvl-7  { background: rgba(251,146,60,.12);  color: #fb923c; }
        .xt-lvl-8  { background: rgba(236,72,153,.12);  color: #ec4899; }
        .xt-lvl-9  { background: rgba(99,102,241,.12);  color: #6366f1; }
        .xt-lvl-10 { background: rgba(234,179,8,.12);   color: #eab308; }

        /* ── Member row hover ── */
        .xt-member:hover { background: rgba(255,255,255,.02) !important; }
        .xt-member:last-child { border-bottom: none !important; }

        /* ── Hidden filter ── */
        .xt-member.hidden { display: none !important; }
    </style>

    <script>
        function showToast(message, type = 'success') {
            const bgColor = type === 'success' ? '#00d48a' : '#f04f5a';
            const toast = document.createElement('div');
            toast.style.cssText = `position:fixed;top:20px;right:20px;background:${bgColor};color:#080b12;padding:10px 18px;border-radius:10px;z-index:9999;font-size:13px;font-weight:700;box-shadow:0 4px 16px rgba(0,0,0,.4);`;
            toast.textContent = message;
            document.body.appendChild(toast);
            setTimeout(() => toast.remove(), 2000);
        }

        function copyReferralCode() {
            const code = document.getElementById('referralCode').textContent.trim();
            const icon = document.getElementById('copyCodeIcon');
            navigator.clipboard.writeText(code).then(() => {
                icon.classList.replace('bi-clipboard', 'bi-check-lg');
                showToast('Kode referral berhasil disalin!');
                setTimeout(() => icon.classList.replace('bi-check-lg', 'bi-clipboard'), 2000);
            }).catch(() => showToast('Gagal menyalin', 'error'));
        }

        function copyReferralLink() {
            const link = document.getElementById('referralLink').textContent.trim();
            const icon = document.getElementById('copyLinkIcon');
            navigator.clipboard.writeText(link).then(() => {
                icon.classList.replace('bi-link-45deg', 'bi-check-lg');
                showToast('Link referral berhasil disalin!');
                setTimeout(() => icon.classList.replace('bi-check-lg', 'bi-link-45deg'), 2000);
            }).catch(() => showToast('Gagal menyalin', 'error'));
        }

        function filterLevel(level, btn) {
            document.querySelectorAll('.xt-tab').forEach(t => t.classList.remove('active'));
            btn.classList.add('active');
            document.querySelectorAll('.xt-member').forEach(m => {
                if (level === 'all' || parseInt(m.dataset.level) === level) {
                    m.classList.remove('hidden');
                } else {
                    m.classList.add('hidden');
                }
            });
        }
    </script>
@endsection