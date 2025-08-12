@extends('layout.master')
@section('title')
    Dashboard
@endsection

@section('styles')
    <style>
        /* Gunakan ID container utama untuk meningkatkan spesifisitas */
        .machine-card-wrapper .machine-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .machine-card-wrapper:hover .machine-card {
            transform: translateY(-8px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.15);
        }
        .machine-card-wrapper .machine-card i {
            transition: transform 0.3s ease;
        }
        .machine-card-wrapper:hover .machine-card i {
            transform: scale(1.1);
        }
        .machine-card-wrapper:hover .machine-card .card-title {
            color: var(--bs-primary) !important; /* Tambahkan !important jika perlu */
        }
    </style>
@endsection

@section('main-content')
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Toolbar-->
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                        Dashboard
                    </h1>
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="#" class="text-muted text-hover-primary">Home</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <!--end::Toolbar-->

        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid d-flex flex-column flex-column-fluid">
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Data Mesin</div>
                </div>
                <div class="card-body">
                    <div class="d-flex flex-wrap gap-2 justify-content-start align-items-center py-5">

                        <div class="form-group">
                            <label for="filterLines" class="form-label me-2 mb-0">Line:</label>
                            <select id="filterLines" class="form-select form-select-sm w-200px">
                                <option value="">Semua Line</option>
                                @foreach ($filter_lines as $line)
                                    <option value="{{ $line->id }}">{{ $line->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="filterProses" class="form-label mx-2 mb-0">Proses:</label>
                            <select id="filterProses" class="form-select form-select-sm w-200px">
                                <option value="">Semua Proses</option>
                                @foreach ($filter_proses as $proses)
                                    <option value="{{ $proses->id }}">{{ $proses->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    
                        <div class="d-flex align-items-center position-relative ms-auto">
                            <input type="text" id="search_dt" class="form-control form-control-sm w-250px" placeholder="Search Mesin" />
                            <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4"></i>
                        </div>

                    </div>

                    <div class="card-container row row-cols-1 row-cols-md-2 row-cols-lg-3 g-5">
                        @foreach ($mesin as $item)
                            <div class="machine-card-wrapper col-lg-4 col-md-6 mb-4"
                                 data-line-id="{{ $item->line_id }}"
                                 data-proses-ids="{{ $item->proses->pluck('id')->implode(',') }}"
                                 data-name="{{ $item->name }}"
                                 data-kode-mesin="{{ $item->kodeMesin }}">
                                <div class="machine-card card h-100" style="text-align: center;  border: 1px solid #e0e0e0; border-radius: 8px; background-color: #f9f9f9; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
                                    <div class="card-body text-center">
                                        @if ($item->image)
                                            {{-- Jika mesin PUNYA gambar, tampilkan gambar tersebut --}}
                                            <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->name }}" class="img-fluid rounded" style="height: 200px; width: 100%; object-fit: cover;"/>
                                        @else
                                            {{-- Jika TIDAK ADA gambar, tampilkan ikon default --}}
                                            <i class="ki-duotone ki-calculator text-primary" style="font-size: 200px;">
                                                <span class="path1"></span><span class="path2"></span>
                                                <span class="path3"></span><span class="path4"></span>
                                                <span class="path5"></span><span class="path6"></span>
                                            </i>
                                        @endif
                                        <p class=""></p>
                                        <div class="mb-4">
                                            {{-- PERBAIKAN 1: Dekatkan jarak baris --}}
                                            <p class="text-muted mb-1" style="font-size: 15px;">{{ $item->line->name }}</p>
                                            <h5 class="card-title mb-1">{{ $item->kodeMesin }} - {{ $item->name }}</h5>
                                        </div>

                                        <p class="card-text">Proses: 
                                            @foreach ($item->proses as $proses)
                                                <span class="badge badge-light">{{ $proses->name }}</span>
                                            @endforeach
                                        </p>
        
                                        @if ($item->kapasitas)
                                            <p class="card-text">Kapasitas: <strong>{{ $item->kapasitas }} Liter</strong></p>
                                        @else
                                            <p class="card-text">Kapasitas: <strong>-</strong></p>
                                        @endif

                                        @if ($item->speed)
                                            <p class="card-text">Speed: <strong>{{ $item->speed }} RPM</strong></p>
                                        @else
                                            <p class="card-text">Speed: <strong>-</strong></p>
                                        @endif

                                        <p class="card-text">{{ $item->jumlahOperator }} Operator</p>

                                        <div class="d-grid">
                                            <button class="btn btn-primary" onclick="showDetail('{{ $item->id }}')">
                                                <i class="fas fa-eye"></i> Detail
                                            </button>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

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

                    <!--begin::modal detail mesin-->
                    <div class="modal fade" tabindex="-1" id="modalDetailMesin">
                        <form id="formMesin">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title" id="titleModalDetailMesin"></h3>

                                        <!--begin::Close-->
                                        <div class="btn btn-icon btn-sm ms-2" data-bs-dismiss="modal" aria-label="Close">
                                            <i class="fas fa-times text-dark"></i>
                                        </div>
                                        <!--end::Close-->
                                    </div>
                                    
                                    <div class="modal-body" id="bodyModalDetailMesin"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!--end::modal mesin -->

                </div>
            </div>
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
@endsection

@section('scripts')
    <script>
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });

        $(document).ready(function () {
            function filterCards() {
                let lineFilter = $('#filterLines').val();
                let prosesFilter = $('#filterProses').val();
                let searchFilter = $('#search_dt').val().toLowerCase().trim();

                $('.machine-card-wrapper').each(function() {
                    let card = $(this);
                    let lineId = card.data('line-id');
                    let prosesIds = card.data('proses-ids');
                    let name = card.data('name');
                    let nameStr = String(name).toLowerCase();
                    let kodeMesin = card.data('kode-mesin');
                    let kodeMesinStr = String(kodeMesin).toLowerCase();

                    let lineMatch = (lineFilter === "") || (lineId == lineFilter);
                    let prosesMatch = (prosesFilter === "") || (prosesIds.split(',').includes(prosesFilter));
                    let searchMatch = (nameStr.includes(searchFilter)) || (kodeMesinStr.includes(searchFilter));

                    if (lineMatch && prosesMatch && searchMatch) {
                        card.show();
                    } else {
                        card.hide();
                    }
                });
            }

            // Terapkan filter saat dropdown atau search box berubah
            $('#filterLines, #filterProses, #search_dt').on('change keyup', function() {
                filterCards();
            });
        });

        function showDetail(id) {
            $('#titleModalDetailMesin').html('Machine Details');
            $('#bodyModalDetailMesin').html('<p class="text-center">Loading data...</p>');
            $('#modalDetailMesin').modal('show');

            let url = `{{ url('v1/mesin/edit') }}/${id}`; // Kita gunakan endpoint 'edit' yang sudah ada

            $.get(url, function (response) {
                let mesin = response.mesin;

                let prosesList = '<p>Tidak ada proses terkait.</p>';
                if (mesin.proses && mesin.proses.length > 0) {
                    prosesList = '<ul class="list-group list-group-flush">';
                    mesin.proses.forEach(function(p) {
                        prosesList += `<li class="list-group-item">${p.name}</li>`;
                    }); 
                    prosesList += '</ul>';
                }

                let imageHtml = '<p class="text-muted">No image available</p>';
                if (mesin.image) {
                    let imageUrl = `{{ asset('storage') }}/${mesin.image}`;
                    imageHtml = `<img src="${imageUrl}" alt="${mesin.name}" class="img-fluid rounded" style="max-height: 200px; justify-content: center; display: block; margin-left: auto; margin-right: auto;">`;
                }

                let updatedAt = mesin.updated_at;
                if (updatedAt) {
                    updatedAt = new Date(updatedAt).toLocaleString('id-ID', {
                        year: 'numeric',
                        month: '2-digit',
                        day: '2-digit',
                    });
                } else {
                    updatedAt = '-';
                }
                
                $('#bodyModalDetailMesin').html(`
                    <div class="mb-5 flex-row align-items-center items-center justify-center">
                        ${imageHtml}
                    </div>
                    <table class="table table-bordered table-striped border border-4">
                        <tbody>
                            <tr><th class="w-250px">Line</th><td>${mesin.line ? mesin.line.name : '-'}</td></tr>
                            <tr>
                                <th class="align-middle">Proses</th>
                                <td>${prosesList}</td>
                            </tr>
                            <tr><th>Kode Mesin</th><td>${mesin.kodeMesin}</td></tr>
                            <tr><th>Nama Mesin</th><td>${mesin.name}</td></tr>
                            <tr><th>Jumlah Operator</th><td>${mesin.jumlahOperator}</td></tr>
                            <tr><th>Kapasitas</th><td>${mesin.kapasitas || '-'}</td></tr>
                            <tr><th>Speed</th><td>${mesin.speed || '-'}</td></tr>
                            <tr><th>Updated At</th><td>${updatedAt}</td></tr>
                            <tr><th>Input By</th><td>${mesin.inupby || '-'}</td></tr>
                        </tbody>
                    </table>
                `);
            }).fail(function() {
                $('#bodyModalDetailMesin').html('<p class="text-center text-danger">Gagal memuat data.</p>');
            });
        }
    </script>
@endsection