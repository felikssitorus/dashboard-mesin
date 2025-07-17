{{-- @if (auth()->user()->jobLvl == 'Administrator') --}}
@foreach (auth()->user()->roles->permission as $item)
    @if (Str::is('admin.*', $item->url))
        <!--begin:Menu item-->
        <div class="menu-item pt-5">
            <!--begin:Menu content-->
            <div class="menu-content">
                <span class="text-gray-800 fw-bold text-uppercase fs-8">Admin Tools</span>
            </div>
            <!--end:Menu content-->
        </div>
        <!--end:Menu item-->
        @foreach (auth()->user()->roles->permission as $item)
            @if (Str::is('admin.permission.index', $item->url))
                {{-- Permission --}}
                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link {{ request()->is('admin/permission*') ? 'active' : '' }}"
                        href="{{ route('admin.permission.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-lock-2 fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                                <span class="path5"></span>
                            </i>
                        </span>
                        <span class="menu-title fw-semibold">Permission Access</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
                @break
            @endif
        @endforeach

        @foreach (auth()->user()->roles->permission as $item)
            @if (Str::is('admin.dept.index', $item->url))
                {{-- Department --}}
                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link {{ request()->is('admin/department*') ? 'active' : '' }}"
                        href="{{ route('admin.dept.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-office-bag fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>
                        <span class="menu-title fw-semibold">Department</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
                @break
            @endif
        @endforeach

        @foreach (auth()->user()->roles->permission as $item)
            @if (Str::is('admin.settings.index', $item->url))
                {{-- Web Settings --}}
                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link {{ request()->is('admin/settings*') ? 'active' : '' }}"
                        href="{{ route('admin.settings.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-user-tick fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                        <span class="menu-title fw-semibold">Users Manage</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
                @break
            @endif
        @endforeach

        @foreach (auth()->user()->roles->permission as $item)
            @if (Str::is('admin.settings.index', $item->url))
                {{-- Web Settings --}}
                <!--begin:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link {{ request()->is('admin/settings*') ? 'active' : '' }}" href="{{ route('admin.settings.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-gear fs-1">
                                <span class="path1"></span>
                                <span class="path2"></span>
                            </i>
                        </span>
                        <span class="menu-title fw-semibold">Page Settings</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <!--end:Menu item-->
                @break
            @endif
        @endforeach

        @break
    @endif
@endforeach
