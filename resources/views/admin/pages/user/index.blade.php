@extends('admin.layouts.app')
@section('content')
    <!--begin::Toolbar-->
    <div id="kt_app_toolbar" class="app-toolbar pt-5 pt-lg-10">
        <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack flex-wrap">
            <div class="app-toolbar-wrapper d-flex flex-stack flex-wrap gap-4 w-100">
                <div class="page-title d-flex flex-column justify-content-center gap-1 me-3">
                    <h1 class="page-heading d-flex flex-column justify-content-center text-gray-900 fw-bold fs-3 m-0">Users
                        List</h1>
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

            <!--begin::Card-->
            <div class="card">
                <!--begin::Card header-->
                <div class="card-header border-0 pt-6">
                    <div class="card-title">
                        <div class="d-flex align-items-center position-relative my-1">
                            <i class="ki-outline ki-magnifier fs-3 position-absolute ms-5"></i>
                            <input type="text" id="search-user" class="form-control form-control-solid w-250px ps-13"
                                placeholder="Search user" />
                        </div>
                    </div>
                </div>
                <!--end::Card header-->

                <!--begin::Card body-->
                <div class="card-body py-4">
                    <!--begin::Table-->
                    <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_table_users">
                        <thead>
                            <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-125px">User</th>
                                <th class="min-w-125px">Username</th>
                                <th class="min-w-125px">Phone</th>
                                <th class="min-w-125px">Verified Data</th>
                                <th class="min-w-125px">Joined Date</th>
                                <th class="text-end min-w-100px">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 fw-semibold">
                            @forelse($users as $user)
                                <tr>
                                    <td class="d-flex align-items-center">
                                        <div class="d-flex flex-column">
                                            <span class="text-gray-800 mb-1">{{ $user->name }}</span>
                                            <span class="text-muted">{{ $user->email }}</span>
                                        </div>
                                    </td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->phone }}</td>
                                    <td>
                                        @if ($user->is_verified)
                                            <span class="badge badge-light-success">Verified</span>
                                        @else
                                            <span class="badge badge-light-warning">Unverified</span>
                                        @endif
                                    </td>
                                    <td>{{ $user->created_at->format('d M Y, h:i a') }}</td>
                                    <td class="text-end">
                                        <button class="btn btn-light btn-active-light-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#kt_modal_edit_user_{{ $user->id }}">
                                            <i class="ki-outline ki-pencil fs-5"></i> Edit
                                        </button>
                                    </td>
                                </tr>

                                <!--begin::Modal - Edit User-->
                                <div class="modal fade" id="kt_modal_edit_user_{{ $user->id }}" tabindex="-1"
                                    aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered mw-650px">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h2 class="fw-bold">Edit User</h2>
                                                <div class="btn btn-icon btn-sm btn-active-icon-primary"
                                                    data-bs-dismiss="modal">
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
                                                            placeholder="Full name" value="{{ old('name', $user->name) }}"
                                                            required />
                                                        @error('name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="fv-row mb-7">
                                                        <label class="required fw-semibold fs-6 mb-2">Username</label>
                                                        <input type="text" name="username"
                                                            class="form-control form-control-solid @error('username') is-invalid @enderror"
                                                            placeholder="Username"
                                                            value="{{ old('username', $user->username) }}" required />
                                                        @error('username')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="fv-row mb-7">
                                                        <label class="required fw-semibold fs-6 mb-2">Email</label>
                                                        <input type="email" name="email"
                                                            class="form-control form-control-solid @error('email') is-invalid @enderror"
                                                            placeholder="example@domain.com"
                                                            value="{{ old('email', $user->email) }}" required />
                                                        @error('email')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="fv-row mb-7">
                                                        <label class="required fw-semibold fs-6 mb-2">Phone</label>
                                                        <input type="text" name="phone"
                                                            class="form-control form-control-solid @error('phone') is-invalid @enderror"
                                                            placeholder="08123456789"
                                                            value="{{ old('phone', $user->phone) }}" required />
                                                        @error('phone')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>

                                                    <div class="text-center pt-10">
                                                        <button type="button" class="btn btn-light me-3"
                                                            data-bs-dismiss="modal">Cancel</button>
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end::Modal - Edit User-->
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-10">
                                        <div class="text-gray-600">No users found</div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <!--end::Table-->

                   

                           

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
                // Search functionality
                $('#search-user').on('keyup', function() {
                    const value = $(this).val().toLowerCase();
                    $('#kt_table_users tbody tr').filter(function() {
                        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
                    });
                });

                // Auto hide alert after 5 seconds
                setTimeout(function() {
                    $('.alert').fadeOut('slow');
                }, 5000);
            });
        </script>
    @endpush
@endsection