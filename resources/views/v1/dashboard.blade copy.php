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
                        Dashboard
                    </h1>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="#" class="text-muted text-hover-primary">Home</a>
                        </li>
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
                
            @foreach ($lines as $line)
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">{{ $line->name }}</div>
                        <div class="card-toolbar">
                            <button class="btn btn-primary" onclick="addRuang()">
                                <i class="ki-duotone ki-plus fs-2"></i>
                                Add New
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="card-content">
                            <div class="d-flex flex-wrap gap-2 justify-content-start align-items-center py-5">

                                <div class="d-flex align-items-center">
                                    <label for="filterLine" class="form-label me-2 mb-0">Line:</label>
                                    <select id="filterLine" class="form-select form-select-sm w-150px">
                                        <option value="">Semua Line</option>
                                        @foreach ($all_line as $line)
                                            <option value="{{ $line->id }}">{{ $line->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="d-flex align-items-center">
                                    <label for="filterProses" class="form-label mx-2 mb-0">Proses:</label>
                                    <select id="filterProses" class="form-select form-select-sm w-150px">
                                        <option value="">Semua Proses</option>
                                        @foreach ($all_proses as $proses)
                                            <option value="{{ $proses->id }}">{{ $proses->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="d-flex align-items-center position-relative ms-auto">
                                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4"><span class="path1"></span><span class="path2"></span></i>
                                    <input type="text" id="search_dt" class="form-control form-control-sm w-250px ps-12" placeholder="Search Mesin" />
                                </div>

                            </div>
                            
                            <table id="dt_mesin" class="table table-bordered table-striped align-middle table-row-dashed fs-6 gy-5 border rounded">
                                <thead>
                                    <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                                        <th style="width: 50px;">NO</th>
                                        <th>Line</th>
                                        <th>Proses</th>
                                        <th>Kode Mesin</th>
                                        <th>Nama Mesin</th>
                                        <th>Jumlah Operator</th>
                                        <th>Kapasitas</th>
                                        <th>Speed</th>
                                        <th>Action</th>
                                </thead>
                                <tbody>
                                    @forelse ($line->mesins as $mesin)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $line->name }}</td>
                                            <td>
                                                @foreach ($mesin->proses as $proses)
                                                    <span class="badge badge-light-primary m-1">{{ $proses->name }}</span>
                                                @endforeach
                                            </td>
                                            <td>{{ $mesin->kodeMesin }}</td>
                                            <td>{{ $mesin->name }}</td>
                                            <td>{{ $mesin->jumlahOperator }}</td>
                                            <td>{{ $mesin->kapasitas }}</td>
                                            <td>{{ $mesin->speed }}</td>
                                            <td>
                                                <button class="btn btn-sm btn-primary" onclick="editRuang({{ $mesin->id }})">Edit</button>
                                                <button class="btn btn-sm btn-danger" onclick="deleteRuang({{ $mesin->id }})">Delete</button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center">No Mesin found for this line.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>

                            <!--begin::modal mesin-->
                            <div class="modal fade" tabindex="-1" id="modalMesin">
                                <form id="formMesin">
                                    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h3 class="modal-title" id="titleModalMesin"></h3>

                                                <!--begin::Close-->
                                                <div class="btn btn-icon btn-sm ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                    <i class="fas fa-times text-dark"></i>
                                                </div>
                                                <!--end::Close-->
                                            </div>
                                            
                                            <div class="modal-body" id="bodyModalMesin"></div>

                                            <div class="modal-footer">
                                                <div class="me-auto">
                                                    <small class="fst-italic"><span class="text-danger">* Tidak boleh kosong</span></small>
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
                            <!--end::modal mesin -->

                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
@endsection

@section('scripts')
@endsection
     