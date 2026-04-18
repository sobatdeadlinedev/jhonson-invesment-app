@extends('admin.layouts.app')
@section('content')
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar pt-5 pt-lg-10">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack flex-wrap">
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">
                        User Level Management</h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('admin.dashboard.index') }}" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-500 w-5px h-2px"></span>
                        </li>
                        <li class="breadcrumb-item text-muted">User Level</li>
                    </ul>
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
                            <input type="text" id="search-user" class="form-control form-control-solid w-250px ps-13"
                                placeholder="Cari user..." />
                        </div>
                    </div>
                    <div class="card-toolbar">
                        <span class="text-muted fs-7">
                            Level manual tidak mengganti level referral. Keduanya tampil di member view.
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
                                    <th class="min-w-100px">Direct</th>
                                    <th class="min-w-125px">Level Manual</th>
                                    <th class="min-w-175px">Di-set Oleh</th>
                                    <th class="text-end min-w-100px">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-600 fw-semibold">
                                @forelse($users as $user)
                                    <tr>
                                        <td>
                                            <div class="d-flex flex-column">
                                                <span class="text-gray-800 fw-bold mb-1">{{ $user->name }}</span>
                                                <span class="text-muted fs-7">{{ $user->email }}</span>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-primary">{{ $user->refferal_code }}</span>
                                        </td>
                                        <td>
                                            <span class="badge badge-light-info">{{ $user->referrals_count }}</span>
                                        </td>
                                        <td>
                                            @if($user->userLevel)
                                                @php
                                                    $lvl = $user->userLevel->level;
                                                    $colors = [1=>'success',2=>'info',3=>'warning',4=>'primary',5=>'danger'];
                                                    $color = $colors[$lvl] ?? 'secondary';
                                                @endphp
                                                <span class="badge badge-light-{{ $color }} fs-7 fw-bold">
                                                    Level {{ $lvl }}
                                                </span>
                                                @if($user->userLevel->note)
                                                    <div class="text-muted fs-8 mt-1">{{ $user->userLevel->note }}</div>
                                                @endif
                                            @else
                                                <span class="text-muted fs-7">— Belum di-set</span>
                                            @endif
                                        </td>
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
                                        <td class="text-end">
                                            <!--begin::Set Level Button-->
                                            <button class="btn btn-sm btn-light btn-active-light-primary me-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#modal_set_level_{{ $user->id }}">
                                                <i class="ki-outline ki-pencil fs-5"></i>
                                                {{ $user->userLevel ? 'Edit' : 'Set Level' }}
                                            </button>

                                            <!--begin::Remove Button-->
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
                                        <td colspan="6" class="text-center py-10">
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

    <!--begin::Modals-->
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
                            <!--begin::User Info-->
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
                            <!--end::User Info-->

                            <!--begin::Level Select-->
                            <div class="mb-5">
                                <label class="form-label fw-semibold required">Level</label>
                                <select name="level" class="form-select form-select-solid" required>
                                    <option value="">— Pilih Level —</option>
                                    @for($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}"
                                            {{ $user->userLevel?->level == $i ? 'selected' : '' }}>
                                            Level {{ $i }}
                                        </option>
                                    @endfor
                                </select>
                                <div class="form-text text-muted">
                                    Level ini akan ditampilkan di dashboard member sebagai badge tambahan.
                                </div>
                            </div>
                            <!--end::Level Select-->

                            <!--begin::Note-->
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Catatan (opsional)</label>
                                <textarea name="note" class="form-control form-control-solid" rows="2"
                                    placeholder="Alasan pemberian level...">{{ $user->userLevel?->note }}</textarea>
                            </div>
                            <!--end::Note-->
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
    <!--end::Modals-->

    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#search-user').on('keyup', function () {
                    const val = $(this).val().toLowerCase();
                    $('#kt_table_user_levels tbody tr').filter(function () {
                        $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1);
                    });
                });
            });
        </script>
    @endpush
@endsection