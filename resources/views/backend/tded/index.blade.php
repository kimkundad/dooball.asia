@extends('backend/layouts.master')

@section('title', 'จัดการทีเด็ดบอล')

@section('content')
    @section('breadcrumb')
        <li><a onclick="Javascript:void(0);">ทีเด็ดบอล</a></li>
    @endsection

    <section class="content container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                รายการทีเด็ดบอลทั้งหมด&nbsp;<span class="text-primary text-bold" id="result_prediction_total"><i class="fa fa-spinner"></i></span>&nbsp;รายการ
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row mbt-10">
                            <div class="col-lg-3 col-md-4 col-sm-6 col-xs-12">
                                <select class="form-control" name="chk_action" id="chk_action" onchange="doSomethingWithIt($(this), 'prediction')">
                                    <option value="">ทำกับที่เลือก</option>
                                    <option value="delete">ไม่เปิดใช้งาน</option>
                                    <option value="restore">เปิดใช้งาน</option>
                                    {{-- <option value="real-delete">ลบทิ้ง</option> --}}
                                </select>
                            </div>
                        </div>
                        <div class="row" style="display: none;">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="search-box">
                                    <span class="search-form-label">ค้นหา:&nbsp;</span>
                                    <input class="form-control search-form-input" type="text" id="search" placeholder="พิมพ์ชื่อลีก หรือ ชื่อทีม" onkeyup="searchDataTable()" />
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="table-responsive">
                                    <table id="table_prediction" class="table table-striped table-condensed table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center no-sort pr-5">#</th>
                                                <th class="text-left no-sort">ประเภท</th>
                                                <th class="text-left">รายละเอียดทีเด็ด</th>
                                                <th class="text-left">วันที่สร้างรายการ</th>
                                                <th class="text-left">สถานะ</th>
                                                <th class="text-center no-sort">ตัวจัดการ</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom-scripts')
    <script>
        // $.ajaxSetup({
        //     headers: {
        //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        //     }
        // });

        var table;

        $(function() {
            dataTable();
            $('.tooltips').tooltip();
        });

        function searchDataTable(){
            table.ajax.reload();
        }

        function dataTable() {
            table = $('#table_prediction').DataTable({
                "pagingType": "full_numbers",
                "lengthMenu": [[5, 10, 15, 20, 25, 30, 50, 100], [5, 10, 15, 20, 25, 30, 50, 100]],
                "searching": false,
                "processing": false,
                "serverSide": true,
                "ajax": {
                    "url": $('#base_url').val() +'/api/admin/tded/list',
                    "type":"POST",
                    "beforeSend": function(xhr){
                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    },
                    "data": function(d){
                        d.search = $('#search').val();
                    },
                    error: function(response) {
                        // console.log(response);
                        checkReload(response.status, 'prediction');
                    }
                },
                "ordering": true,
                "fnDrawCallback":  function (oSettings, json) {
                    $('.tooltips').tooltip({container: "body"});
                    $('td').removeClass('sorting_1');

                    $('#result_prediction_total').html(oSettings.fnRecordsTotal());
                    $('table.dataTable thead .no-sort.sorting_desc').css('cursor', 'auto');
                    $('table.dataTable thead .no-sort.sorting_desc').css('position', 'unset');
                },
                "createdRow": function(row, data, index){
                    // $('td', row).eq(0).attr('id', 'tr_' + (index + 1));
                    $('td', row).eq(0).addClass('tr-prediction');
                    // console.log(data[0]);
                },
                "pageLength": 10,
                "columns": [
                    { "className":'text-center'},
                    { "className":'text-center' },
                    { "className":'text-left' },
                    { "className":'text-left' },
                    { "className":'text-center' },
                    { "className":'text-center' }
                ],
                "columnDefs": [
                    {
                        "targets"  : 'no-sort',
                        "orderable": false,
                        "order" : []
                    }
                ]
                ,"order": [[0, 'desc']]
            });
        }
    </script>
@stop