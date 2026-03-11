@extends('admin.layouts.app')
@section('content')
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar pt-5 pt-lg-10">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack flex-wrap">
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
                        Account Verification List</h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard.index') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">Verification Management</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <!--end::Toolbar-->

    <!--begin::Content-->
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

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible d-flex align-items-center p-5 mb-10">
                    <i class="ki-outline ki-cross-circle fs-2hx text-danger me-4"></i>
                    <div class="d-flex flex-column">
                        <h4 class="mb-1 text-dark">Error</h4>
                        <span>{{ session('error') }}</span>
                    </div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!--begin::Card-->
            <div class="card">
                <!--begin::Card body-->
                <div class="card-body py-4">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_verifications">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-150px">User</th>
                                <th class="min-w-125px">Full Name</th>
                                <th class="min-w-125px">Identity Number</th>
                                <th class="min-w-100px">Identity Type</th>
                                <th class="min-w-100px">Status</th>
                                <th class="min-w-125px">Submitted Date</th>
                                <th class="text-end min-w-125px">Action</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            @forelse($verifications as $verification)
                                <tr>
                                    <td>
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-800 mb-1">{{ $verification->user->name }}</span>
                                            <span class="text-muted">{{ $verification->user->email }}</span>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="text-gray-800">{{ $verification->full_name }}</span>
                                    </td>
                                    <td>
                                        <span class="badge badge-light-primary">{{ $verification->identity_number }}</span>
                                    </td>
                                    <td>
                                        <span class="text-gray-800">{{ $verification->identity_type }}</span>
                                    </td>
                                    <td>
                                        @if ($verification->isVerified())
                                            <span class="badge badge-light-success">
                                                <i class="ki-outline ki-check-circle fs-5"></i> Verified
                                            </span>
                                        @else
                                            <span class="badge badge-light-warning">
                                                <i class="ki-outline ki-time fs-5"></i> Pending
                                            </span>
                                        @endif
                                    </td>
                                    <td>{{ $verification->submitted_at->format('d M Y, h:i a') }}</td>
                                    <td class="text-end">
                                        <a href="{{ route('admin.verification.show', $verification->id) }}"
                                            class="btn btn-light btn-active-light-primary btn-sm">
                                            <i class="ki-outline ki-eye fs-5"></i> Detail
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-10">
                                        <div class="text-gray-600">No verification requests found</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <!--end::Table-->

                    <!--begin::Pagination-->
<div class="d-flex flex-stack flex-wrap pt-10">
    <div class="fs-6 fw-semibold text-gray-700">
        Showing {{ $verifications->firstItem() }} to {{ $verifications->lastItem() }} of {{ $verifications->total() }} results
    </div>
    <ul class="pagination">
        {{-- Previous --}}
        <li class="page-item {{ $verifications->onFirstPage() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $verifications->previousPageUrl() }}">
                <i class="previous"></i>
            </a>
        </li>

        {{-- Page Numbers --}}
        @php
            $currentPage = $verifications->currentPage();
            $lastPage = $verifications->lastPage();
            $start = max(1, $currentPage - 2);
            $end = min($lastPage, $currentPage + 2);
        @endphp

        @if ($start > 1)
            <li class="page-item">
                <a class="page-link" href="{{ $verifications->url(1) }}">1</a>
            </li>
            @if ($start > 2)
                <li class="page-item disabled"><span class="page-link">...</span></li>
            @endif
        @endif

        @for ($i = $start; $i <= $end; $i++)
            <li class="page-item {{ $i == $currentPage ? 'active' : '' }}">
                <a class="page-link" href="{{ $verifications->url($i) }}">{{ $i }}</a>
            </li>
        @endfor

        @if ($end < $lastPage)
            @if ($end < $lastPage - 1)
                <li class="page-item disabled"><span class="page-link">...</span></li>
            @endif
            <li class="page-item">
                <a class="page-link" href="{{ $verifications->url($lastPage) }}">{{ $lastPage }}</a>
            </li>
        @endif

        {{-- Next --}}
        <li class="page-item {{ !$verifications->hasMorePages() ? 'disabled' : '' }}">
            <a class="page-link" href="{{ $verifications->nextPageUrl() }}">
                <i class="next"></i>
            </a>
        </li>
    </ul>
</div>
<!--end::Pagination-->
                </div>
                <!--end::Card body-->
            </div>
            <!--end::Card-->
        </div>
    </div>
    <!--end::Content-->

    @push('scripts')
        <script>
            $(document).ready(function() {
                // Auto hide alert after 5 seconds
                setTimeout(function() {
                    $('.alert').fadeOut('slow');
                }, 5000);
            });
        </script>
    @endpush
@endsection
