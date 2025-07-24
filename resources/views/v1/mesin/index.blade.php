@extends('layout.master')
@section('title')
    Dashboard
@endsection
@section('main-content')
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        Users Manage
                    </h1>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="#" class="text-muted text-hover-primary">Home</a>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">Admin</li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>
                        <!--end::Item-->
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">Users Manage</li>
                        <!--end::Item-->
                    </ul>
                    <!--end::Breadcrumb-->
                </div>
                <!--end::Page title-->
            </div>
            <!--end::Toolbar container-->
        </div>
        <!--end::Toolbar-->

        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid d-flex flex-column flex-column-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-title"></div>
                    <div class="card-toolbar">
                        <button class="btn btn-primary" onclick="addRuang()">
                            <i class="ki-duotone ki-plus fs-2"></i>
                            Add New
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="card-content">
                        <div class="d-flex align-items-center position-relative my-5">
                            <span class="svg-icon position-absolute ms-4">
                                <i class="ki-duotone ki-magnifier fs-1">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                            <input type="text" id="search_dt" class="form-control border border-2 w-250px ps-14"
                                placeholder="Search User" />
                        </div>
                        <table id="dt_user" class="table table-bordered align-middle table-row-dashed fs-6 gy-5">
                            <thead>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th style="width: 50px;">ID</th>
                                    <th>Employe ID</th>
                                    <th>Employe Group</th>
                                    <th>Nama User</th>
                                    <th>Email</th>
                                    <th>Job Level</th>
                                    <th>Line</th>
                                    <th>Group Name</th>
                                    <th>Action</th>
                            </thead>
                            <tbody></tbody>
                            <tfoot>
                                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                    <th style="width: 50px;">ID</th>
                                    <th>Employe ID</th>
                                    <th>Employe Group</th>
                                    <th>Nama User</th>
                                    <th>Email</th>
                                    <th>Job Level</th>
                                    <th>Line</th>
                                    <th>Group Name</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                        </table>

                        <!--begin::modal user-->
                        <div class="modal fade" tabindex="-1" id="modalUser">
                            <form id="formUser">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title" id="titleModalUser"></h3>

                                            <!--begin::Close-->
                                            <div class="btn btn-icon btn-sm ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                <i class="fas fa-times text-dark"></i>
                                            </div>
                                            <!--end::Close-->
                                        </div>
                                        
                                        <div class="modal-body" id="bodyModalUser"></div>

                                        <div class="modal-footer">
                                            <div class="me-auto">
                                                <small class="fst-italic"><span class="text-danger">*Tidak boleh kosong</span></small>
                                            </div>
                                            <button type="button" class="btn btn-sm btn-light btn-action" type="button" data-bs-dismiss="modal" id="btnBatal" onclick="" style="margin-right: 10px;">
                                                Batal
                                            </button>
                                            <button type="submit" class="btn btn-sm btn-primary btn-action" id="btnSimpan" onclick="">
                                                Simpan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <!--end::modal user -->

                    </div>
                </div>
            </div>
        </div>
        <!--end::Content container-->

    </div>
    <!--end::Content-->
@endsection

@section('scripts')
    <script src="{{asset("/assets/plugins/custom/datatables/datatables.bundle.js")}}"></script>
    <script>

        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        const _URL = "{{ route('admin.user.getDataTableUser') }}";

        $(document).ready(function () {
            $('.page-loading').fadeIn();
            setTimeout(function () {
                $('.page-loading').fadeOut();
            }, 1000); // Adjust the timeout duration as needed

            let DT = $("#dt_user").DataTable({
                order: [[3, 'asc']],
                processing: false,
                serverSide: true,
                ajax: {
                    url: _URL,
                },
                columns: [
                    { data: "DT_RowIndex" },
                    { data: "employeId" },
                    { data: "empTypeGroup" },
                    { data: "fullname" },
                    { data: "email" },
                    { data: "jobLvl" },
                    { data: "line_name", name: "line.name" },
                    { data: "groupName"},
                    {
                        data: "action",
                        orderable: false,
                        searchable: false,
                    },
                ],
                columnDefs: [
                    {
                        targets: 0,
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1; // Calculate the row index
                        },
                    },
                ],
            });

            $('#search_dt').on('keyup', function () {
                DT.search(this.value).draw();
            });
        });

        // let isEdit_temp = 0;
        // let id_temp = "";

        function addRuang() {
            // isEdit_temp = 0;
            $('#formUser')[0].reset();  // clear the form
            $('#titleModalUser').html('Add New User');
            $('#formUser').find('input[fullname="_method"]').remove();

            $('#formUser').attr('action', "{{ route('admin.user.store') }}");
            $('#formUser').attr('method', 'POST');

            $('#bodyModalUser').html(`
                <div class="row align-items-center mb-3">
                    <label for="fullname" class="col-sm-4 col-form-label">Nama User<span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="fullname" name="fullname" required placeholder="Masukkan nama lengkap">
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <label for="employeId" class="col-sm-4 col-form-label">Employe ID<span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="employeId" name="employeId" required placeholder="Masukkan ID karyawan">
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <label for="empTypeGroup" class="col-sm-4 col-form-label">Employe Group<span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <select class="form-control form-select" id="empTypeGroup" name="empTypeGroup" required>
                            <option value="">-- Select Employe Group --</option>
                            <option value="PKWTT">PKWTT</option>
                            <option value="Tetap">Tetap</option>
                        </select>
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <label for="email" class="col-sm-4 col-form-label">Email<span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="email" name="email" required placeholder="Masukkan email">
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <label for="email_backup" class="col-sm-4 col-form-label">Email Backup</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="email_backup" name="email_backup" placeholder="Masukkan email backup">
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <label for="phone" class="col-sm-4 col-form-label">Phone</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Masukkan nomor telepon">
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <label for="jobLvl" class="col-sm-4 col-form-label">Job Level<span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <select class="form-control form-select" id="jobLvl" name="jobLvl" required>
                            <option value="">-- Select Job Level --</option>
                            <option value="PKWTT">PKWTT</option>
                            <option value="Tetap">Tetap</option>
                        </select>
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <label for="line_id" class="col-sm-4 col-form-label">Line</label>
                    <div class="col-sm-8">
                        <select class="form-control form-select" id="line_id" name="line_id">
                            <option value="">-- Select Line --</option>
                            <option value="PKWTT">PKWTT</option>
                            <option value="Tetap">Tetap</option>
                        </select>
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <label for="groupName" class="col-sm-4 col-form-label">Group Name<span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <select class="form-control form-select" id="groupName" name="groupName" required>
                            <option value="">-- Select Group --</option>
                            <option value="PKWTT">PKWTT</option>
                            <option value="Tetap">Tetap</option>
                        </select>
                    </div>
                </div>
            `);

            $('#modalUser').modal('show');
        }

        function editRuang(id) {
            // isEdit_temp = 1;
            id_temp = id;
            $('#formUser')[0].reset();  // clear the form
            $('#titleModalUser').html('Edit User');
            $('#formUser').find('input[name="_method"]').remove();

            $('#formUser').attr('action', `{{ url('admin/user/update') }}/${id}`);
            $('#formUser').attr('method', 'POST');

            $('#formUser').append('<input type="hidden" name="_method" value="PUT">');

            $('#bodyModalUser').html(`
                <div class="row align-items-center mb-3">
                    <label for="fullname" class="col-sm-4 col-form-label">Nama User<span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="fullname" name="fullname" required>
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <label for="compCode" class="col-sm-4 col-form-label">Company Code<span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="compCode" name="compCode" required>
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <label for="employeId" class="col-sm-4 col-form-label">Employe ID<span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="employeId" name="employeId" required>
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <label for="empTypeGroup" class="col-sm-4 col-form-label">Employe Group<span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <select class="form-control form-select" id="empTypeGroup" name="empTypeGroup" required>
                            <option value="">-- Select Employe Group --</option>
                            <option value="PKWTT">PKWTT</option>
                            <option value="Tetap">Tetap</option>
                        </select>
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <label for="email" class="col-sm-4 col-form-label">Email<span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="email" name="email" required>
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <label for="email_backup" class="col-sm-4 col-form-label">Email Backup</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="email_backup" name="email_backup" placeholder="Masukkan email backup">
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <label for="phone" class="col-sm-4 col-form-label">Phone</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="phone" name="phone" placeholder="Masukkan nomor telepon">
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <label for="jobLvl" class="col-sm-4 col-form-label">Job Level<span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <select class="form-control form-select" id="jobLvl" name="jobLvl" required>
                            <option value="">-- Select Job Level --</option>
                            <option value="PKWTT">PKWTT</option>
                            <option value="Tetap">Tetap</option>
                        </select>
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <label for="jobTitle" class="col-sm-4 col-form-label">Job Title<span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="jobTitle" name="jobTitle" required>
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <label for="line_id" class="col-sm-4 col-form-label">Line</label>
                    <div class="col-sm-8">
                        <select class="form-control form-select" id="line_id" name="line_id">
                            <option value="">-- Select Line --</option>
                            <option value="PKWTT">PKWTT</option>
                            <option value="Tetap">Tetap</option>
                        </select>
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <label for="groupKode" class="col-sm-4 col-form-label">Group Kode<span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="groupKode" name="groupKode" required>
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <label for="groupName" class="col-sm-4 col-form-label">Group Name<span class="text-danger">*</span></label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="groupName" name="groupName" required>
                    </div>
                </div>
            `);

            // Ambil data dari server untuk diisikan ke form
            let url = `{{ url('admin/user/edit') }}/${id}`;
            $.get(url, function (response) {
                let user = response.user;
                let roles = response.roles;
                let lines = response.lines;

                // --- Mengisi field input biasa ---
                $('#fullname').val(user.fullname);
                $('#compCode').val(user.compCode);
                $('#email').val(user.email);
                $('#email_backup').val(user.email_backup);
                $('#phone').val(user.phone);
                $('#employeId').val(user.employeId);
                $('#empTypeGroup').val(user.empTypeGroup);
                $('#jobTitle').val(user.jobTitle);
                $('#groupKode').val(user.groupKode);
                $('#groupName').val(user.groupName);

                // --- Mengisi dan memilih dropdown Roles ---
                let rolesSelect = $('#jobLvl');
                rolesSelect.empty().append('<option value="">-- Select Job Level --</option>');
                roles.forEach(function(role) {
                    let selected = (user.jobLvl == role.name) ? 'selected' : '';
                    rolesSelect.append(`<option value="${role.name}" ${selected}>${role.name}</option>`);
                });

                // --- Mengisi dan memilih dropdown Lines ---
                let linesSelect = $('#line_id');
                linesSelect.empty().append('<option value="">-- Select Line --</option>');
                lines.forEach(function(line) {
                    let selected = (user.line_id == line.id) ? 'selected' : '';
                    linesSelect.append(`<option value="${line.id}" ${selected}>${line.name}</option>`);
                });

                // Tampilkan modal setelah semuanya siap
                $('#modalUser').modal('show');
                
            });
        }

        function deleteRuang(id) {
            Swal.fire({
                text: "Are you sure you want to delete this User?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Yes, delete it!",
                cancelButtonText: "No, cancel!",
            }).then(function (result) {
                if (result.value) {
                    $.ajax({
                        url: "{{ route('admin.user.destroy') }}",
                        type: "POST",
                        data: {
                            id: id,
                            _token: "{{ csrf_token() }}",
                        },
                        success: function (response) {
                            $("#dt_user").DataTable().ajax.reload(null, false);
                            Swal.fire("Deleted!", response.message, "success");
                        },
                        error: function (xhr) {
                            Swal.fire("Error!", xhr.responseJSON.message, "error");
                        },
                    });
                } else if (result.dismiss === "cancel") {
                    Swal.fire("Cancelled", "Your data is safe :)", "error");
                }
            });
        }
    </script>

@endsection