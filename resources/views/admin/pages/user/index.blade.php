@extends('admin.layouts.app')
@section('content')
    <div id="kt_app_toolbar" class="app-toolbar pt-5 pt-lg-10">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack flex-wrap">
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">Users List</h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard.index') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">User Management</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            @if (session('success'))
                <div class="alert alert-success alert-dismissible d-flex align-items-center p-5 mb-10">
                    <i class="ki-outline ki-shield-tick fs-2hx text-success me-4"></i>
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-dark">Success</h4>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card">
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                            <input type="text" id="search-user"
                                class="form-control form-control-solid w-250px ps-13"
                                placeholder="Search user" />
                        </div>
                    </div>
                </div>

                <div class="card-body py-4">
                    <div class="table-responsive">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-150px">User</th>
                                    <th class="min-w-100px">Username</th>
                                    <th class="min-w-125px">Phone</th>
                                    <th class="min-w-100px">Verified</th>
                                    <th class="min-w-120px">Exchange</th>
                                    <th class="min-w-120px">Trade</th>
                                    <th class="min-w-100px">Deposit</th>
                                    <th class="min-w-100px">Withdrawals</th>
                                    <th class="min-w-100px">Team</th>
                                    <th class="min-w-175px">Volume</th>
                                    <th class="min-w-125px">Joined Date</th>
                                    <th class="text-end min-w-80px">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                @forelse($users as $user)
                                    @php
                                        $wdTransactions      = $user->transactions->whereIn('type', ['withdrawal', 'deduction']);
                                        $depositTransactions = $user->transactions->where('type', 'deposit');

                                        $level1Users = $user->referrals->map->referred->filter();
                                        $level2Users = $user->referrals->flatMap(function ($ref) {
                                            return $ref->referred?->referrals?->map->referred ?? collect();
                                        })->filter();
                                        $level3Users = $user->referrals->flatMap(function ($ref) {
                                            return $ref->referred?->referrals?->flatMap(function ($ref2) {
                                                return $ref2->referred?->referrals?->map->referred ?? collect();
                                            }) ?? collect();
                                        })->filter();

                                        $totalTeam = $level1Users->count() + $level2Users->count() + $level3Users->count();
                                    @endphp
                                    <tr>
                                        {{-- User --}}
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-800 fw-bold mb-1">{{ $user->name }}</span>
                                                <span class="text-muted fs-7">{{ $user->email }}</span>
                                            </div>
                                        </td>

                                        {{-- Username --}}
                                        <td>{{ $user->username }}</td>

                                        {{-- Phone --}}
                                        <td>{{ $user->phone }}</td>

                                        {{-- Verified --}}
                                        <td>
                                            @if ($user->is_verified)
                                                <span class="badge badge-light-success">Verified</span>
                                            @else
                                                <span class="badge badge-light-warning">Unverified</span>
                                            @endif
                                        </td>

                                        {{-- Exchange Balance --}}
                                        <td>
                                            @php $exc = $user->exchange_balance ?? 0; @endphp
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold {{ $exc > 0 ? 'text-success' : ($exc < 0 ? 'text-danger' : 'text-muted') }}">
                                                    {{ number_format($exc, 2) }}
                                                </span>
                                                <span class="text-muted fs-8">USDT</span>
                                            </div>
                                        </td>

                                        {{-- Trade Balance --}}
                                        <td>
                                            @php $trade = $user->trade_balance ?? 0; @endphp
                                            <div class="d-flex flex-column">
                                                <span class="fw-bold {{ $trade > 0 ? 'text-primary' : ($trade < 0 ? 'text-danger' : 'text-muted') }}">
                                                    {{ number_format($trade, 2) }}
                                                </span>
                                                <span class="text-muted fs-8">USDT</span>
                                            </div>
                                        </td>

                                        {{-- Deposit --}}
                                        <td>
                                            @if($user->deposit_count > 0)
                                                <button class="btn btn-light-success btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#kt_modal_deposit_{{ $user->id }}">
                                                    <i class="ki-outline ki-arrow-down fs-5"></i>
                                                    {{ $user->deposit_count }}x
                                                </button>
                                            @else
                                                <span class="badge badge-light-secondary">-</span>
                                            @endif
                                        </td>

                                        {{-- Withdrawals --}}
                                        <td>
                                            @if($user->withdrawal_count > 0)
                                                <button class="btn btn-light-info btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#kt_modal_wd_{{ $user->id }}">
                                                    <i class="ki-outline ki-wallet fs-5"></i>
                                                    {{ $user->withdrawal_count }}x
                                                </button>
                                            @else
                                                <span class="badge badge-light-secondary">-</span>
                                            @endif
                                        </td>

                                        {{-- Team --}}
                                        <td>
                                            @if($totalTeam > 0)
                                                <button class="btn btn-light-primary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#kt_modal_team_{{ $user->id }}">
                                                    <i class="ki-outline ki-people fs-5"></i>
                                                    {{ $totalTeam }}
                                                </button>
                                            @else
                                                <span class="badge badge-light-secondary">-</span>
                                            @endif
                                        </td>

                                        {{-- Volume --}}
                                        <td>
                                            @if($user->target_volume > 0)
                                                @php
                                                    $pct   = min(100, ($user->achieved_volume / $user->target_volume) * 100);
                                                    $color = $pct >= 100 ? 'success' : ($pct >= 50 ? 'primary' : 'warning');
                                                @endphp
                                                <div class="d-flex flex-column gap-1" style="min-width:160px">
                                                    <div class="d-flex justify-content-between fs-8 text-muted">
                                                        <span>{{ number_format($user->achieved_volume, 2) }} / {{ number_format($user->target_volume, 2) }}</span>
                                                        <span class="fw-bold text-{{ $color }}">{{ number_format($pct, 1) }}%</span>
                                                    </div>
                                                    <div class="progress h-6px">
                                                        <div class="progress-bar bg-{{ $color }}" style="width: {{ $pct }}%"></div>
                                                    </div>
                                                    @if($pct >= 100)
                                                        <span class="badge badge-light-success fs-8">Completed</span>
                                                    @else
                                                        <span class="badge badge-light-warning fs-8">In Progress</span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="badge badge-light-secondary">No Target</span>
                                            @endif
                                        </td>

                                        {{-- Joined --}}
                                        <td>{{ $user->created_at->format('d M Y, h:i a') }}</td>

                                        {{-- Actions --}}
                                        <td class="text-end">
                                            <button class="btn btn-light btn-active-light-primary btn-sm"
                                                data-bs-toggle="modal"
                                                data-bs-target="#kt_modal_edit_user_{{ $user->id }}">
                                                <i class="ki-outline ki-pencil fs-5"></i> Edit
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="12" class="text-center py-10">
                                            <div class="text-gray-600">No users found</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ===================== SEMUA MODAL ===================== --}}
    @foreach($users as $user)
        @php
            $wdTransactions      = $user->transactions->whereIn('type', ['withdrawal', 'deduction']);
            $depositTransactions = $user->transactions->where('type', 'deposit');

            $level1Users = $user->referrals->map->referred->filter();
            $level2Users = $user->referrals->flatMap(function ($ref) {
                return $ref->referred?->referrals?->map->referred ?? collect();
            })->filter();
            $level3Users = $user->referrals->flatMap(function ($ref) {
                return $ref->referred?->referrals?->flatMap(function ($ref2) {
                    return $ref2->referred?->referrals?->map->referred ?? collect();
                }) ?? collect();
            })->filter();

            $totalTeam  = $level1Users->count() + $level2Users->count() + $level3Users->count();
            $activeTeam = $level1Users->where('is_verified', true)->count()
                        + $level2Users->where('is_verified', true)->count()
                        + $level3Users->where('is_verified', true)->count();

            $depositL1   = $level1Users->flatMap->transactions->where('type', 'deposit')->where('status', 'approved')->sum('total_amount');
            $depositL2   = $level2Users->flatMap->transactions->where('type', 'deposit')->where('status', 'approved')->sum('total_amount');
            $depositL3   = $level3Users->flatMap->transactions->where('type', 'deposit')->where('status', 'approved')->sum('total_amount');
            $teamDeposit = $depositL1 + $depositL2 + $depositL3;
        @endphp

        {{-- ===== Modal Edit User ===== --}}
        <div class="modal fade" id="kt_modal_edit_user_{{ $user->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-650px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">Edit User</h2>
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="ki-outline ki-cross fs-1"></i>
                        </div>
                    </div>
                    <div class="modal-body px-5 my-7">
                        <form action="{{ route('admin.user.update', $user->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Full Name</label>
                                <input type="text" name="name"
                                    class="form-control form-control-solid @error('name') is-invalid @enderror"
                                    value="{{ old('name', $user->name) }}" required />
                                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Username</label>
                                <input type="text" name="username"
                                    class="form-control form-control-solid @error('username') is-invalid @enderror"
                                    value="{{ old('username', $user->username) }}" required />
                                @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Email</label>
                                <input type="email" name="email"
                                    class="form-control form-control-solid @error('email') is-invalid @enderror"
                                    value="{{ old('email', $user->email) }}" required />
                                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="fv-row mb-7">
                                <label class="required fw-semibold fs-6 mb-2">Phone</label>
                                <input type="text" name="phone"
                                    class="form-control form-control-solid @error('phone') is-invalid @enderror"
                                    value="{{ old('phone', $user->phone) }}" required />
                                @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">Target Volume</label>
                                <input type="text" class="form-control form-control-solid"
                                    value="{{ number_format($user->target_volume, 2) }} USDT" disabled />
                                <div class="form-text text-muted">Target volume tidak bisa diubah dari sini.</div>
                            </div>
                            <div class="fv-row mb-7">
                                <label class="fw-semibold fs-6 mb-2">Achieved Volume</label>
                                <input type="number" name="achieved_volume" step="0.01" min="0"
                                    class="form-control form-control-solid @error('achieved_volume') is-invalid @enderror"
                                    value="{{ old('achieved_volume', $user->achieved_volume) }}" />
                                @error('achieved_volume')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                <div class="form-text text-muted">Sisa: {{ number_format($user->getRemainingVolume(), 2) }} USDT</div>
                            </div>
                            <div class="text-center pt-10">
                                <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== Modal Deposit ===== --}}
        <div class="modal fade" id="kt_modal_deposit_{{ $user->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-750px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">Deposit History — {{ $user->name }}</h2>
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="ki-outline ki-cross fs-1"></i>
                        </div>
                    </div>
                    <div class="modal-body px-5 my-5">
                        @if($depositTransactions->isEmpty())
                            <div class="text-center text-muted py-5">No deposit history</div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle fs-6 gy-3">
                                    <thead>
                                        <tr class="text-muted fw-bold fs-7 text-uppercase bg-light">
                                            <th>Reference</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($depositTransactions as $trx)
                                            <tr>
                                                <td class="fw-semibold">{{ $trx->reference }}</td>
                                                <td class="fw-bold text-success">+{{ number_format($trx->total_amount, 2) }} USDT</td>
                                                <td>
                                                    <span class="badge badge-light-{{ $trx->status === 'approved' ? 'success' : ($trx->status === 'pending' ? 'warning' : 'danger') }}">
                                                        {{ ucfirst($trx->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $trx->created_at->format('d M Y, h:i a') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== Modal WD ===== --}}
        <div class="modal fade" id="kt_modal_wd_{{ $user->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-750px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">WD History — {{ $user->name }}</h2>
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="ki-outline ki-cross fs-1"></i>
                        </div>
                    </div>
                    <div class="modal-body px-5 my-5">
                        @if($wdTransactions->isEmpty())
                            <div class="text-center text-muted py-5">No withdrawal history</div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-bordered align-middle fs-6 gy-3">
                                    <thead>
                                        <tr class="text-muted fw-bold fs-7 text-uppercase bg-light">
                                            <th>Reference</th>
                                            <th>Type</th>
                                            <th>Amount</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($wdTransactions as $trx)
                                            <tr>
                                                <td class="fw-semibold">{{ $trx->reference }}</td>
                                                <td>
                                                    <span class="badge badge-light-{{ $trx->type === 'withdrawal' ? 'primary' : 'danger' }}">
                                                        {{ ucfirst($trx->type) }}
                                                    </span>
                                                </td>
                                                <td class="fw-bold">{{ number_format($trx->total_amount, 2) }} USDT</td>
                                                <td>
                                                    <span class="badge badge-light-{{ $trx->status === 'approved' ? 'success' : ($trx->status === 'pending' ? 'warning' : 'danger') }}">
                                                        {{ ucfirst($trx->status) }}
                                                    </span>
                                                </td>
                                                <td>{{ $trx->created_at->format('d M Y, h:i a') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- ===== Modal Team ===== --}}
        <div class="modal fade" id="kt_modal_team_{{ $user->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered mw-900px">
                <div class="modal-content">
                    <div class="modal-header">
                        <h2 class="fw-bold">
                            <i class="ki-outline ki-people fs-2 me-2 text-primary"></i>
                            Team — {{ $user->name }}
                        </h2>
                        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal">
                            <i class="ki-outline ki-cross fs-1"></i>
                        </div>
                    </div>
                    <div class="modal-body px-5 my-5">

                        {{-- Summary Cards --}}
                        <div class="row g-4 mb-7">
                            <div class="col-6 col-md-3">
                                <div class="border border-dashed border-gray-300 rounded text-center px-3 py-4">
                                    <div class="fs-2 fw-bold text-gray-800">{{ $totalTeam }}</div>
                                    <div class="fs-7 text-muted">Total Member</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="border border-dashed border-success rounded text-center px-3 py-4">
                                    <div class="fs-2 fw-bold text-success">{{ $activeTeam }}</div>
                                    <div class="fs-7 text-muted">Member Aktif</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="border border-dashed border-primary rounded text-center px-3 py-4">
                                    <div class="fs-2 fw-bold text-primary">{{ $user->direct_referral_count }}</div>
                                    <div class="fs-7 text-muted">Direct Referral</div>
                                </div>
                            </div>
                            <div class="col-6 col-md-3">
                                <div class="border border-dashed border-info rounded text-center px-3 py-4">
                                    <div class="fs-3 fw-bold text-info">{{ number_format($teamDeposit, 2) }}</div>
                                    <div class="fs-7 text-muted">Total Deposit Tim</div>
                                </div>
                            </div>
                        </div>

                        {{-- Deposit per Level --}}
                        <div class="d-flex gap-3 mb-7 flex-wrap">
                            <div class="d-flex align-items-center bg-light-primary rounded px-4 py-2 flex-fill">
                                <span class="badge badge-primary me-2">L1</span>
                                <div>
                                    <div class="fw-bold text-gray-800">{{ $level1Users->count() }} member</div>
                                    <div class="fs-8 text-muted">Deposit: {{ number_format($depositL1, 2) }} USDT</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center bg-light-info rounded px-4 py-2 flex-fill">
                                <span class="badge badge-info me-2">L2</span>
                                <div>
                                    <div class="fw-bold text-gray-800">{{ $level2Users->count() }} member</div>
                                    <div class="fs-8 text-muted">Deposit: {{ number_format($depositL2, 2) }} USDT</div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center bg-light-warning rounded px-4 py-2 flex-fill">
                                <span class="badge badge-warning me-2">L3</span>
                                <div>
                                    <div class="fw-bold text-gray-800">{{ $level3Users->count() }} member</div>
                                    <div class="fs-8 text-muted">Deposit: {{ number_format($depositL3, 2) }} USDT</div>
                                </div>
                            </div>
                        </div>

                        @if($level1Users->isEmpty())
                            <div class="text-center text-muted py-5">
                                <i class="ki-outline ki-people fs-3x text-muted mb-3 d-block"></i>
                                <div>Belum ada anggota tim</div>
                            </div>
                        @else
                            {{-- Level 1 --}}
                            <div class="mb-6">
                                <div class="d-flex align-items-center mb-3">
                                    <span class="badge badge-primary fs-7 me-2">L1</span>
                                    <h5 class="fw-bold text-primary m-0">Direct Referrals</h5>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle fs-7 gy-2">
                                        <thead>
                                            <tr class="text-muted fw-bold text-uppercase bg-light">
                                                <th>Name / Email</th>
                                                <th>Username</th>
                                                <th>Status</th>
                                                <th class="text-end">Deposit</th>
                                                <th>Joined</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($level1Users as $ref)
                                                @php
                                                    $refDeposit = $ref->transactions
                                                        ->where('type', 'deposit')
                                                        ->where('status', 'approved')
                                                        ->sum('total_amount');
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <div class="fw-semibold text-gray-800">{{ $ref->name }}</div>
                                                        <div class="text-muted fs-8">{{ $ref->email }}</div>
                                                    </td>
                                                    <td>{{ $ref->username }}</td>
                                                    <td>
                                                        <span class="badge badge-light-{{ $ref->is_verified ? 'success' : 'warning' }}">
                                                            {{ $ref->is_verified ? 'Verified' : 'Unverified' }}
                                                        </span>
                                                    </td>
                                                    <td class="text-end fw-bold {{ $refDeposit > 0 ? 'text-success' : 'text-muted' }}">
                                                        {{ number_format($refDeposit, 2) }} USDT
                                                    </td>
                                                    <td>{{ $ref->created_at->format('d M Y') }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            {{-- Level 2 --}}
                            @if($level2Users->isNotEmpty())
                                <div class="mb-6">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="badge badge-info fs-7 me-2">L2</span>
                                        <h5 class="fw-bold text-info m-0">Level 2</h5>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle fs-7 gy-2">
                                            <thead>
                                                <tr class="text-muted fw-bold text-uppercase bg-light">
                                                    <th>Name / Email</th>
                                                    <th>Username</th>
                                                    <th>Referred By</th>
                                                    <th>Status</th>
                                                    <th class="text-end">Deposit</th>
                                                    <th>Joined</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($level2Users as $ref)
                                                    @php
                                                        $refDeposit = $ref->transactions
                                                            ->where('type', 'deposit')
                                                            ->where('status', 'approved')
                                                            ->sum('total_amount');
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            <div class="fw-semibold text-gray-800">{{ $ref->name }}</div>
                                                            <div class="text-muted fs-8">{{ $ref->email }}</div>
                                                        </td>
                                                        <td>{{ $ref->username }}</td>
                                                        <td>
                                                            <span class="badge badge-light-primary">
                                                                {{ $ref->usedReferral?->referrer?->name ?? '-' }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-light-{{ $ref->is_verified ? 'success' : 'warning' }}">
                                                                {{ $ref->is_verified ? 'Verified' : 'Unverified' }}
                                                            </span>
                                                        </td>
                                                        <td class="text-end fw-bold {{ $refDeposit > 0 ? 'text-success' : 'text-muted' }}">
                                                            {{ number_format($refDeposit, 2) }} USDT
                                                        </td>
                                                        <td>{{ $ref->created_at->format('d M Y') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif

                            {{-- Level 3 --}}
                            @if($level3Users->isNotEmpty())
                                <div class="mb-6">
                                    <div class="d-flex align-items-center mb-3">
                                        <span class="badge badge-warning fs-7 me-2">L3</span>
                                        <h5 class="fw-bold text-warning m-0">Level 3</h5>
                                    </div>
                                    <div class="table-responsive">
                                        <table class="table table-bordered align-middle fs-7 gy-2">
                                            <thead>
                                                <tr class="text-muted fw-bold text-uppercase bg-light">
                                                    <th>Name / Email</th>
                                                    <th>Username</th>
                                                    <th>Referred By</th>
                                                    <th>Status</th>
                                                    <th class="text-end">Deposit</th>
                                                    <th>Joined</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($level3Users as $ref)
                                                    @php
                                                        $refDeposit = $ref->transactions
                                                            ->where('type', 'deposit')
                                                            ->where('status', 'approved')
                                                            ->sum('total_amount');
                                                    @endphp
                                                    <tr>
                                                        <td>
                                                            <div class="fw-semibold text-gray-800">{{ $ref->name }}</div>
                                                            <div class="text-muted fs-8">{{ $ref->email }}</div>
                                                        </td>
                                                        <td>{{ $ref->username }}</td>
                                                        <td>
                                                            <span class="badge badge-light-info">
                                                                {{ $ref->usedReferral?->referrer?->name ?? '-' }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <span class="badge badge-light-{{ $ref->is_verified ? 'success' : 'warning' }}">
                                                                {{ $ref->is_verified ? 'Verified' : 'Unverified' }}
                                                            </span>
                                                        </td>
                                                        <td class="text-end fw-bold {{ $refDeposit > 0 ? 'text-success' : 'text-muted' }}">
                                                            {{ number_format($refDeposit, 2) }} USDT
                                                        </td>
                                                        <td>{{ $ref->created_at->format('d M Y') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>

    @endforeach
    {{-- ===================== END MODAL ===================== --}}

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#search-user').on('keyup', function () {
                    const value = $(this).val().toLowerCase();
                    $('#kt_table_users tbody tr').filter(function () {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                    });
                });
                setTimeout(function () {
                    $('.alert').fadeOut('slow');
                }, 5000);
            });
        </script>
    @endpush
@endsection