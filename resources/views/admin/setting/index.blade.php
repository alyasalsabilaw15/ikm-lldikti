@extends('layouts.app-admin')

@section('title')
    Admin - Setting 
@endsection

@section('content')
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">Management Setting</h5>
        </div>
        <div class="card-body">
            <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#modal-add-setting">Tambah Setting</button>

            <table id="table" class="table table-bordered table-hover" style="width: 100%;">
                <thead>
                    <tr>
                        <th class="text-nowrap text-center" style="width: 50px;">No</th>
                        <th class="text-nowrap text-center" style="width: 100px;">Aksi</th>
                        <th class="text-nowrap text-center">Tahun</th>
                        <th class="text-nowrap text-center">Survei</th>
                        <th class="text-nowrap text-center">Status</th>
                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>

        </div>
    </div>
@endsection

@section('modal')
    @include('admin.setting.modal-add')
@endsection

@section('script')
<script>
    const modalAddSetting = new bootstrap.Modal('#modal-add-setting');

    function fetchData() {
            $('#table').DataTable({
                processing: true,
                serverSide: true,
                destroy: true,
                ordering: false,
                searching: false,
                deferRender: true,
                scrollX: true,
                ajax: {
                    url : "{{ url('/admin/setting/get-list') }}",
                    type: 'post',
                    headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                    data : function(d) {
                        return {
                            ...d,
                            page: parseInt( $('#table').DataTable().page.info().page + 1),
                            search: $('input[name=search]').val()
                        }
                    }
                },
                columns: [
                {"data": "no", "orderable": false, class: "text-center", render: function (data, type, row, meta) {
	                return meta.row + meta.settings._iDisplayStart + 1;
                }},
                {"data": "action", "orderable": false, class: "text-center", render: function (data, type, row, meta) {
                    return `
                        <div class="d-flex justify-content-center">
                            <button 
                                type="button" 
                                class="btn btn-sm btn-danger btn-delete d-flex" 
                                data-id="${row.id}" 
                            >
                                Hapus
                            </button>
                        </div>    
                    `; 
                }},
                {data: 'tahun', class: 'text-nowrap text-center'},
                {data: 'survei_ke', class: 'text-nowrap text-center', render: function (data, type, row, meta) {
                    return `
                        <select class="form-select survei" name="survei_ke" data-id="${row.id}">
                            <option value="1" ${(row.survei_ke == '1' ? 'selected' : '')}>Survei Ke-1</option>
                            <option value="2" ${(row.survei_ke == '2' ? 'selected' : '')}>Survei Ke-2</option>
                            <option value="3" ${(row.survei_ke == '3' ? 'selected' : '')}>Survei Ke-3</option>    
                        </select>
                    `
                }},
                {data: 'status', class: 'text-nowrap text-center', render: function (data, type, row, meta) {
                    return `
                        <form id="form-update-status" method="post">
                            <button type="button" data-id="${row.id}" id="btnUbah" class="${(row.status == 0 ? 'btn btn-outline-danger btn-sm rounded-pill btn-ubah' : 'btn btn-outline-success btn-sm rounded-pill btn-ubah')}">${row.status == 0 ? 'Tidak Aktif' : 'Aktif'}</button>
                        </form>
                    `
                }},
            ],
                order: [[1, 'asc']],
            }).columns.adjust();
        }

    function addSetting() {
        let dataForm = $('#form-add-setting').serialize();

        $.ajax({
            url: "{{ url('admin/setting') }}",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            beforeSend: function() {
                showLoading();
            },
            data: dataForm,
            success: function(response) {
                alertSuccess(response.message);
                $('#form-add-setting')[0].reset();
                modalAddSetting.hide();
                fetchData();
            },
            error: function(xhr, stat, err) {
                swal.close();
                if (xhr.status == 500) {
                    alertError(); 
                }

                else if(xhr.status == 422)
                {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: xhr.responseJSON.message,
                    })
                }
            }
        });
    }
        
    $(document).ready(function() {
        fetchData();

        $.validator.setDefaults({
            debug: true,
            ignore: [],
            highlight: function(element) {
                $(element).closest('.form-select').addClass('is-invalid');
                $(element).siblings('.select2-container').find('.select2-selection').addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).closest('.form-select').removeClass('is-invalid');
                $(element).siblings('.select2-container').find('.select2-selection').removeClass('is-invalid');
            },
            errorPlacement: function(error, element) {
                 if (element.hasClass('setting')) {
                    error.insertBefore(element.parents('.div-setting'));
                } else {
                    error.insertAfter(element);   
                }
            }
        });

        $('#form-add-setting').validate({
            submitHandler: function(form) {
                addSetting();
            },
            rules: {
                tahun: {
                    required: true,
                },
                survei_ke: {
                    required: true,
                },
                status: {
                    required: true,
                },
            },
            messages: {
                tahun: {
                    required: 'Harap isi tahun',
                },
                survei_ke: {
                    required: 'Harap isi survei ke berapa'
                },
                status: {
                    required: 'Harap isi status',
                },
            }
        });

        $(document).on('click', '#btnUbah', function() {  
            let id = $(this).data('id');
            $('#id_status').val(id);

            Swal.fire({
                title: '',
                text: `Apakah anda yakin ingin mengubah status?`,
                icon: 'warning',
                confirmButtonText: 'Ubah',
                cancelButtonText: 'Batal',
                showCancelButton: true
            })
            .then(function(result) {
                if(result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        url: '/admin/setting/update-status/' + id,
                        data: {
                            id: id
                            // Add more fields as needed
                        },
                        success: function(response) {
                            // Handle the success response
                            alertSuccess(response.message);
                            fetchData();
                            // Optionally, update the UI or display a success message
                        },
                        error: function(xhr) {
                            if(xhr.status == 422)
                            {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Oops...',
                                    text: xhr.responseJSON.message,
                                })
                            }
                        }
                    });
                }
            });
        })

        $(document).on('change', '.survei', function() {
            let id = $(this).data('id');
            let survei_ke = $(this).val();
      
            Swal.fire({
                title: '',
                text: `Apakah anda yakin ingin mengubah survei?`,
                icon: 'warning',
                confirmButtonText: 'Ubah',
                cancelButtonText: 'Batal',
                showCancelButton: true
            })
            .then(function(result) {
                if(result.isConfirmed) {
                    $.ajax({
                        type: 'POST',
                        headers: {'X-CSRF-TOKEN': '{{ csrf_token() }}'},
                        url: '/admin/setting/update-survei/' + id,
                        data: {
                            id: id,
                            survei_ke: survei_ke
                            // Add more fields as needed
                        },
                        success: function(response) {
                            // Handle the success response
                            alertSuccess(response.message);
                            fetchData();
                            // Optionally, update the UI or display a success message
                        },
                        error: function(xhr) {
                            // Handle the error response
                            alertError(xhr.responseText);
                            // Optionally, update the UI or display an error message
                        }
                    });
                }
            });
        })

        // Button delete onclick event handler 
        $(document).on('click', '.btn-delete', function() {
            let id = $(this).data('id');

            Swal.fire({
                title: '',
                text: `Apakah anda yakin akan menghapus setting?`,
                icon: 'warning',
                confirmButtonText: 'Hapus',
                cancelButtonText: 'Batal',
                showCancelButton: true
            })
            .then(function(result) {
                if(result.isConfirmed) {
                    $.ajax({
                        url: "{{ url('admin/setting') }}" + "/" + id,
                        type: 'delete',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        beforeSend: function() {
                            showLoading();
                        },
                        success: function(response) {
                            alertSuccess(response.message);
                            fetchData();
                        },
                        error: function(xhr, stat, err) {
                            swal.close();
                            if (xhr.status == 500) {
                                alertError(); 
                            }
                        }
                    });
                }
            });
        });
    });
</script>
@endsection