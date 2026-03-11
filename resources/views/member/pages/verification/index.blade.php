@extends('member.layouts.app')
@section('content')
    <div class="scrollable-content">
        <div class="content-section">

            {{-- ── TOP BAR ── --}}
            <div class="d-flex align-items-center justify-content-between mb-4">
                <div>
                    <div style="font-size:11px;color:#3a4d66;letter-spacing:.6px;margin-bottom:3px;">Keamanan Akun</div>
                    <h5 class="mb-0 fw-bold" style="letter-spacing:-.4px;color:#e2eaf8;">
                        Verifikasi <span style="color:#00d48a;">Identitas</span>
                    </h5>
                </div>
                <div style="width:38px;height:38px;border-radius:11px;background:#0d1120;border:1px solid #1a2235;display:flex;align-items:center;justify-content:center;font-size:15px;color:#7a8fad;flex-shrink:0;">
                    <i class="bi bi-shield-check"></i>
                </div>
            </div>

            {{-- ── ALERTS ── --}}
            @if (session('success'))
                <div class="mb-3" style="background:rgba(0,212,138,.08);border:1px solid rgba(0,212,138,.25);border-left:3px solid #00d48a;border-radius:14px;padding:12px 14px;display:flex;align-items:center;gap:10px;">
                    <i class="bi bi-check-circle-fill" style="color:#00d48a;font-size:14px;flex-shrink:0;"></i>
                    <span style="font-size:12px;color:#00d48a;">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="mb-3" style="background:rgba(240,79,90,.08);border:1px solid rgba(240,79,90,.25);border-left:3px solid #f04f5a;border-radius:14px;padding:12px 14px;display:flex;align-items:center;gap:10px;">
                    <i class="bi bi-exclamation-triangle-fill" style="color:#f04f5a;font-size:14px;flex-shrink:0;"></i>
                    <span style="font-size:12px;color:#f04f5a;">{{ session('error') }}</span>
                </div>
            @endif

            {{-- ══════════════════════════════════════════════
                 STATE 1: FORM VERIFIKASI
            ══════════════════════════════════════════════ --}}
            @if (!$verification || !$verification->submitted_at)

                {{-- Info bar --}}
                <div class="mb-3" style="background:#0d1120;border:1px solid #1a2235;border-left:3px solid #f5a623;border-radius:14px;padding:12px 14px;display:flex;align-items:flex-start;gap:10px;">
                    <i class="bi bi-info-circle-fill" style="font-size:14px;color:#f5a623;flex-shrink:0;margin-top:1px;"></i>
                    <div>
                        <div style="font-size:10px;font-weight:700;color:#f5a623;letter-spacing:.8px;text-transform:uppercase;margin-bottom:3px;">Informasi</div>
                        <div style="font-size:12px;color:#7a8fad;line-height:1.55;">Pastikan foto identitas dan selfie terlihat jelas untuk mempercepat proses verifikasi.</div>
                    </div>
                </div>

                <form action="{{ route('member.verification.store') }}" method="POST" enctype="multipart/form-data" id="verificationForm">
                    @csrf

                    {{-- ── CARD: Data Diri ── --}}
                    <div class="mb-3" style="background:#0d1120;border:1px solid #1a2235;border-radius:20px;overflow:hidden;">
                        <div style="padding:14px 16px 13px;border-bottom:1px solid #1a2235;display:flex;align-items:center;gap:10px;">
                            <div style="width:30px;height:30px;border-radius:9px;background:rgba(0,212,138,.1);border:1px solid rgba(0,212,138,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi bi-person-fill" style="font-size:13px;color:#00d48a;"></i>
                            </div>
                            <span style="font-size:13px;font-weight:700;color:#e2eaf8;">Data Diri</span>
                        </div>

                        <div style="padding:16px;">
                            {{-- Nama Lengkap --}}
                            <div class="mb-3">
                                <label style="font-size:10px;color:#3a4d66;letter-spacing:.5px;text-transform:uppercase;display:block;margin-bottom:7px;">Nama Lengkap</label>
                                <input type="text" name="full_name"
                                    value="{{ old('full_name') }}"
                                    placeholder="Sesuai KTP / identitas resmi"
                                    required
                                    class="vf-input @error('full_name') vf-invalid @enderror">
                                @error('full_name')
                                    <small style="color:#f04f5a;font-size:11px;display:block;margin-top:5px;">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Nomor Identitas --}}
                            <div class="mb-3">
                                <label style="font-size:10px;color:#3a4d66;letter-spacing:.5px;text-transform:uppercase;display:block;margin-bottom:7px;">Nomor Identitas</label>
                                <input type="text" name="identity_number"
                                    value="{{ old('identity_number') }}"
                                    placeholder="Masukkan nomor identitas"
                                    required
                                    class="vf-input @error('identity_number') vf-invalid @enderror">
                                @error('identity_number')
                                    <small style="color:#f04f5a;font-size:11px;display:block;margin-top:5px;">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Jenis Identitas --}}
                            <div>
                                <label style="font-size:10px;color:#3a4d66;letter-spacing:.5px;text-transform:uppercase;display:block;margin-bottom:7px;">Jenis Identitas</label>
                                <select name="identity_type" required class="vf-input @error('identity_type') vf-invalid @enderror">
                                    <option value="">Pilih Jenis Identitas</option>
                                    <option value="KTP"    {{ old('identity_type')=='KTP'    ? 'selected':'' }}>KTP</option>
                                    <option value="SIM"    {{ old('identity_type')=='SIM'    ? 'selected':'' }}>SIM</option>
                                    <option value="Paspor" {{ old('identity_type')=='Paspor' ? 'selected':'' }}>Paspor</option>
                                </select>
                                @error('identity_type')
                                    <small style="color:#f04f5a;font-size:11px;display:block;margin-top:5px;">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- ── CARD: Foto Identitas ── --}}
                    <div class="mb-3" style="background:#0d1120;border:1px solid #1a2235;border-radius:20px;overflow:hidden;">
                        <div style="padding:14px 16px 13px;border-bottom:1px solid #1a2235;display:flex;align-items:center;gap:10px;">
                            <div style="width:30px;height:30px;border-radius:9px;background:rgba(245,166,35,.1);border:1px solid rgba(245,166,35,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi bi-card-image" style="font-size:13px;color:#f5a623;"></i>
                            </div>
                            <span style="font-size:13px;font-weight:700;color:#e2eaf8;">Foto Identitas</span>
                        </div>
                        <div style="padding:16px;">
                            <input type="file" id="identity_photo" name="identity_photo" accept="image/*" class="d-none"
                                onchange="previewImage(this,'identityPreview')" required>
                            <div class="vf-upload" onclick="document.getElementById('identity_photo').click()">
                                <div id="identityPreview" class="vf-preview-wrap">
                                    <i class="bi bi-cloud-arrow-up" style="font-size:28px;color:#3a4d66;display:block;margin-bottom:8px;"></i>
                                    <p style="font-size:13px;font-weight:600;color:#7a8fad;margin-bottom:4px;">Tap untuk upload foto KTP/SIM/Paspor</p>
                                    <small style="font-size:10px;color:#3a4d66;">JPG, JPEG, PNG &nbsp;·&nbsp; Maks 2 MB</small>
                                </div>
                            </div>
                            @error('identity_photo')
                                <small style="color:#f04f5a;font-size:11px;display:block;margin-top:6px;">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- ── CARD: Foto Selfie ── --}}
                    <div class="mb-3" style="background:#0d1120;border:1px solid #1a2235;border-radius:20px;overflow:hidden;">
                        <div style="padding:14px 16px 13px;border-bottom:1px solid #1a2235;display:flex;align-items:center;gap:10px;">
                            <div style="width:30px;height:30px;border-radius:9px;background:rgba(98,126,234,.1);border:1px solid rgba(98,126,234,.2);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                <i class="bi bi-camera-fill" style="font-size:13px;color:#627eea;"></i>
                            </div>
                            <span style="font-size:13px;font-weight:700;color:#e2eaf8;">Selfie dengan Identitas</span>
                        </div>
                        <div style="padding:16px;">
                            <input type="file" id="selfie_photo" name="selfie_photo" accept="image/*" class="d-none"
                                onchange="previewImage(this,'selfiePreview')" required>
                            <div class="vf-upload" onclick="document.getElementById('selfie_photo').click()">
                                <div id="selfiePreview" class="vf-preview-wrap">
                                    <i class="bi bi-cloud-arrow-up" style="font-size:28px;color:#3a4d66;display:block;margin-bottom:8px;"></i>
                                    <p style="font-size:13px;font-weight:600;color:#7a8fad;margin-bottom:4px;">Tap untuk upload foto selfie</p>
                                    <small style="font-size:10px;color:#3a4d66;">Pegang identitas saat selfie &nbsp;·&nbsp; Maks 2 MB</small>
                                </div>
                            </div>
                            @error('selfie_photo')
                                <small style="color:#f04f5a;font-size:11px;display:block;margin-top:6px;">{{ $message }}</small>
                            @enderror
                        </div>
                    </div>

                    {{-- ── SUBMIT BUTTON ── --}}
                    <button type="submit"
                        style="width:100%;padding:14px;border-radius:14px;background:linear-gradient(135deg,#f5a623,#e8940f);border:none;color:#080b12;font-size:14px;font-weight:700;letter-spacing:.3px;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;">
                        <i class="bi bi-shield-check" style="font-size:15px;"></i>
                        Ajukan Verifikasi
                    </button>
                </form>

            {{-- ══════════════════════════════════════════════
                 STATE 2: PENDING
            ══════════════════════════════════════════════ --}}
            @elseif($verification->submitted_at && !$user->is_verified)

                {{-- Status badge --}}
                <div class="mb-3" style="background:linear-gradient(135deg,#0f1c2e 0%,#0d1420 60%,#0a1118 100%);border:1px solid #1a2235;border-radius:20px;padding:24px 20px;position:relative;overflow:hidden;text-align:center;">
                    <div style="position:absolute;top:-30px;right:-30px;width:140px;height:140px;border-radius:50%;background:radial-gradient(circle,rgba(245,166,35,.08) 0%,transparent 65%);pointer-events:none;"></div>
                    <div style="width:68px;height:68px;margin:0 auto 16px;background:rgba(245,166,35,.1);border:1px solid rgba(245,166,35,.25);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-clock-history" style="font-size:30px;color:#f5a623;"></i>
                    </div>
                    <div style="display:inline-flex;align-items:center;gap:5px;background:rgba(245,166,35,.07);border:1px solid rgba(245,166,35,.2);border-radius:99px;padding:3px 10px;font-size:10px;font-weight:700;color:#f5a623;letter-spacing:.6px;margin-bottom:12px;">
                        <span style="width:5px;height:5px;border-radius:50%;background:#f5a623;display:inline-block;animation:xi-blink 1.6s infinite;"></span>
                        MENUNGGU REVIEW
                    </div>
                    <h6 style="color:#e2eaf8;font-weight:700;margin-bottom:6px;">Verifikasi Sedang Diproses</h6>
                    <p style="font-size:12px;color:#7a8fad;line-height:1.6;margin-bottom:12px;">Dokumen Anda sedang ditinjau oleh admin. Mohon tunggu hingga proses selesai.</p>
                    <small style="font-size:11px;color:#3a4d66;">Diajukan: {{ $verification->submitted_at->format('d M Y, H:i') }}</small>
                </div>

                {{-- Data yang disubmit --}}
                <div style="background:#0d1120;border:1px solid #1a2235;border-radius:20px;overflow:hidden;">
                    <div style="padding:14px 16px 13px;border-bottom:1px solid #1a2235;">
                        <span style="font-size:13px;font-weight:700;color:#e2eaf8;">Data yang Diajukan</span>
                    </div>
                    <div style="padding:4px 0;">
                        <div class="vf-data-row">
                            <span style="font-size:10px;color:#3a4d66;letter-spacing:.4px;text-transform:uppercase;">Nama Lengkap</span>
                            <span style="font-size:13px;font-weight:600;color:#e2eaf8;">{{ $verification->full_name }}</span>
                        </div>
                        <div class="vf-data-row">
                            <span style="font-size:10px;color:#3a4d66;letter-spacing:.4px;text-transform:uppercase;">Nomor Identitas</span>
                            <span style="font-size:13px;font-weight:600;color:#e2eaf8;font-family:monospace;">{{ $verification->identity_number }}</span>
                        </div>
                        <div class="vf-data-row" style="border-bottom:none;">
                            <span style="font-size:10px;color:#3a4d66;letter-spacing:.4px;text-transform:uppercase;">Jenis Identitas</span>
                            <span style="font-size:13px;font-weight:600;color:#e2eaf8;">{{ $verification->identity_type }}</span>
                        </div>
                    </div>
                </div>

            {{-- ══════════════════════════════════════════════
                 STATE 3: VERIFIED
            ══════════════════════════════════════════════ --}}
            @elseif($user->is_verified)

                {{-- Status badge --}}
                <div class="mb-3" style="background:linear-gradient(135deg,#0f1c2e 0%,#0d1420 60%,#0a1118 100%);border:1px solid #1a2235;border-radius:20px;padding:24px 20px;position:relative;overflow:hidden;text-align:center;">
                    <div style="position:absolute;top:-30px;right:-30px;width:140px;height:140px;border-radius:50%;background:radial-gradient(circle,rgba(0,212,138,.08) 0%,transparent 65%);pointer-events:none;"></div>
                    <div style="width:68px;height:68px;margin:0 auto 16px;background:rgba(0,212,138,.1);border:1px solid rgba(0,212,138,.25);border-radius:50%;display:flex;align-items:center;justify-content:center;">
                        <i class="bi bi-patch-check-fill" style="font-size:30px;color:#00d48a;"></i>
                    </div>
                    <div style="display:inline-flex;align-items:center;gap:5px;background:rgba(0,212,138,.07);border:1px solid rgba(0,212,138,.2);border-radius:99px;padding:3px 10px;font-size:10px;font-weight:700;color:#00d48a;letter-spacing:.6px;margin-bottom:12px;">
                        <span style="width:5px;height:5px;border-radius:50%;background:#00d48a;display:inline-block;animation:xi-blink 1.6s infinite;"></span>
                        TERVERIFIKASI
                    </div>
                    <h6 style="color:#e2eaf8;font-weight:700;margin-bottom:6px;">Akun Anda Terverifikasi</h6>
                    <p style="font-size:12px;color:#7a8fad;line-height:1.6;margin-bottom:12px;">Selamat! Identitas Anda telah berhasil diverifikasi oleh admin.</p>
                    @if ($verification->verified_at)
                        <small style="font-size:11px;color:#3a4d66;">Diverifikasi: {{ $verification->verified_at->format('d M Y, H:i') }}</small>
                    @endif
                </div>

                {{-- Data terverifikasi --}}
                <div style="background:#0d1120;border:1px solid #1a2235;border-radius:20px;overflow:hidden;">
                    <div style="padding:14px 16px 13px;border-bottom:1px solid #1a2235;display:flex;align-items:center;gap:8px;">
                        <i class="bi bi-shield-fill-check" style="font-size:13px;color:#00d48a;"></i>
                        <span style="font-size:13px;font-weight:700;color:#e2eaf8;">Data Terverifikasi</span>
                    </div>
                    <div style="padding:4px 0;">
                        <div class="vf-data-row">
                            <span style="font-size:10px;color:#3a4d66;letter-spacing:.4px;text-transform:uppercase;">Nama Lengkap</span>
                            <span style="font-size:13px;font-weight:600;color:#e2eaf8;">{{ $verification->full_name }}</span>
                        </div>
                        <div class="vf-data-row">
                            <span style="font-size:10px;color:#3a4d66;letter-spacing:.4px;text-transform:uppercase;">Nomor Identitas</span>
                            <span style="font-size:13px;font-weight:600;color:#e2eaf8;font-family:monospace;">{{ $verification->identity_number }}</span>
                        </div>
                        <div class="vf-data-row" style="border-bottom:none;">
                            <span style="font-size:10px;color:#3a4d66;letter-spacing:.4px;text-transform:uppercase;">Jenis Identitas</span>
                            <span style="font-size:13px;font-weight:600;color:#e2eaf8;">{{ $verification->identity_type }}</span>
                        </div>
                    </div>
                </div>

            @endif

        </div>
    </div>

    <style>
        @keyframes xi-blink { 0%,100%{opacity:1} 50%{opacity:.15} }

        /* ── Input field ── */
        .vf-input {
            width: 100%;
            background: rgba(255,255,255,.03);
            border: 1px solid #1a2235;
            border-radius: 12px;
            padding: 11px 14px;
            font-size: 13px;
            color: #e2eaf8;
            outline: none;
            transition: border-color .2s, background .2s;
            -webkit-appearance: none;
            appearance: none;
        }
        .vf-input::placeholder { color: #3a4d66; }
        .vf-input:focus {
            border-color: rgba(0,212,138,.4);
            background: rgba(0,212,138,.04);
        }
        .vf-input.vf-invalid { border-color: rgba(240,79,90,.5); }
        select.vf-input { cursor: pointer; }
        select.vf-input option { background: #0d1120; color: #e2eaf8; }

        /* ── Upload area ── */
        .vf-upload {
            border: 1.5px dashed #1a2235;
            border-radius: 14px;
            padding: 24px 16px;
            text-align: center;
            cursor: pointer;
            transition: border-color .2s, background .2s;
        }
        .vf-upload:hover {
            border-color: rgba(0,212,138,.35);
            background: rgba(0,212,138,.03);
        }
        .vf-preview-wrap img {
            width: 100%;
            max-height: 180px;
            object-fit: cover;
            border-radius: 10px;
        }

        /* ── Data row ── */
        .vf-data-row {
            display: flex;
            flex-direction: column;
            gap: 3px;
            padding: 13px 16px;
            border-bottom: 1px solid rgba(26,34,53,.9);
        }
    </style>

    <script>
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Preview" style="width:100%;max-height:180px;object-fit:cover;border-radius:10px;">`;
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        document.getElementById('verificationForm')?.addEventListener('submit', function(e) {
            const id  = document.getElementById('identity_photo')?.files[0];
            const sel = document.getElementById('selfie_photo')?.files[0];
            if (!id || !sel) { e.preventDefault(); alert('Mohon upload semua foto yang diperlukan'); return; }
            if (id.size > 2048000 || sel.size > 2048000) { e.preventDefault(); alert('Ukuran file maksimal 2MB'); }
        });
    </script>
@endsection