<div id="kt_app_sidebar" class="app-sidebar flex-column " data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <div class="app-sidebar-logo px-6 border-bottom-1 flex-column-auto bg-biru-tua " id="kt_app_sidebar_logo">
        <a href="#">
            <div class="h-25px app-sidebar-logo-default">
                <div class="row align-items-center">
                    <div class="col-2">
                        <i class="ki-duotone ki-book-square fs-2x text-white">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                        </i>
                    </div>
                    <div class="col-10">
                        <span class="text-white fw-bold fs-5 text-nowrap mx-2">
                            Kalteng Dashboard
                        </span>
                    </div>
                </div>
                
            </div> 
            <div class="h-20px app-sidebar-logo-minimize">
                <i class="ki-duotone ki-book-square fs-2x text-white">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                </i>        
            </div> 
        </a>
        <div id="kt_app_sidebar_toggle"
            class="app-sidebar-toggle btn btn-icon btn-sm btn-color-muted btn-active-color-primary body-bg h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
            data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
            data-kt-toggle-name="app-sidebar-minimize">
            <i class="ki-duotone ki-double-left fs-2 rotate-180">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
    </div>

    <div class="app-sidebar-menu overflow-hidden flex-column-fluid bg-biru-tua">
        <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5"
            data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto"
            data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
            data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
            <div class="menu menu-column menu-rounded menu-sub-indention px-3" id="#kt_app_sidebar_menu"
                data-kt-menu="true" data-kt-menu-expand="false">
                <div class="menu-item ">
                    <a class="menu-link {{ request()->routeIs('home') ? 'active' : '' }}"
                        href="{{ route('home') }}">
                        <span class="menu-icon ">
                            <i class="ki-duotone ki-element-11 fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>
                        <span class="menu-title fw-bolder fs-5">Dashboard</span>
                    </a>
                </div>

                <div class="menu-item">
                    <a class="menu-link {{ request()->routeIs('performances.index') ? 'active' : '' }}"
                        href="{{ route('performances.index') }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-chart-simple-2 fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                                <span class="path4"></span>
                            </i>
                        </span>
                        <span class="menu-title fw-bolder fs-5">Performance</span>
                    </a>
                </div>

                <div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ request()->is('*budgets*') ? 'show' : '' }}">
                    <span
                        class="menu-link  {{ request()->is('*budgets*') ? 'active' : '' }}">
                        <span class="menu-icon">
                            <i class="ki-duotone ki-dollar fs-2">
                                <span class="path1"></span>
                                <span class="path2"></span>
                                <span class="path3"></span>
                            </i>
                        </span>
                        <span class="menu-title fw-bolder fs-5">Budgets</span>
                        <span class="menu-arrow"></span>
                    </span>
                    <div class="menu-sub menu-sub-accordion">
                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('ceilings.index') ? 'active' : '' }}"
                                href="{{ route('ceilings.index') }}">
                                <span class="menu-title fw-bolder fs-5">
                                    Ceiling
                                </span>
                            </a>
                        </div>

                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('expenses.index') ? 'active' : '' }}"
                                href="{{ route('expenses.index') }}">
                                <span class="menu-title fw-bolder fs-5">
                                    Expense
                                </span>
                            </a>
                        </div>

                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('e-monev.index') ? 'active' : '' }}"
                                href="{{ route('e-monev.index') }}">
                                <span class="menu-title fw-bolder fs-5">
                                    E-Monev
                                </span>
                            </a>
                        </div>

                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('e-performance.index') ? 'active' : '' }}"
                                href="{{ route('e-performance.index') }}">
                                <span class="menu-title fw-bolder fs-5">
                                    E-Performance
                                </span>
                            </a>
                        </div>

                        <div class="menu-item">
                            <a class="menu-link {{ request()->routeIs('ikpa-score.index') ? 'active' : '' }}"
                                href="{{ route('ikpa-score.index') }}">
                                <span class="menu-title fw-bolder fs-5">
                                    IKPA Score
                                </span>
                            </a>
                        </div>


                    </div>
                </div>

                @if(auth()->user()->hasRole('Super Admin'))
                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('file-categories.index') ? 'active' : '' }}"
                            href="{{ route('file-categories.index') }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-setting-2 fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                            <span class="menu-title fw-bolder fs-5">File Categories</span>
                        </a>
                    </div>

                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('departements.index') ? 'active' : '' }}"
                            href="{{ route('departements.index') }}">
                            
                            <span class="menu-icon">
                                <i class="ki-duotone ki-archive fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                    <span class="path3"></span>
                                </i>
                            </span>
                            <span class="menu-title fw-bolder fs-5">Departement</span>
                        </a>
                    </div>


                    <div class="menu-item">
                        <a class="menu-link {{ request()->routeIs('users.index') ? 'active' : '' }}"
                            href="{{ route('users.index') }}">
                            <span class="menu-icon">
                                <i class="ki-duotone ki-security-user fs-2">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </span>
                            <span class="menu-title fw-bolder fs-5">Users</span>
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
