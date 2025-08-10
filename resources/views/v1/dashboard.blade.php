@extends('layout.master')
@section('title')
    Dashboard
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

                    <div class="row">
                        @foreach ($mesin as $item)
                            <div class="col-lg-4 col-md-6 mb-4 machine-card-wrapper"
                                 data-line-id="{{ $item->line_id }}"
                                 data-proses-ids="{{ $item->proses->pluck('id')->implode(',') }}"
                                 data-name="{{ $item->name }}"
                                 data-kode-mesin="{{ $item->kodeMesin }}">
                                <div class="card" style="text-align: center;  border: 1px solid #e0e0e0; border-radius: 8px; background-color: #f9f9f9; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);">
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
                                        <p class="card-text">{{ $item->line->name }}</p>
                                        <p class="card-text text-gray-700 pt-1 fw-semibold fs-7">{{ $item->kodeMesin }}</p>
                                        <h5 class="card-title mt-3">{{ $item->name }}</h5>
                                        <p class="card-text">Proses: 
                                            @foreach ($item->proses as $proses)
                                                <span class="badge badge-light">{{ $proses->name }}</span>
                                            @endforeach
                                        </p>

                                        @if ($item->kapasitas)
                                            <p class="card-text">Kapasitas: <strong>{{ $item->kapasitas }}</strong></p>
                                        @else
                                            <p class="card-text">Kapasitas: <strong>-</strong></p>
                                        @endif

                                        @if ($item->speed)
                                            <p class="card-text">Speed: <strong>{{ $item->speed }}</strong></p>
                                        @else
                                            <p class="card-text">Speed: <strong>-</strong></p>
                                        @endif

                                        <p class="card-text">{{ $item->jumlahOperator }} Operator</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
@endsection

@section('scripts')
    <script>
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
                    // if(typeof kodeMesin === 'string') {
                    //     name = name.toLowerCase();
                    //     kodeMesin = kodeMesin.toLowerCase();
                    // }
                    // else {
                    //     console.log('Name is not a string:', name);
                    //     console.log('Kode Mesin is not a string:', kodeMesin);
                    // }

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
    </script>
@endsection