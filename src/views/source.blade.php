@push('AF_dtable.css')
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/AF_DataTables/datatables.bundle.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('vendor/AF_DataTables/datatables.custom.css') }}">
@endpush


@push('AF_dtable.js')
    <script src="{{ asset('vendor/AF_DataTables/datatables.bundle.js') }}"></script>
    <script src="{{ asset('vendor/AF_DataTables/datatables.custom.js') }}"></script>

    <script>
        var datatable_func = function() {
            // Shared variables
            var table;
            var datatable;

            // Private functions
            var initDatatable = function() {
                // Set date data order
                const tableRows = table.querySelectorAll('tbody tr');
                // Initialize the DataTable without the default column visibility button ('l' removed from 'dom')
                datatable = $(table).DataTable({
                    "info": {{ $info ?? 'true' }},
                    'order': [{{ $order[0] ?? 0 }}, '{{ $order[1] ?? 'asc' }}'],
                    'pageLength': 10,
                    // 'buttons': [],
                    processing: {{ $processing ?? 'true' }},
                    serverSide: {{ $serverside ?? 'true' }},
                    responsive: {{ $responsive ?? 'true' }},
                    select: {{ $select ?? false }},

                    ajax: {
                        url: '{{ route($route) }}', // Use the named route
                        type: 'POST',
                        data: function(d) {
                            return $.extend({}, d, {
                                _token: $('meta[name="csrf-token"]').attr("content"),
                            });
                        },
                    },
                    error: function(xhr, error, thrown) {
                        console.log("DataTables error:", error);
                        console.log("Server response:", xhr.responseText);
                    },
                    "columnDefs": [{
                        "targets": @json($hide_cols),
                        "visible": false,
                    }],
                    columns: [
                        @foreach ($cols as $col)
                            {
                                data: '{{ $col['name'] }}',
                                name: '{{ $col['name'] }}',
                                className: '{{ $col['class'] }}',
                                render: function(data, type, row, meta) {
                                    // Custom rendering logic based on the provided function
                                    @isset($render)
                                        @foreach ($render as $renderInfo)
                                            if (meta.col === {{ $renderInfo['col'] }}) {
                                                return `{!! addslashes($renderInfo['html']) !!}`.replace('${data}',
                                                    data);
                                            }
                                        @endforeach
                                    @endisset

                                    // Default behavior if no custom rendering is specified
                                    return data;
                                }
                            },
                        @endforeach
                    ],

                    createdRow: function(row, data, dataIndex) {
                        // Apply the className to specific rows
                        @isset($cols_class)
                            @foreach ($cols_class as $class)
                                $(row).find('td:eq({{ $class['index'] }})').addClass(
                                    '{{ $class['class'] }}');
                            @endforeach
                        @endisset

                    },
                });

                // Custom column visibility using customColvis
                customColvis();

                // Hide the default search input
                $('.dataTables_filter').css('display', 'none');
            };

            // Updated customColvis function to create a button element
            // Updated customColvis function to add the "text-primary" class to default columns
            var customColvis = () => {
                const colvisDropdown = $("#colvisDropdown");

                datatable.columns().every(function(index) {
                    const column = this;
                    const button = document.createElement("a");
                    button.className = "dropdown-item custom-colvis-item";
                    button.href = "javascript:void(0)"; // Use "javascript:void(0)" to prevent page reload
                    button.dataset.cvIdx = index;
                    button.textContent = column.header().textContent;

                    // Function to set the visibility and active class
                    const setColumnVisibility = () => {
                        const columnIndex = parseInt(button.dataset.cvIdx, 10);
                        const currentVisibility = column.visible();

                        if (currentVisibility) {
                            column.visible(false);
                            button.classList.remove("text-primary");
                        } else {
                            column.visible(true);
                            button.classList.add("text-primary");
                        }
                    };

                    button.addEventListener("click", function(e) {
                        e.preventDefault(); // Prevent the default action of the anchor element
                        e.stopPropagation(); // Prevent event propagation to keep the dropdown open
                        setColumnVisibility();
                    });

                    colvisDropdown.append(button);

                    // Add the "text-primary" class to default columns
                    if (column.visible()) {
                        button.classList.add("text-primary");
                    }
                });

                // Initialize Bootstrap 5 dropdown without closing when clicking
                $("#customColvisButton").dropdown({
                    keepOnHover: true // Keep the dropdown open on hover
                });
            };

            // Hook export buttons
            var exportButtons = () => {
                const documentTitle = 'Invoices List';
                var buttons = new $.fn.dataTable.Buttons(table, {
                    buttons: [{
                            extend: 'copyHtml5',
                            title: documentTitle
                        },
                        {
                            extend: 'excelHtml5',
                            title: documentTitle
                        },
                        {
                            extend: 'csvHtml5',
                            title: documentTitle
                        },
                        {
                            extend: 'pdfHtml5',
                            title: documentTitle,
                            orientation: 'portrait',
                            pageSize: 'A4'
                        }
                    ]
                }).container().appendTo($('#default_dtable_btns'));

                // Hook dropdown menu click event to datatable export buttons
                const exportButtons = document.querySelectorAll('#export_btn [data-kt-export]');
                exportButtons.forEach(exportButton => {
                    exportButton.addEventListener('click', e => {
                        e.preventDefault();

                        // Get clicked export value
                        const exportValue = e.target.getAttribute('data-kt-export');
                        const target = document.querySelector('.dt-buttons .buttons-' +
                            exportValue);
                        target.click();
                    });
                });
            };

            // Search Datatable
            var handleSearchDatatable = () => {
                const filterSearch = document.querySelector('[data-kt-filter="search"]');
                filterSearch.addEventListener('keyup', function(e) {
                    datatable.search(e.target.value).draw();
                });
            };

            // Public methods
            return {
                init: function() {
                    table = document.querySelector('#{{ $id }}');

                    if (!table) {
                        return;
                    }

                    initDatatable();
                    exportButtons();
                    handleSearchDatatable();
                }
            };
        }();

        // On document ready
        KTUtil.onDOMContentLoaded(function() {
            datatable_func.init();
        });
    </script>
@endpush
