<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>ป้ายหยุดรถโดยสารประจำทาง</title>
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.24/css/jquery.dataTables.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/gh/BowSpleef/jquery-datatables-thai-sorted@1.0.3/js/dataTables.thaiSort.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.7.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <link rel="icon" href="img/favicon.png">
</head>
<body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>ป้ายหยุดรถโดยสารประจำทางในเขตกรุงเทพมหานคร</h4>
                    </div>
                    <div class="card-body">
                        <table id="myTable" class="display" style="width: 100%;">
                            <thead>
                                <tr>
                                    <th>ลำดับ</th>
                                    <th>รหัส</th>
                                    <th>ชื่อป้าย</th>
                                    <th>ละติจูด</th>
                                    <th>ลองจิจูด</th>
                                    <th>ถนน</th>
                                    <th>เขต</th>
                                    <th>สถานีตำรวจ</th>
                                    <th>สถานะ</th>
                                    <th>Maps</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                        <style>
                            .square-button {
                                display: inline-block;
                                padding: 5px 10px;
                                background-color: #3498db;
                                color: #fff;
                                text-align: center;
                                text-decoration: none;
                                font-size: 16px;
                                border-radius: 10px;
                                border: none;
                                cursor: pointer;
                                margin-right: 10px;
                            }

                            .square-button:hover {
                                background-color: #2980b9;
                            }

                            .dt-buttons {
                                float: right;
                                margin-bottom: 10px;
                            }

                            .custom-copy-button {
                                background-color: #87CEEB;
                                color: #fff;
                                border: 1px solid #87CEEB;
                                margin-left: 10px;
                            }

                            .custom-copy-button:hover {
                                background-color: #FFB6C1;
                                border: 1px solid #FFB6C1;
                            }

                            .dataTables_filter {
                                margin-bottom: 10px;
                                margin-right: 10px;
                            }

                            div.dataTables_wrapper div.dataTables_filter input {
                                border-radius: 10px;
                                padding: 5px;
                            }

                            .square-button, .custom-copy-button {
                                margin-bottom: 10px;
                            }
                            .filter-container {
                                margin-bottom: 20px;
                            }
                        </style>

<script>
    jQuery.extend(jQuery.fn.dataTableExt.oSort, {
        "thai-string-asc": function (s1, s2) {
            return s1.localeCompare(s2, "th");
        },
        "thai-string-desc": function (s1, s2) {
            return s2.localeCompare(s1, "th");
        }
    });

    $(document).ready(function () {
        $.ajax({
            url: 'api.php',
            type: 'GET',
            dataType: 'json',
            success: function (data) {
                data.forEach(function (item, index) {
                    item.order = index + 1;
                });

                // Sort data based on the 'new_order' property
                data.sort(function (a, b) {
                    return a.new_order - b.new_order;
                });

                // Populate DataTable with API response
                var table = $('#myTable').DataTable({
                    data: data,
                    columns: [
                        { data: 'order', title: 'ลำดับ' },
                        { data: 'sign_id', title: 'รหัส' },
                        { data: 'sign_name', title: 'ชื่อป้าย' },
                        { data: 'lat', title: 'ละติจูด' },
                        { data: 'lng', title: 'ลองจิจูด' },
                        { data: 'road_name_thai', title: 'ถนน' },
                        { data: 'district_name', title: 'เขต' },
                        { data: 'police_station', title: 'สถานีตำรวจ' },
                        { data: 'sign_type_name', title: 'สถานะ' },
                        {
                            // Custom column for Google Maps link with map icon
                            title: 'Maps',
                            render: function (data, type, row) {
                                // Create a link to Google Maps using latitude and longitude with map icon
                                var googleMapsLink = `<a href="https://www.google.com/maps?q=${row.lat},${row.lng}" target="_blank"><i class="bi bi-geo-alt-fill"></i></a>`;
                                return googleMapsLink;
                            }
                        }
                    ],
                    dom: 'Blfrtip',
                    buttons: [
                        {
                            extend: 'excel',
                            text: 'ดาวน์โหลด Excel',
                            className: 'square-button',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'copy',
                            text: 'คัดลอก',
                            className: 'square-button'
                        }
                    ],
                    lengthMenu: [[10, 25, 50, 100, 500], [10, 25, 50, 100, 500]],
                    pageLength: 25,
                    columnDefs: [
                        { className: 'text-center', targets: [0, 1, 8, 9] }
                    ],
                    createdRow: function (row, data, dataIndex) {
                        var formattedLat = parseFloat(data.lat).toFixed(6);
                        var formattedLng = parseFloat(data.lng).toFixed(6);

                        $('td:eq(3)', row).html(formattedLat);
                        $('td:eq(4)', row).html(formattedLng);

                        var signType = data.sign_type_name;
                        var signTypeCell = $('td:eq(8)', row);

                        if (signType == 'ยกเลิก') {
                            signTypeCell.css('color', 'red');
                        } else if (signType == 'ปกติ') {
                            signTypeCell.css('color', 'green');
                        } else if (signType == 'ไม่อนุมัติ') {
                            signTypeCell.css('color', 'orange');
                        } else if (signType == 'ยกเลิกชั่วคราว') {
                            signTypeCell.css('color', 'blue');
                        }
                    }
                });
                
                /*var columnsToFilter = [5, 6, 7, 8]; // Indices of columns to add dropdown filters

                $('#myTable thead th').each(function (index) {
                    var title = $(this).text();

                    if (columnsToFilter.includes(index)) {
                        $(this).html('<select class="filter-dropdown"><option value="">' + title + '</option></select>');
                    }
                });

                // Populate dropdowns with unique values from each selected column
                table.columns().every(function (index) {
                    var column = this;

                    if (columnsToFilter.includes(index)) {
                        var select = $('.filter-dropdown').eq(index - 5); // Adjusted index to exclude the first 5 columns

                        // Clear existing options before repopulating
                        select.empty().append('<option value="">' + column.header().textContent + '</option>');

                        // Get unique values from the column
                        column.data().unique().sort().each(function (d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>');
                        });

                        // Apply the filters when the dropdown selection changes
                        select.on('change', function () {
                            var val = $.fn.dataTable.util.escapeRegex($(this).val());
                            table.column(index).search(val ? '^' + val + '$' : '', true, false).draw();
                        });
                    }
                });*/
            },
            error: function (xhr, status, error) {
                console.error('API request failed:', status, error);
            }
        });
    });
</script>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
