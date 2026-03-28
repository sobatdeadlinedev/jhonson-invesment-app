@extends('admin.layouts.app')
@section('content')
    <div id="kt_app_toolbar" class="app-toolbar pt-5 pt-lg-10">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack flex-wrap">
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
                        Withdrawal & Deduction List</h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard.index') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Withdrawal Management</li>
                    </ul>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deductionModal">
                        <i class="ki-outline ki-minus fs-2"></i>
                        <span class="d-none d-sm-inline">Deduct Balance</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-xxl">

            {{-- Alert Messages --}}
            @if (session('success'))
                <div class="alert alert-success d-flex align-items-center p-5 mb-10">
                    <i class="ki-outline ki-check-circle fs-2hx text-success me-4"></i>
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-success">Success</h4>
                        <span>{{ session('success') }}</span>
                    </div>
                    <button type="button"
                        class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
                        data-bs-dismiss="alert">
                        <i class="ki-outline ki-cross fs-1 text-success"></i>
                    </button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
                    <i class="ki-outline ki-information-5 fs-2hx text-danger me-4"></i>
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-danger">Error</h4>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button"
                        class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
                        data-bs-dismiss="alert">
                        <i class="ki-outline ki-cross fs-1 text-danger"></i>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger d-flex align-items-center p-5 mb-10">
                    <i class="ki-outline ki-information-5 fs-2hx text-danger me-4"></i>
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-danger">Validation Error</h4>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                    <button type="button"
                        class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn btn-icon ms-sm-auto"
                        data-bs-dismiss="alert">
                        <i class="ki-outline ki-cross fs-1 text-danger"></i>
                    </button>
                </div>
            @endif

            <div class="card">
                <div class="card-body p-0">
                    {{-- Desktop Table View --}}
                    <div class="table-responsive d-none d-lg-block">
                        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_withdrawals">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th class="min-w-150px ps-4">User</th>
                                    <th class="min-w-125px">Reference</th>
                                    <th class="min-w-100px">Type</th>
                                    <th class="min-w-100px">Balance Type</th>
                                    <th class="min-w-125px">Wallet</th>
                                    <th class="min-w-100px">Amount</th>
                                    <th class="min-w-100px">Fee</th>
                                    <th class="min-w-100px">Status</th>
                                    <th class="min-w-125px">Date</th>
                                    <th class="text-end min-w-100px pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                @forelse($withdrawals as $withdrawal)
                                    <tr>
                                        <td class="ps-4">
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-800 mb-1">{{ $withdrawal->user->name }}</span>
                                                <span class="text-muted">{{ $withdrawal->user->email }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-primary">{{ $withdrawal->reference }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-{{ $withdrawal->type_color }}">
                                                {{ ucfirst($withdrawal->type) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge badge-light-{{ $withdrawal->balance_type === 'trade' ? 'info' : 'success' }}">
                                                {{ ucfirst($withdrawal->balance_type) }}
                                            </span>
                                        </td>
                                        <td>
                                            @if ($withdrawal->wallet)
                                                <div class="d-flex flex-column">
                                                    <span class="text-gray-800">{{ $withdrawal->wallet->bank_name }}</span>
                                                    <span class="text-muted">{{ $withdrawal->wallet->account_number }}</span>
                                                </div>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-danger fw-bold">{{ number_format($withdrawal->amount, 2) }}
                                                USDT</span>
                                        </td>
                                        <td>
                                            @if ($withdrawal->type === 'withdrawal')
                                                <span
                                                    class="text-warning fw-bold">{{ number_format($withdrawal->withdrawal_fee, 2) }}
                                                    USDT</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="badge badge-light-{{ $withdrawal->status_color }}">
                                                {{ ucfirst($withdrawal->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $withdrawal->created_at->format('d M Y, h:i a') }}</td>
                                        <td class="text-end pe-4">
                                            @if ($withdrawal->type === 'withdrawal')
                                                <a href="{{ route('admin.withdrawal.show', $withdrawal->id) }}"
                                                    class="btn btn-light btn-active-light-primary btn-sm">
                                                    <i class="ki-outline ki-eye fs-5"></i> Detail
                                                </a>
                                            @else
                                                <span class="badge badge-light-warning">Manual Deduction</span>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-10">
                                            <div class="text-gray-600">No transactions found</div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    {{-- Mobile Card View --}}
                    <div class="d-lg-none">
                        @forelse($withdrawals as $withdrawal)
                            <div class="card border border-gray-300 mb-3 mx-3 mt-3">
                                <div class="card-body p-4">
                                    {{-- User Info --}}
                                    <div class="d-flex justify-content-between align-items-start mb-3">
                                        <div class="flex-grow-1">
                                            <div class="fw-bold text-gray-800 mb-1">{{ $withdrawal->user->name }}</div>
                                            <div class="text-muted fs-7">{{ $withdrawal->user->email }}</div>
                                        </div>
                                        <span class="badge badge-light-{{ $withdrawal->status_color }}">
                                            {{ ucfirst($withdrawal->status) }}
                                        </span>
                                    </div>

                                    {{-- Reference & Type --}}
                                    <div class="d-flex flex-wrap gap-2 mb-3">
                                        <span class="badge badge-light-primary">{{ $withdrawal->reference }}</span>
                                        <span class="badge badge-light-{{ $withdrawal->type_color }}">
                                            {{ ucfirst($withdrawal->type) }}
                                        </span>
                                        <span class="badge badge-light-{{ $withdrawal->balance_type === 'trade' ? 'info' : 'success' }}">
                                            {{ ucfirst($withdrawal->balance_type) }}
                                        </span>
                                    </div>

                                    {{-- Amount Info --}}
                                    <div class="d-flex justify-content-between align-items-center py-2 border-top border-gray-300">
                                        <span class="text-muted fs-7">Amount</span>
                                        <span class="text-danger fw-bold">{{ number_format($withdrawal->amount, 2) }} USDT</span>
                                    </div>

                                    @if ($withdrawal->type === 'withdrawal')
                                        <div class="d-flex justify-content-between align-items-center py-2 border-top border-gray-300">
                                            <span class="text-muted fs-7">Fee</span>
                                            <span class="text-warning fw-bold">{{ number_format($withdrawal->withdrawal_fee, 2) }} USDT</span>
                                        </div>
                                    @endif

                                    {{-- Wallet Info --}}
                                    @if ($withdrawal->wallet)
                                        <div class="d-flex justify-content-between align-items-center py-2 border-top border-gray-300">
                                            <span class="text-muted fs-7">Wallet</span>
                                            <div class="text-end">
                                                <div class="text-gray-800 fs-7">{{ $withdrawal->wallet->bank_name }}</div>
                                                <div class="text-muted fs-8">{{ $withdrawal->wallet->account_number }}</div>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Date --}}
                                    <div class="d-flex justify-content-between align-items-center py-2 border-top border-gray-300">
                                        <span class="text-muted fs-7">Date</span>
                                        <span class="text-gray-800 fs-7">{{ $withdrawal->created_at->format('d M Y, h:i a') }}</span>
                                    </div>

                                    {{-- Action Button --}}
                                    <div class="mt-3">
                                        @if ($withdrawal->type === 'withdrawal')
                                            <a href="{{ route('admin.withdrawal.show', $withdrawal->id) }}"
                                                class="btn btn-light btn-active-light-primary btn-sm w-100">
                                                <i class="ki-outline ki-eye fs-5"></i> View Detail
                                            </a>
                                        @else
                                            <div class="badge badge-light-warning w-100 py-2">Manual Deduction</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-10 px-4">
                                <div class="text-gray-600">No transactions found</div>
                            </div>
                        @endforelse
                    </div>

                   
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Deduct Balance -->
    <div class="modal fade" id="deductionModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <form action="{{ route('admin.withdrawal.deduction') }}" method="POST" id="deductionForm">
                    @csrf
                    <div class="modal-header">
                        <h2 class="fw-bold">Deduct Balance from User</h2>
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal">
                            <i class="ki-outline ki-cross fs-1"></i>
                        </div>
                    </div>

                    <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                        <!--begin::Form group - User-->
                        <div class="fv-row mb-7">
                            <label class="required fs-6 fw-semibold mb-2">Select User</label>
                            <select name="user_id" id="user_id" class="form-select form-select-solid" required>
                                <option value="">Choose user...</option>
                                @foreach ($members as $member)
                                    <option value="{{ $member->id }}" data-exchange="{{ $member->exchange_balance }}"
                                        data-trade="{{ $member->trade_balance }}">
                                        {{ $member->name }} ({{ $member->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!--begin::Balance Info-->
                        <div class="fv-row mb-7" id="balanceInfo" style="display: none;">
                            <div class="alert alert-info d-flex align-items-center p-4">
                                <i class="ki-outline ki-information-5 fs-2x text-info me-4 d-none d-sm-block"></i>
                                <div class="d-flex flex-column w-100">
                                    <h5 class="mb-2">Current Balance</h5>
                                    <div class="d-flex justify-content-between mb-1">
                                        <span class="fw-semibold">Exchange Balance:</span>
                                        <span class="text-info fw-bold" id="exchangeBalance">0.00 USDT</span>
                                    </div>
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-semibold">Trade Balance:</span>
                                        <span class="text-info fw-bold" id="tradeBalance">0.00 USDT</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!--begin::Form group - Balance Type-->
                        <div class="fv-row mb-7">
                            <label class="required fs-6 fw-semibold mb-2">Balance Type</label>
                            <select name="balance_type" id="balance_type" class="form-select form-select-solid" required>
                                <option value="">Choose balance type...</option>
                                <option value="exchange">Exchange Balance</option>
                                <option value="trade">Trade Balance</option>
                            </select>
                        </div>

                        <!--begin::Form group - Amount-->
                        <div class="fv-row mb-7">
                            <label class="required fs-6 fw-semibold mb-2">Amount (USDT)</label>
                            <input type="number" step="0.01" min="0.01" name="amount" id="amount"
                                class="form-control form-control-solid" placeholder="Enter amount" required />
                            <div class="form-text">Enter the amount you want to deduct</div>
                        </div>

                        <!--begin::Warning Alert-->
                        <div class="alert alert-warning d-flex align-items-center p-4 mb-5">
                            <i class="ki-outline ki-information-5 fs-2x text-warning me-4 d-none d-sm-block"></i>
                            <div class="d-flex flex-column">
                                <h5 class="mb-1">Warning</h5>
                                <span class="fs-7">This action will immediately deduct the balance from the selected user. Make sure the
                                    amount is correct.</span>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer flex-center">
                        <button type="button" class="btn btn-light me-3" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger" id="submitBtn">
                            <span class="indicator-label">Deduct Balance</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Auto hide alerts after 5 seconds (EXCLUDE modal alerts)
                setTimeout(function() {
                    const alerts = document.querySelectorAll('.alert:not(#deductionModal .alert)');
                    alerts.forEach(function(alert) {
                        if (alert.classList.contains('alert-success') ||
                            alert.classList.contains('alert-danger')) {
                            const bsAlert = new bootstrap.Alert(alert);
                            bsAlert.close();
                        }
                    });
                }, 5000);

                const userSelect = document.getElementById('user_id');
                const balanceTypeSelect = document.getElementById('balance_type');
                const amountInput = document.getElementById('amount');
                const balanceInfo = document.getElementById('balanceInfo');
                const exchangeBalanceSpan = document.getElementById('exchangeBalance');
                const tradeBalanceSpan = document.getElementById('tradeBalance');
                const deductionForm = document.getElementById('deductionForm');

                let currentExchangeBalance = 0;
                let currentTradeBalance = 0;

                // Show balance when user is selected
                userSelect.addEventListener('change', function() {
                    if (this.value) {
                        const selectedOption = this.options[this.selectedIndex];
                        currentExchangeBalance = parseFloat(selectedOption.dataset.exchange) || 0;
                        currentTradeBalance = parseFloat(selectedOption.dataset.trade) || 0;

                        exchangeBalanceSpan.textContent = currentExchangeBalance.toFixed(2) + ' USDT';
                        tradeBalanceSpan.textContent = currentTradeBalance.toFixed(2) + ' USDT';

                        balanceInfo.style.display = 'block';
                    } else {
                        balanceInfo.style.display = 'none';
                    }
                });

                // Handle form submission with confirmation
                deductionForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const balanceType = balanceTypeSelect.value;
                    const amount = parseFloat(amountInput.value) || 0;
                    const userName = userSelect.options[userSelect.selectedIndex].text;

                    const confirmMsg =
                        `Are you sure you want to deduct ${amount.toFixed(2)} USDT from ${userName}'s ${balanceType} balance?`;

                    if (confirm(confirmMsg)) {
                        this.submit();
                    }
                });

                // Reset form when modal closes
                document.getElementById('deductionModal').addEventListener('hidden.bs.modal', function() {
                    deductionForm.reset();
                    balanceInfo.style.display = 'none';
                    currentExchangeBalance = 0;
                    currentTradeBalance = 0;
                });
            });
        </script>
    @endpush
@endsection