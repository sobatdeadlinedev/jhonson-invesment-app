@extends('admin.layouts.app')
@section('content')
    <div id="kt_app_toolbar" class="app-toolbar pt-5 pt-lg-10">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack flex-wrap">
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
                        Deposit & Adjustment List</h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard.index') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Deposit Management</li>
                    </ul>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#adjustmentModal">
                        <i class="ki-outline ki-plus fs-2"></i>
                        Add Balance
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">
            <div class="card">
                <div class="card-body py-4">
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_deposits">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-150px">User</th>
                                <th class="min-w-125px">Reference</th>
                                <th class="min-w-100px">Type</th>
                                <th class="min-w-100px">Balance Type</th>
                                <th class="min-w-100px">Amount</th>
                                <th class="min-w-100px">Status</th>
                                <th class="min-w-125px">Date</th>
                                <th class="text-end min-w-125px">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            @forelse($deposits as $deposit)
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-800 mb-1">{{ $deposit->user->name }}</span>
                                            <span class="text-muted">{{ $deposit->user->email }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge badge-light-primary">{{ $deposit->reference }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-light-{{ $deposit->type_color }}">
                                            {{ ucfirst($deposit->type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge badge-light-{{ $deposit->balance_type === 'trade' ? 'info' : 'success' }}">
                                            {{ ucfirst($deposit->balance_type) }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-success fw-bold">{{ number_format($deposit->amount, 2) }} USDT</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-light-{{ $deposit->status_color }}">
                                            {{ ucfirst($deposit->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $deposit->created_at->format('d M Y, h:i a') }}</td>
                                    <td class="text-end">
                                        @if ($deposit->type === 'deposit')
                                            <a href="{{ route('admin.deposit.show', $deposit->id) }}"
                                                class="btn btn-light btn-active-light-primary btn-sm">
                                                <i class="ki-outline ki-eye fs-5"></i> Detail
                                            </a>
                                        @else
                                            <span class="badge badge-light-info">Manual Adjustment</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-10">
                                        <div class="text-gray-600">No transactions found</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{-- ── Pagination ── --}}
                    @if ($deposits->hasPages())
                        <div class="d-flex flex-stack flex-wrap pt-6 mt-4 border-top border-gray-200">

                            {{-- Left: info --}}
                            <div class="fs-6 fw-semibold text-gray-700">
                                Showing
                                <span class="text-gray-900 fw-bold">{{ $deposits->firstItem() }}</span>
                                –
                                <span class="text-gray-900 fw-bold">{{ $deposits->lastItem() }}</span>
                                of
                                <span class="text-gray-900 fw-bold">{{ number_format($deposits->total()) }}</span>
                                results
                            </div>

                            {{-- Right: page buttons --}}
                            <ul class="pagination mb-0">

                                {{-- Previous --}}
                                <li class="page-item {{ $deposits->onFirstPage() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $deposits->previousPageUrl() }}" aria-label="Previous">
                                        <i class="ki-outline ki-left fs-6"></i>
                                    </a>
                                </li>

                                {{-- Page Numbers --}}
                                @php
                                    $currentPage = $deposits->currentPage();
                                    $lastPage    = $deposits->lastPage();
                                    $window      = 2; // pages on each side of current

                                    $start = max(1, $currentPage - $window);
                                    $end   = min($lastPage, $currentPage + $window);
                                @endphp

                                {{-- First page + ellipsis --}}
                                @if ($start > 1)
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $deposits->url(1) }}">1</a>
                                    </li>
                                    @if ($start > 2)
                                        <li class="page-item disabled">
                                            <span class="page-link">…</span>
                                        </li>
                                    @endif
                                @endif

                                {{-- Window --}}
                                @for ($page = $start; $page <= $end; $page++)
                                    <li class="page-item {{ $page == $currentPage ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $deposits->url($page) }}">{{ $page }}</a>
                                    </li>
                                @endfor

                                {{-- Last page + ellipsis --}}
                                @if ($end < $lastPage)
                                    @if ($end < $lastPage - 1)
                                        <li class="page-item disabled">
                                            <span class="page-link">…</span>
                                        </li>
                                    @endif
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $deposits->url($lastPage) }}">{{ $lastPage }}</a>
                                    </li>
                                @endif

                                {{-- Next --}}
                                <li class="page-item {{ !$deposits->hasMorePages() ? 'disabled' : '' }}">
                                    <a class="page-link" href="{{ $deposits->nextPageUrl() }}" aria-label="Next">
                                        <i class="ki-outline ki-right fs-6"></i>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    @else
                        {{-- Still show total when only 1 page --}}
                        <div class="fs-6 fw-semibold text-gray-700 pt-4 mt-2 border-top border-gray-200">
                            Showing
                            <span class="text-gray-900 fw-bold">{{ $deposits->total() }}</span>
                            result{{ $deposits->total() !== 1 ? 's' : '' }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Modal Add Balance --}}
    <div class="modal fade" id="adjustmentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <form action="{{ route('admin.deposit.adjustment') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h2 class="fw-bold">Add Balance to User</h2>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                            <i class="ki-outline ki-cross fs-1"></i>
                        </div>
                    </div>

                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                        <div class="fv-row mb-7">
                            <label class="required fs-6 fw-semibold mb-2">Select User</label>
                            <select name="user_id" class="form-select form-select-solid" required>
                                <option value="">Choose user...</option>
                                @foreach ($members as $member)
                                    <option value="{{ $member->id }}">
                                        {{ $member->name }} ({{ $member->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="fv-row mb-7">
                            <label class="required fs-6 fw-semibold mb-2">Amount (USDT)</label>
                            <input type="number" step="0.01" min="0.01" name="amount"
                                class="form-control form-control-solid" placeholder="Enter amount" required />
                        </div>

                        <div class="fv-row mb-7">
                            <label class="required fs-6 fw-semibold mb-2">Balance Type</label>
                            <select name="balance_type" class="form-select form-select-solid" required>
                                <option value="exchange">Exchange Balance</option>
                                <option value="trade">Trade Balance</option>
                            </select>
                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">Add Balance</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection