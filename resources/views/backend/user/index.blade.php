@extends('backend/layouts.master')

@section('title', 'จัดการผู้ใช้งาน')

@section('content')
    @section('breadcrumb')
        <li><a onclick="Javascript:void(0);">ผู้ใช้งาน</a></li>
    @endsection

    <section class="content container-fluid">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <div class="row">
                            <div class="col-md-9 col-sm-9 pddt7">
                                รายการผู้ใช้งานทั้งหมด&nbsp;<span class="text-primary text-bold" id="result_user_total"><i class="fa fa-spinner"></i></span>&nbsp;รายการ
                            </div>
                            <div class="col-md-3 col-sm-3 text-right">
                                <a href="{{ URL::to('/') }}/admin/settings/user/create">
                                    <span class="btn btn-success add-btn"><i class="fa fa-plus"></i>&nbsp;เพิ่มข้อมูล</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="search-box">
                                    <span class="search-form-label">ค้นหา:&nbsp;</span>
                                    <input class="form-control search-form-input" type="text" id="search" onkeyup="searchDataTable()" />
                                </div>
                            </div>
                        </div>
                        <div class="row no-marg">
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 no-pdd">
                                <div class="table-responsive">
                                    <table id="table_user" class="table table-striped table-condensed table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th class="text-center">&nbsp;&nbsp;ID&nbsp;&nbsp;</th>
                                                <th class="text-left">บทบาท</th>
                                                <th class="text-left">Username</th>
                                                <th class="text-left">ชื่อ</th>
                                                <th class="text-left">สกุล</th>
                                                <th class="text-left">ชื่อในเกมส์</th>
                                                <th class="text-center">สถานะ</th>
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
        var table;

        $(function() {
            dataTable();
            $('.tooltips').tooltip();
        });

        function searchDataTable(){
            table.ajax.reload();
        }

        function dataTable() {
            table = $('#table_user').DataTable({
                "pagingType": "full_numbers",
                "lengthMenu": [[10,15,20, 25, 30, 50, 100], [10,15,20, 25, 30, 50, 100]],
                "searching": false,
                "processing": false,
                "serverSide": true,
                "ajax": {
                    "url": $('#base_url').val() +'/api/admin/user/list',
                    "type":"POST",
                    "beforeSend": function(xhr){
                        xhr.setRequestHeader('X-CSRF-TOKEN', $('meta[name="csrf-token"]').attr('content'));
                    },
                    "data": function(d){
                        d.search = $('#search').val();
                    },
                    error: function(response) {
                        // console.log(response);
                        checkReload(response.status, 'user');
                    }
                },
                "ordering": true,
                "fnDrawCallback":  function (oSettings, json) {
                    $('.tooltips').tooltip({container: "body"});
                    $('td').removeClass('sorting_1');

                    $('#result_user_total').html(oSettings.fnRecordsTotal());
                },
                "createdRow": function(row, data, index){
                    // $('td', row).eq(8).addClass($('td', row).eq(8).find('span').attr('class'));
                },
                "pageLength": 10,
                "columns": [
                    { "className":'text-center'},
                    { "className":'text-left' },
                    { "className":'text-left' },
                    { "className":'text-left' },
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
                ,"order": [[0, 'asc']]
            });
        }

        function deleteUser(id) {
            Swal.fire({
                title: 'ยืนยันการลบรายการ?',
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.value) {
                    callDelete(id);
                }
            });
        }

        function callDelete(id) {
            Swal.fire({
                title: 'Loading..',
                type: 'info',
                onOpen: () => {
                    swal.showLoading();
                    const formData = new FormData();
                    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                    formData.append('id', id);

                    $.ajax({
                        url: $('#base_url').val() + '/api/admin/user/delete',
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            // console.log(response);
                            swal.close();

                            if (response.total == 1) {
                                saveSuccessReload();
                            } else {
                                showWarning('Warning!', 'เกิดความผิดพลาดในระบบ กรุณาตรวจสอบอีกครั้ง');
                            }

                        }
                    });
                }
            });
            return false;
        }

        function restoreUser(id) {
            Swal.fire({
                title: 'ยืนยันการทำรายการ?',
                type: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33'
            }).then((result) => {
                if (result.value) {
                    callRestore(id);
                }
            });
        }

        function callRestore(id) {
            Swal.fire({
                title: 'Loading..',
                type: 'info',
                onOpen: () => {
                    swal.showLoading();
                    const formData = new FormData();
                    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
                    formData.append('id', id);

                    $.ajax({
                        url: $('#base_url').val() + '/api/admin/user/restore',
                        type: 'POST',
                        data: formData,
                        dataType: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        success: function (response) {
                            // console.log(response);
                            swal.close();

                            if (response.total == 1) {
                                saveSuccessReload();
                            } else {
                                showWarning('Warning!', 'เกิดความผิดพลาดในระบบ กรุณาตรวจสอบอีกครั้ง');
                            }

                        }
                    });
                }
            });
            return false;
        }
    </script>
@stop