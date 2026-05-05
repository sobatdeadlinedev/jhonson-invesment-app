@extends('admin.layouts.app')
@section('content')
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar pt-5 pt-lg-10">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack flex-wrap">
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
                        User Level Management
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard.index') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item"><span class="bullet bg-gray-500 w-5px h-2px"></span></li>
                        <li class="breadcrumb-item text-muted">User Level</li>
                    </ul>
                </div>

                {{-- Info periode minggu --}}
                <div class="d-flex align-items-center gap-2 bg-light-primary rounded px-4 py-3">
                    <i class="ki-outline ki-calendar-2 fs-2 text-primary"></i>
                    <div>
                        <div class="fw-bold text-primary fs-7">Periode Minggu Ini</div>
                        <div class="text-gray-700 fs-8">
                            {{ $weekStart->format('d M Y') }} – {{ $weekEnd->format('d M Y') }}
                            <span class="badge badge-light-warning ms-2">Gajian: Rabu</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            @if(session('success'))
                <div class="alert alert-success d-flex align-items-center p-5 mb-6">
                    <i class="ki-outline ki-shield-tick fs-2hx text-success me-4"></i>
                    <div>{{ session('success') }}</div>
                </div>
            @endif

            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                            <input type="text" id="search-user"
                                class="form-control form-control-solid w-250px ps-13"
                                placeholder="Cari user..." />
                        </div>
                    </div>
                    <div class="card-toolbar">
                        <span class="text-muted fs-7">
                            Bonus dihitung dari volume trading downline dalam periode Rabu–Selasa.
                        </span>
                    </div>
                </div>
                <!--end::Card header-->

                <!--begin::Card body-->
                <div class="card-body py-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_user_levels">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-175px">User</th>
                                    <th class="min-w-100px">Referral Code</th>
                                    <th class="min-w-80px text-center">Direct</th>
                                    <th class="min-w-125px">Level Agen</th>
                                    <th class="min-w-100px text-center">Total Team</th>
                                    <th class="min-w-100px text-center">Aktif</th>
                                    <th class="min-w-150px text-end">Volume Minggu Ini</th>
                                    <th class="min-w-150px text-end">Gaji Minggu Ini</th>
                                    <th class="min-w-175px">Di-set Oleh</th>
                                    <th class="text-end min-w-150px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                @forelse($users as $user)
                                    @php
                                        $stats  = $userStats[$user->id];
                                        $lvl    = $user->userLevel?->level;
                                        $colors = [1=>'success',2=>'info',3=>'warning',4=>'primary',5=>'danger',
                                                   6=>'dark',7=>'secondary',8=>'info',9=>'warning',10=>'primary'];
                                        $color  = $lvl ? ($colors[$lvl] ?? 'secondary') : null;
                                    @endphp
                                    <tr>
                                        {{-- User --}}
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-800 fw-bold mb-1">{{ $user->name }}</span>
                                                <span class="text-muted fs-7">{{ $user->email }}</span>
                                            </div>
                                        </td>

                                        {{-- Referral Code --}}
                                        <td>
                                            <span class="badge badge-light-primary">{{ $user->refferal_code }}</span>
                                        </td>

                                        {{-- Direct --}}
                                        <td class="text-center">
                                            <span class="badge badge-light-info">{{ $user->referrals_count }}</span>
                                        </td>

                                        {{-- Level Agen --}}
                                        <td>
                                            @if($lvl)
                                                <div class="d-flex flex-column gap-1">
                                                    <span class="badge badge-light-{{ $color }} fs-7 fw-bold">
                                                        Level {{ $lvl }}
                                                        <span class="ms-1 opacity-75">({{ number_format($stats['rate'] * 100, 1) }}%)</span>
                                                    </span>
                                                    @if($user->userLevel->note)
                                                        <div class="text-muted fs-8">{{ $user->userLevel->note }}</div>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted fs-7">— Belum di-set</span>
                                            @endif
                                        </td>

                                        {{-- Total Team --}}
                                        <td class="text-center">
                                            <span class="fw-bold text-gray-800">{{ number_format($stats['totalTeam']) }}</span>
                                        </td>

                                        {{-- Aktif --}}
                                        <td class="text-center">
                                            <span class="fw-bold text-success">{{ number_format($stats['activeTeam']) }}</span>
                                        </td>

                                        {{-- Volume Minggu Ini --}}
                                        <td class="text-end">
                                            <span class="fw-bold text-gray-700">
                                                {{ number_format($stats['weeklyVolume'], 2, '.', ',') }} USDT
                                            </span>
                                        </td>

                                        {{-- Gaji Minggu Ini --}}
                                        <td class="text-end">
                                            @if($lvl && $stats['weeklySalary'] > 0)
                                                <span class="fw-bold text-success fs-6">
                                                    {{ number_format($stats['weeklySalary'], 2, '.', ',') }} USDT
                                                </span>
                                            @elseif($lvl)
                                                <span class="text-muted fs-7">0.00 USDT</span>
                                            @else
                                                <span class="text-muted fs-7">—</span>
                                            @endif
                                        </td>

                                        {{-- Di-set Oleh --}}
                                        <td>
                                            @if($user->userLevel?->assignedBy)
                                                <div class="d-flex flex-column">
                                                    <span class="text-gray-700 fs-7">{{ $user->userLevel->assignedBy->name }}</span>
                                                    <span class="text-muted fs-8">
                                                        {{ $user->userLevel->updated_at->format('d M Y H:i') }}
                                                    </span>
                                                </div>
                                            @else
                                                <span class="text-muted fs-7">—</span>
                                            @endif
                                        </td>

                                        {{-- Aksi --}}
                                        <td class="text-end">
                                            {{-- Tombol Detail Team --}}
                                            <button class="btn btn-sm btn-light btn-active-light-info me-1"
                                                onclick="loadTeamDetail({{ $user->id }}, '{{ addslashes($user->name) }}')"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modal_team_detail">
                                                <i class="ki-outline ki-people fs-5"></i>
                                                Team
                                            </button>

                                            {{-- Tombol Set/Edit Level --}}
                                            <button class="btn btn-sm btn-light btn-active-light-primary me-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modal_set_level_{{ $user->id }}">
                                                <i class="ki-outline ki-pencil fs-5"></i>
                                                {{ $user->userLevel ? 'Edit' : 'Set' }}
                                            </button>

                                            {{-- Tombol Hapus Level --}}
                                            @if($user->userLevel)
                                                <form action="{{ route('admin.user-levels.destroy', $user->id) }}"
                                                    method="POST" class="d-inline"
                                                    onsubmit="return confirm('Hapus level manual user ini?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-light btn-active-light-danger">
                                                        <i class="ki-outline ki-trash fs-5"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-10">
                                            <div class="text-gray-600">Tidak ada user ditemukan</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
    </div>
    <!--end::Content-->

    {{-- ========================================================== --}}
    {{-- MODAL: Detail Team & Gaji                                  --}}
    {{-- ========================================================== --}}
    <div class="modal fade" id="modal_team_detail" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="fw-bold" id="modal_team_title">Detail Team</h2>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2"
                        data-bs-dismiss="modal" aria-label="Close">
                        <i class="ki-outline ki-cross fs-1"></i>
                    </div>
                </div>
                <div class="modal-body">
                    {{-- Loading state --}}
                    <div id="team_detail_loading" class="text-center py-15">
                        <div class="spinner-border text-primary" role="status"></div>
                        <div class="text-muted mt-3">Memuat data team...</div>
                    </div>

                    {{-- Content (hidden until loaded) --}}
                    <div id="team_detail_content" class="d-none">

                        {{-- Summary Cards --}}
                        <div class="row g-4 mb-6">
                            <div class="col-sm-6 col-lg-3">
                                <div class="card card-flush bg-light-primary h-100">
                                    <div class="card-body py-4">
                                        <div class="text-primary fw-bold fs-7 mb-1">Level Agen</div>
                                        <div class="fs-2 fw-bolder text-gray-800" id="sd_agent_level">—</div>
                                        <div class="text-muted fs-8" id="sd_rate"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card card-flush bg-light-info h-100">
                                    <div class="card-body py-4">
                                        <div class="text-info fw-bold fs-7 mb-1">Total Team</div>
                                        <div class="fs-2 fw-bolder text-gray-800" id="sd_total_team">0</div>
                                        <div class="text-muted fs-8"><span id="sd_active_team">0</span> aktif</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card card-flush bg-light-warning h-100">
                                    <div class="card-body py-4">
                                        <div class="text-warning fw-bold fs-7 mb-1">Volume Minggu Ini</div>
                                        <div class="fs-4 fw-bolder text-gray-800" id="sd_weekly_volume">0.00 USDT</div>
                                        <div class="text-muted fs-8" id="sd_period"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-3">
                                <div class="card card-flush bg-light-success h-100">
                                    <div class="card-body py-4">
                                        <div class="text-success fw-bold fs-7 mb-1">Gaji Minggu Ini</div>
                                        <div class="fs-4 fw-bolder text-success" id="sd_weekly_salary">0.00 USDT</div>
                                        <div class="text-muted fs-8">Dicairkan hari Rabu</div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Breakdown per Level --}}
                        <h4 class="fw-bold text-gray-800 mb-4">Breakdown per Level Referral</h4>
                        <div class="table-responsive">
                            <table class="table table-row-bordered table-row-gray-200 align-middle gy-4">
                                <thead>
                                    <tr class="fw-bold text-muted fs-7 text-uppercase">
                                        <th>Level</th>
                                        <th class="text-center">Jumlah Member</th>
                                        <th class="text-center">Aktif</th>
                                        <th class="text-end">Volume Minggu Ini</th>
                                    </tr>
                                </thead>
                                <tbody id="sd_breakdown_body">
                                    {{-- diisi via JS --}}
                                </tbody>
                                <tfoot>
                                    <tr class="fw-bold fs-6 border-top-2">
                                        <td>TOTAL</td>
                                        <td class="text-center" id="sd_total_members">0</td>
                                        <td class="text-center" id="sd_total_active">0</td>
                                        <td class="text-end" id="sd_total_vol">Rp 0</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        {{-- Formula Note --}}
                        <div class="notice d-flex bg-light-primary rounded border-primary border border-dashed p-4 mt-4">
                            <i class="ki-outline ki-information-5 fs-2tx text-primary me-4"></i>
                            <div class="d-flex flex-stack flex-grow-1">
                                <div>
                                    <div class="fw-bold text-gray-800">Formula Perhitungan Gaji</div>
                                    <div class="text-gray-600 fs-7 mt-1">
                                        <strong>Gaji</strong> = Total Volume Trading Downline × Bonus Rate Level Agen<br>
                                        <span id="sd_formula_detail" class="text-primary fw-semibold"></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>{{-- end #team_detail_content --}}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ========================================================== --}}
    {{-- MODAL: Set / Edit Level                                    --}}
    {{-- ========================================================== --}}
    @foreach($users as $user)
        <div class="modal fade" id="modal_set_level_{{ $user->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-500px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">Set Level Manual</h2>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2"
                            data-bs-dismiss="modal" aria-label="Close">
                            <i class="ki-outline ki-cross fs-1"></i>
                        </div>
                    </div>

                    <form action="{{ route('admin.user-levels.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="user_id" value="{{ $user->id }}">

                        <div class="modal-body">
                            {{-- User Info --}}
                            <div class="d-flex align-items-center gap-3 p-4 bg-light rounded mb-6">
                                <div class="symbol symbol-40px">
                                    <div class="symbol-label bg-light-primary text-primary fw-bold fs-5">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                </div>
                                <div>
                                    <div class="fw-bold text-gray-800">{{ $user->name }}</div>
                                    <div class="text-muted fs-7">{{ $user->email }}</div>
                                </div>
                            </div>

                            {{-- Level Select --}}
                            <div class="mb-5">
                                <label class="form-label fw-semibold required">Level</label>
                                <select name="level" class="form-select form-select-solid" required>
                                    <option value="">— Pilih Level —</option>
                                    @php
                                        $bonusRates = [1=>0.5,2=>1,3=>1.5,4=>2,5=>2.5,
                                                       6=>3,7=>3.5,8=>4,9=>4.5,10=>5];
                                    @endphp
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}"
                                            {{ $user->userLevel?->level == $i ? 'selected' : '' }}>
                                            Level {{ $i }} — Bonus {{ $bonusRates[$i] }}%
                                        </option>
                                    @endfor
                                </select>
                                <div class="form-text text-muted">
                                    Bonus dihitung dari total volume trading seluruh downline dalam 1 minggu.
                                </div>
                            </div>

                            {{-- Note --}}
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Catatan (opsional)</label>
                                <textarea name="note" class="form-control form-control-solid" rows="2"
                                    placeholder="Alasan pemberian level...">{{ $user->userLevel?->note }}</textarea>
                            </div>
                        </div>

                        <div class="modal-footer flex-center gap-3">
                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="ki-outline ki-check fs-4 me-1"></i>
                                Simpan Level
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endforeach

    @push('scripts')
    <script>
        // ── Search ────────────────────────────────────────────────────────────
        $(document).ready(function () {
            $('#search-user').on('keyup', function () {
                const val = $(this).val().toLowerCase();
                $('#kt_table_user_levels tbody tr').filter(function () {
                    $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1);
                });
            });
        });

        // ── Helpers ───────────────────────────────────────────────────────────
        function formatUSDT(amount) {
            return parseFloat(amount).toLocaleString('en-US', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }) + ' USDT';
        }

        // ── Load Team Detail via AJAX ─────────────────────────────────────────
        function loadTeamDetail(userId, userName) {
            // Reset modal state
            document.getElementById('modal_team_title').textContent = 'Detail Team: ' + userName;
            document.getElementById('team_detail_loading').classList.remove('d-none');
            document.getElementById('team_detail_content').classList.add('d-none');

            fetch(`/admin/user-levels/${userId}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content ?? ''
                }
            })
            .then(res => res.json())
            .then(data => {
                // Summary cards
                document.getElementById('sd_agent_level').textContent =
                    data.agentLevel ? 'Level ' + data.agentLevel : '— Belum di-set';
                document.getElementById('sd_rate').textContent =
                    data.agentLevel ? 'Bonus Rate: ' + data.rate.toFixed(1) + '%' : '';
                document.getElementById('sd_total_team').textContent = data.totalTeam.toLocaleString('id-ID');
                document.getElementById('sd_active_team').textContent = data.activeTeam.toLocaleString('id-ID');
                document.getElementById('sd_weekly_volume').textContent = formatUSDT(data.weeklyVolume);
                document.getElementById('sd_period').textContent = data.weekStart + ' – ' + data.weekEnd;
                document.getElementById('sd_weekly_salary').textContent = formatUSDT(data.weeklySalary);

                // Formula detail
                document.getElementById('sd_formula_detail').textContent =
                    data.agentLevel
                        ? formatUSDT(data.weeklyVolume) + ' × ' + data.rate.toFixed(1) + '% = ' + formatUSDT(data.weeklySalary)
                        : 'Belum ada level agen yang di-set.';

                // Breakdown table
                const tbody = document.getElementById('sd_breakdown_body');
                tbody.innerHTML = '';
                let totalMem = 0, totalAct = 0, totalVol = 0;

                if (Object.keys(data.breakdown).length === 0) {
                    tbody.innerHTML = '<tr><td colspan="4" class="text-center text-muted py-6">Tidak ada downline.</td></tr>';
                } else {
                    Object.entries(data.breakdown).forEach(([lvl, row]) => {
                        totalMem += row.count;
                        totalAct += row.activeCount;
                        totalVol += row.weeklyVolume;

                        const tr = document.createElement('tr');
                        tr.innerHTML = `
                            <td>
                                <span class="badge badge-light-primary fw-bold">Level ${lvl}</span>
                            </td>
                            <td class="text-center fw-bold">${row.count.toLocaleString('id-ID')}</td>
                            <td class="text-center">
                                <span class="text-success fw-bold">${row.activeCount.toLocaleString('id-ID')}</span>
                            </td>
                            <td class="text-end fw-bold">${formatUSDT(row.weeklyVolume)}</td>
                        `;
                        tbody.appendChild(tr);
                    });
                }

                document.getElementById('sd_total_members').textContent = totalMem.toLocaleString('id-ID');
                document.getElementById('sd_total_active').textContent  = totalAct.toLocaleString('id-ID');
                document.getElementById('sd_total_vol').textContent     = formatUSDT(totalVol);

                // Show content
                document.getElementById('team_detail_loading').classList.add('d-none');
                document.getElementById('team_detail_content').classList.remove('d-none');
            })
            .catch(err => {
                console.error(err);
                document.getElementById('team_detail_loading').innerHTML =
                    '<div class="text-danger py-10 text-center">Gagal memuat data. Coba lagi.</div>';
            });
        }
    </script>
    @endpush
@endsection