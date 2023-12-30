@push('AF_dtable.css')
    @if (!isset($index) || $index == 1)
        <link rel="stylesheet" type="text/css" href="{{ asset('vendor/AF_DataTables/datatables.bundle.css') }}">
        <link rel="stylesheet" type="text/css" href="{{ asset('vendor/AF_DataTables/datatables.custom.css') }}">
    @endif
@endpush

@php
    if (config('app.env') !== 'production') {
        $ErrorHandling = ' console.log("DataTables error:", error);
            console.log("Server response:", xhr.responseText);';
    } else {
        $ErrorHandling = '';
    }

@endphp



@push('AF_dtable.js')

    @if (!isset($index) || $index == 1)
        <script src="{{ asset('vendor/AF_DataTables/datatables.bundle.js') }}"></script>
        <script src="{{ asset('vendor/AF_DataTables/datatables.custom.js') }}"></script>
    @endif



    <script>
        var {{ 'create_dtable' . ($index ?? 'create_dtable') }} = (function() {
            var table, datatable;

            var initDatatable = function() {
                const tableRows = table.querySelectorAll('tbody tr');

                const defaultOptions = {
                    "info": {{ $info ?? 'true' }},
                    'order': [{{ $order[0] ?? 0 }}, '{{ $order[1] ?? 'asc' }}'],
                    'pageLength': {{ $pageLength ?? '10' }},
                    processing: {{ $processing ?? 'true' }},
                    serverSide: {{ $serverside ?? 'true' }},
                    responsive: {{ $responsive ?? 'true' }},
                    select: {{ $select ?? 'false' }},
                    ajax: {
                        url: '{{ route($route) }}',
                        type: 'POST',
                        data: function(d) {
                            return $.extend({}, d, {
                                _token: $('meta[name="csrf-token"]').attr("content"),
                            });
                        },
                    },
                    error: function(xhr, error, thrown) {
                        {!! html_entity_decode($ErrorHandling) !!}
                    },
                    "columnDefs": [{
                        "targets": @json($hide_cols ?? []),
                        "visible": false,
                    }],
                    columns: [
                        @foreach ($cols as $col)
                            {
                                data: '{{ $col }}',
                                name: '{{ $col }}',
                                @isset($col['class'])
                                    className: '{{ $col['class'] }}',
                                @endisset
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
                                $(row).find('td:eq({{ $class[0] }})').addClass(
                                    '{{ $class[1] }}');
                            @endforeach
                        @endisset
                    },
                };

                datatable = $(table).DataTable(defaultOptions);
                customColvis();
                $('.dataTables_filter').css('display', 'none');
            };

            var customColvis = function() {
                const colvisDropdown = $("#colvisDropdown");

                datatable.columns().every(function(index) {
                    const column = this;
                    const button = document.createElement("a");
                    button.className = "dropdown-item custom-colvis-item";
                    button.href = "javascript:void(0)";
                    button.dataset.cvIdx = index;
                    button.textContent = column.header().textContent;

                    const setColumnVisibility = function() {
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
                        e.preventDefault();
                        e.stopPropagation();
                        setColumnVisibility();
                    });

                    colvisDropdown.append(button);

                    if (column.visible()) {
                        button.classList.add("text-primary");
                    }
                });

                $("#customColvisButton").dropdown({
                    keepOnHover: true
                });
            };

            var exportButtons = function() {
                const documentTitle = 'Invoices List';
                const buttons = new $.fn.dataTable.Buttons(table, {
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

                const exportButtons = document.querySelectorAll('#export_btn [data-kt-export]');
                exportButtons.forEach(exportButton => {
                    exportButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        const exportValue = e.target.getAttribute('data-kt-export');
                        const target = document.querySelector('.dt-buttons .buttons-' +
                            exportValue);
                        target.click();
                    });
                });
            };


            return {
                init: function() {
                    table = document.querySelector('#{{ $id }}');
                    if (!table) {
                        return;
                    }
                    initDatatable();
                    exportButtons();
                }
            };
        })();

        KTUtil.onDOMContentLoaded(function() {
            {{ 'create_dtable' . ($index ?? 'create_dtable') }}.init();
        });
    </script>
@endpush
