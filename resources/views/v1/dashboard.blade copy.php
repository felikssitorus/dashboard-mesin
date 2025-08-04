@extends('layout.master')
@section('title')
    Dashboard
@endsection

@section('main-content')
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="app-container container-fluid">

            @foreach ($lines as $line)
                <div class="card mb-6">
                    <div class="card-header">
                        <h3 class="card-title">{{ $line->name }}</h3>
                    </div>
                    <div class="card-body">
                        <div class="d-flex flex-wrap gap-3 align-items-center py-5">
                            <div class="d-flex align-items-center">
                                <label class="form-label mx-2 mb-0">Filter Proses:</label>
                                {{-- Beri data-table-id agar JavaScript tahu tabel mana yang harus difilter --}}
                                <select class="form-select form-select-sm w-200px filter-proses" data-table-id="dt_mesin_{{ $line->id }}">
                                    <option value="">Semua Proses</option>
                                    @foreach ($all_proses as $proses)
                                        <option value="{{ $proses->name }}">{{ $proses->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Beri ID unik untuk setiap tabel --}}
                        <table id="dt_mesin_{{ $line->id }}" class="table table-row-bordered table-striped gy-5">
                            <thead>
                                <tr class="fw-semibold fs-6 text-muted">
                                    <th>Kode Mesin</th>
                                    <th>Nama Mesin</th>
                                    <th>Proses</th>
                                    <th>Jml. Operator</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($line->mesins as $mesin)
                                    <tr>
                                        <td>{{ $mesin->kodeMesin }}</td>
                                        <td>{{ $mesin->name }}</td>
                                        <td>
                                            @foreach ($mesin->proses as $proses)
                                                <span class="badge badge-light-primary m-1">{{ $proses->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>{{ $mesin->jumlahOperator }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted">Tidak ada mesin.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{asset("/assets/plugins/custom/datatables/datatables.bundle.js")}}"></script>
    <script>
        $(document).ready(function () {
            // Inisialisasi DataTables untuk SETIAP tabel yang ada
            $('table').each(function() {
                $(this).DataTable({
                    // Opsi sederhana untuk pencarian dan paginasi sisi klien
                    "paging":   true,
                    "ordering": true,
                    "info":     true,
                    "searching": false // Kita akan gunakan filter custom
                });
            });

            // Event listener untuk SEMUA dropdown filter proses
            $('.filter-proses').on('change', function() {
                let selectedProses = $(this).val();
                let tableId = $(this).data('table-id');
                
                // Ambil instance DataTables dari tabel yang sesuai
                let table = $('#' + tableId).DataTable();
                
                // Lakukan pencarian di kolom ke-2 (indeks kolom 'Proses')
                table.column(2).search(selectedProses).draw();
            });
        });
    </script>
@endsection