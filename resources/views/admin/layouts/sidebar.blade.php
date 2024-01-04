<div id="sidebar" class="">
    <div class="sidebar-wrapper active">
        <div class="sidebar-header position-relative">
            <div class="d-flex justify-content-between align-items-center">
                <div class="logo mt-2">
                    <a href="{{ route('dashboard') }}" class="d-flex gap-1 justify-content-center align-items-center">
                        <img src="{{ asset('assets/compiled/logos/logo-sm.png') }}" alt="SMAN 1 Purwosari" width="50" style="height: auto">
                        {{-- <span class="fs-6">SMANESA</span> --}}
                    </a>
                </div>
                <div class="theme-toggle d-flex gap-2  align-items-center mt-2">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                         role="img" class="iconify iconify--system-uicons" width="20" height="20"
                         preserveAspectRatio="xMidYMid meet" viewBox="0 0 21 21">
                        <g fill="none" fill-rule="evenodd" stroke="currentColor" stroke-linecap="round"
                           stroke-linejoin="round">
                            <path
                                d="M10.5 14.5c2.219 0 4-1.763 4-3.982a4.003 4.003 0 0 0-4-4.018c-2.219 0-4 1.781-4 4c0 2.219 1.781 4 4 4zM4.136 4.136L5.55 5.55m9.9 9.9l1.414 1.414M1.5 10.5h2m14 0h2M4.135 16.863L5.55 15.45m9.899-9.9l1.414-1.415M10.5 19.5v-2m0-14v-2"
                                opacity=".3"></path>
                            <g transform="translate(-210 -1)">
                                <path d="M220.5 2.5v2m6.5.5l-1.5 1.5"></path>
                                <circle cx="220.5" cy="11.5" r="4"></circle>
                                <path d="m214 5l1.5 1.5m5 14v-2m6.5-.5l-1.5-1.5M214 18l1.5-1.5m-4-5h2m14 0h2"></path>
                            </g>
                        </g>
                    </svg>
                    <div class="form-check form-switch fs-6">
                        <input class="form-check-input  me-0" type="checkbox" id="toggle-dark" style="cursor: pointer">
                        <label class="form-check-label"></label>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true"
                         role="img" class="iconify iconify--mdi" width="20" height="20" preserveAspectRatio="xMidYMid meet"
                         viewBox="0 0 24 24">
                        <path fill="currentColor"
                              d="m17.75 4.09l-2.53 1.94l.91 3.06l-2.63-1.81l-2.63 1.81l.91-3.06l-2.53-1.94L12.44 4l1.06-3l1.06 3l3.19.09m3.5 6.91l-1.64 1.25l.59 1.98l-1.7-1.17l-1.7 1.17l.59-1.98L15.75 11l2.06-.05L18.5 9l.69 1.95l2.06.05m-2.28 4.95c.83-.08 1.72 1.1 1.19 1.85c-.32.45-.66.87-1.08 1.27C15.17 23 8.84 23 4.94 19.07c-3.91-3.9-3.91-10.24 0-14.14c.4-.4.82-.76 1.27-1.08c.75-.53 1.93.36 1.85 1.19c-.27 2.86.69 5.83 2.89 8.02a9.96 9.96 0 0 0 8.02 2.89m-1.64 2.02a12.08 12.08 0 0 1-7.8-3.47c-2.17-2.19-3.33-5-3.49-7.82c-2.81 3.14-2.7 7.96.31 10.98c3.02 3.01 7.84 3.12 10.98.31Z">
                        </path>
                    </svg>
                </div>
                <div class="sidebar-toggler  x">
                    <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                </div>
            </div>
        </div>
        <div class="sidebar-menu">
            <ul class="menu">


                <li
                    class="sidebar-item {{ isset($main_menu) && $main_menu == 'dashboard' ? 'active' : '' }}">
                    <a href="{{ route('dashboard') }}" class='sidebar-link'>
                        <i class="bi bi-grid-fill"></i>
                        <span>Dashboard</span>
                    </a>


                </li>


                @unlessrole('satpam')
                <li class="sidebar-title">Master Data</li>
                @endunlessrole

                @hasrole(['staf', 'superadmin', 'pimpinan', 'guru'])
                <li
                    class="sidebar-item {{ isset($main_menu) && $main_menu == 'student' ? 'active' : '' }}">
                    <a href="{{ route('student.index') }}" class='sidebar-link'>
                        <i class="bi bi-mortarboard-fill"></i>
                        <span>Siswa</span>
                    </a>


                </li>
                @endhasrole

                @hasrole(['staf', 'superadmin', 'pimpinan'])
                <li
                    class="sidebar-item {{ isset($main_menu) && $main_menu == 'teacher' ? 'active' : '' }} has-sub">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-person-fill"></i>
                        <span>Guru</span>
                    </a>

                    <ul class="submenu active">
                        @hasrole('superadmin')
                        <li class="submenu-item {{ isset($sub_menu) && $sub_menu == 'teacher-add' ? 'active' : '' }}">
                            <a href="{{ url('teacher/create') }}" class="submenu-link">Tambah Guru</a>
                        </li>
                        @endhasrole
                        <li class="submenu-item {{ isset($sub_menu) && $sub_menu == 'teacher-list' ? 'active' : '' }}">
                            <a href="{{ url('teacher') }}" class="submenu-link">List Guru</a>

                        </li>

                    </ul>


                </li>
                @endhasrole

                @hasrole('superadmin')
                <li
                    class="sidebar-item has-sub {{ isset($main_menu) && $main_menu == 'user' ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-people-fill"></i>
                        <span>Pengguna</span>
                    </a>

                    <ul class="submenu active">
                        @hasrole('superadmin')
                        <li class="submenu-item {{ isset($sub_menu) && $sub_menu == 'user-add' ? 'active' : '' }}">
                            <a href="{{ url('user/create') }}" class="submenu-link">Tambah Pengguna</a>
                        </li>
                        @endhasrole
                        <li class="submenu-item {{ isset($sub_menu) && $sub_menu == 'user-list' ? 'active' : '' }}">
                            <a href="{{ url('user') }}" class="submenu-link">List Pengguna</a>
                        </li>
                    </ul>
                </li>
                @endhasrole

                @hasrole(['guru', 'staf', 'superadmin'])
                <li
                    class="sidebar-item has-sub {{ isset($main_menu) && $main_menu == 'violation-master' ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-pass-fill"></i>
                        <span>Pelanggaran</span>
                    </a>

                    <ul class="submenu active">
                        <li class="submenu-item {{ isset($sub_menu) && $sub_menu == 'violation-type' ? 'active' : '' }}">
                            <a href="{{ url('violation-type') }}" class="submenu-link">Jenis Pelanggaran</a>

                        </li>

                        <li class="submenu-item {{ isset($sub_menu) && $sub_menu == 'treatment' ? 'active' : '' }}">
                            <a href="{{ url('treatment') }}" class="submenu-link">Tindak Lanjut</a>

                        </li>
                    </ul>


                </li>
                @endhasrole

                <li class="sidebar-title">Menu</li>

                <li
                    class="sidebar-item has-sub {{ isset($main_menu) && $main_menu == 'attendance' ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-clock-fill"></i>
                        <span>Presensi</span>
                    </a>

                    <ul class="submenu active">

                        <li class="submenu-item {{ isset($sub_menu) && $sub_menu == 'attendance-overview' ? 'active' : '' }}">
                            <a href="{{ url('attendance') }}" class="submenu-link">Overview</a>

                        </li>
                        <li class="submenu-item {{ isset($sub_menu) && $sub_menu == 'attendance-manage' ? 'active' : '' }}">
                            <a href="{{ url('attendance/presence') }}" class="submenu-link">Manajemen Presensi</a>

                        </li>

                        <li class="submenu-item {{ isset($sub_menu) && $sub_menu == 'attendance-report' ? 'active' : '' }}">
                            <a href="{{ url('attendance/report') }}" class="submenu-link">Laporan Presensi</a>

                        </li>
                    </ul>


                </li>

                <li
                    class="sidebar-item has-sub {{ isset($main_menu) && $main_menu == 'permit' ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-door-open-fill"></i>
                        <span>Izin Keluar-Masuk</span>
                    </a>

                    <ul class="submenu active">

                        @hasrole(['guru', 'staf', 'pimpinan', 'superadmin'])
                        <li class="submenu-item {{ isset($sub_menu) && $sub_menu == 'permit-add' ? 'active' : '' }}">
                            <a href="{{ url('permit/create') }}" class="submenu-link">Tambah Izin</a>

                        </li>
                        @endhasrole
                        <li class="submenu-item {{ isset($sub_menu) && $sub_menu == 'permit-list' ? 'active' : '' }}">
                            <a href="{{ url('permit') }}" class="submenu-link">List Izin</a>

                        </li>
                    </ul>


                </li>

                @hasrole(['guru', 'staf', 'superadmin'])
                <li
                    class="sidebar-item has-sub {{ isset($main_menu) && $main_menu == 'violation' ? 'active' : '' }}">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-clipboard-x-fill"></i>
                        <span>Catat Pelanggaran</span>
                    </a>

                    <ul class="submenu active">

                        <li class="submenu-item {{ isset($sub_menu) && $sub_menu == 'violation' ? 'active' : '' }}">
                            <a href="{{ url('violation') }}" class="submenu-link">Pelanggaran</a>

                        </li>

                        <li class="submenu-item {{ isset($sub_menu) && $sub_menu == 'violation-report' ? 'active' : '' }}">
                            <a href="{{ url('violation/report') }}" class="submenu-link">Laporan Pelanggaran</a>

                        </li>
                    </ul>


                </li>
                @endhasrole

                {{-- <li class="sidebar-title">Pengaturan</li>

                <li
                    class="sidebar-item  ">
                    <a href="#" class='sidebar-link'>
                        <i class="bi bi-life-preserver"></i>
                        <span>Akun</span>
                    </a>
                </li>

                <li class="sidebar-item">
                    <form action="{{ route('logout') }}" method="post">
                    @csrf
                    
                    <button type="submit" class='sidebar-link w-100 border-0'>
                        <i class="bi bi-puzzle"></i>
                        <span>Keluar</span>
                    </button>
                    </form>
                </li> --}}

            </ul>
        </div>
    </div>
</div>
