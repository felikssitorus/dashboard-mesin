@extends('layout.master')
@section('title')
    Edit Permission Manage
@endsection
@section('page_title')
    Edit Permissions Manage
@endsection
@section('breadcrumb')
    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
        <li class="breadcrumb-item text-muted">
            <a href="#" class="text-muted text-hover-primary">Home</a>
        </li>
        <li class="breadcrumb-item">
            <span class="bullet bg-gray-400 w-5px h-2px"></span>
        </li>
        <li class="breadcrumb-item text-muted">Admin</li>
        <li class="breadcrumb-item">
            <span class="bullet bg-gray-400 w-5px h-2px"></span>
        </li>
        <li class="breadcrumb-item text-muted">Edit Permission Manage</li>
    </ul>
@endsection
@section('main-content')
    <!--begin::Content-->
    <div id="kt_app_content" class="app-content flex-column-fluid">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container container-fluid d-flex flex-column flex-column-fluid">
            <div class="row">
                <div class="col">
                    <div class="card">
                        <div class="card-header">
                            <div class="card-title">
                                <div class="row">
                                    <div class="col-12 mb-0 py-0">
                                        <h5 class="my-0">Edit Permission Manage</h5>
                                    </div>
                                    <div class="col-12 my-0 py-0">
                                        <span class="fw-light fs-8">Manager Your Permission</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="card-content">
                                <form action="{{ route('admin.permission.update', $role->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between">
                                            <label for="jobLvl" class="form-label">Job Level</label>
                                            <div class="form-check">
                                                <input type="checkbox" id="checkAll" class="form-check-input">
                                                <label class="form-check-label" for="checkAll">Check All</label>
                                            </div>
                                        </div>
                                        <input type="text" name="jobLvl" class="form-control" value="{{ $role->name }}" required>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">Select URLs</label>
                                        <div class="row">
                                            @foreach ($routes as $routeName => $route)
                                                @if (!str_starts_with($routeName, 'admin.') && !str_starts_with($routeName, 'v1.') && !str_starts_with($routeName, 'livewire.'))
                                                    <div class="col-md-4 col-sm-6">
                                                        <div class="form-check">
                                                            <input type="checkbox" name="urls[]" value="{{ $routeName }}"
                                                                class="form-check-input route-checkbox my-1"
                                                                id="route_{{ $loop->index }}"
                                                                {{ in_array($routeName, optional($role->permission)->pluck('url')->toArray() ?? []) ? 'checked' : '' }}>
                                                            <label class="form-check-label my-1 text-gray-700"
                                                                for="route_{{ $loop->index }}">{{ $routeName }}</label>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <p class="mt-5 mb-2">Admin</p>
                                        <div class="row">
                                            @foreach ($routes as $routeName => $route)
                                                @if (str_starts_with($routeName, 'admin.'))
                                                    <div class="col-md-4 col-sm-6">
                                                        <div class="form-check">
                                                            <input type="checkbox" name="urls[]" value="{{ $routeName }}"
                                                                class="form-check-input route-checkbox my-1"
                                                                id="route_{{ $loop->index }}"
                                                                {{ in_array($routeName, optional($role->permission)->pluck('url')->toArray() ?? []) ? 'checked' : '' }}>
                                                            <label class="form-check-label my-1 text-gray-700"
                                                                for="route_{{ $loop->index }}">{{ $routeName }}</label>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <p class="mt-5 mb-2">User</p>
                                        <div class="row">
                                            @foreach ($routes as $routeName => $route)
                                                @if (str_starts_with($routeName, 'v1.'))
                                                    <div class="col-md-4 col-sm-6">
                                                        <div class="form-check">
                                                            <input type="checkbox" name="urls[]" value="{{ $routeName }}"
                                                                class="form-check-input route-checkbox my-1"
                                                                id="route_{{ $loop->index }}"
                                                                {{ in_array($routeName, optional($role->permission)->pluck('url')->toArray() ?? []) ? 'checked' : '' }}>
                                                            <label class="form-check-label my-1 text-gray-700"
                                                                for="route_{{ $loop->index }}">{{ $routeName }}</label>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary mt-10">Save</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Content container-->
    </div>
    <!--end::Content-->
@endsection
@section('scripts')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#checkAll').change(function () {
                $('.route-checkbox').prop('checked', this.checked);
            });
        });
    </script>
@endsection