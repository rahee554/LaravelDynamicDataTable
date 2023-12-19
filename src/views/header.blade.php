@if ($search || $export_btn || $colvis)
    <div class="d-sm-flex justify-content-between border-0 align-items-center py-5 gap-2 gap-md-5">
        <div class="card-title">
            @if ($search == true)
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    <span class="svg-icon fs-5 position-absolute ms-4"><i class="fad fa-search "></i></span>
                    <input type="text" data-kt-filter="search"
                        class="form-control form-control-sm form-control-solid w-250px ps-14"
                        placeholder="Search Invoice" />
                </div>
                <!--end::Search-->
            @endif
        </div>

        <div class="card-toolbar flex-row-fluid justify-content-sm-end gap-5 d-flex">
            <div id="filters">

            </div>
            @if ($export_btn == true)
                <!--begin::Export dropdown-->
                <button type="button" class="btn btn-sm btn-light-primary" data-kt-menu-trigger="click"
                    data-kt-menu-placement="bottom-end">
                    <i class="ki-duotone ki-exit-down fs-2"><span class="path1"></span><span class="path2"></span></i>
                    Export
                </button>
                <!--begin::Menu-->
                <div id="export_btn"
                    class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4"
                    data-kt-menu="true">
                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                        <a href="#" class="menu-link px-3" data-kt-export="copy">
                            Copy to clipboard
                        </a>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                        <a href="#" class="menu-link px-3" data-kt-export="excel">
                            Export as Excel
                        </a>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                        <a href="#" class="menu-link px-3" data-kt-export="csv">
                            Export as CSV
                        </a>
                    </div>
                    <!--end::Menu item-->
                    <!--begin::Menu item-->
                    <div class="menu-item px-3">
                        <a href="#" class="menu-link px-3" data-kt-export="pdf">
                            Export as PDF
                        </a>
                    </div>
                    <!--end::Menu item-->
                </div>
            @endif
            <!--end::Menu-->
            <!--end::Export dropdown-->

            <!--begin::Hide default export buttons-->
            <div id="default_dtable_btns" class="d-none"></div>
            <!--end::Hide default export buttons-->

            @if ($colvis == true)
                <div class="dropdown">
                    <button class="btn btn-sm btn-light dropdown-toggle" type="button" id="customColvisButton"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Column Visibility
                    </button>
                    <div class="dropdown-menu" aria-labelledby="customColvisButton" id="colvisDropdown">
                        <!-- Dynamic content will be added here -->
                    </div>
                </div>
            @endif
        </div>
    </div>
@endif
