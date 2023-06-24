@extends('layouts.app')

@section('css')
<style>
    .nilai > div > label {
        display: flex;
        justify-content: center;
        align-items: center;
        border: 1px solid #999;
        width: 50px;
        height: 50px;
        cursor: pointer;
    }
    .nilai > .nilai-div:hover {
        background-color: #e7ebfc; 
    }
    label.error {
        font-weight: 500;
    }
    #scroll-down {
        position: fixed;
        bottom: 40%;
        right: 50%;
        width: 50px;
        height: 50px;
        opacity: 1;
        z-index: 99;
        /* color: #607D8B; */
        cursor: pointer;
        line-height: 45px;
        text-align: center;
        border-radius: 5px;
        background-color: transparent;
    }
</style>
@endsection

@section('content')
    <input type="hidden" name="id_layanan" id="id_layanan" value="{{ $id_layanan }}">

    <div class="card">
        <div class="card-header">
            <h4 class="mb-0 card-title">{{ $namalayanan }}</h4>
        </div>
        <div class="card-body">
            <button type="button" class="btn btn-primary mb-5" data-bs-toggle="modal" id="btn-isi-survey" data-bs-target="#modal-isi-survey">Isi Survey</button>
            
            {{-- Survey Question --}}
            @include('kuesioner.survey-question')
        </div>
    </div>

    {{-- Modal --}}
    <div class="modal fade" id="modal-isi-survey" aria-labelledby="modal-isi-survey" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header px-4">
                    <h5 class="mb-0 modal-title">Isi Data</h5>
                    <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" data-bs-target="#modal-isi-survey"></button>
                </div>
                <div class="modal-body p-4">
                    <form method="post" id="form-isi-data" spellcheck="false">
                        <div class="row">
                            <div class="col-lg-6 col-sm-12">
                                <div class="mb-3">
                                    <label class="col-form-label">Nama Lengkap</label>
                                    <input type="text" name="nama_lengkap" id="nama_lengkap" class="form-control" placeholder="Masukkan Nama Lengkap" maxlength="50">
                                </div>
                                <!-- <div class="mb-3">
                                    <label class="col-form-label">Jenis Kelamin</label>
                                    <select name="jenis_kelamin" id="jenis_kelamin" class="form-control form-select">
                                        <option value="1">Laki - Laki</option>
                                        <option value="2">Perempuan</option>
                                    </select>
                                </div> --->
                                <div class="mb-3">
                                    <label class="col-form-label">Perguruan Tinggi</label>
                                    <input type="text" name="pt" id="pt" class="form-control" placeholder="Masukkan Asal Perguruan Tinggi" maxlength="250">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="col-form-label">Alamat Email</label>
                                    <input type="email" name="email" id="email" class="form-control" placeholder="Masukkan email">
                                    <small id="email" class="form-text text-muted">Kami tidak akan pernah membagikan email Anda dengan orang lain.</small>
                                </div>
                                <div class="mb-3">
                                    <label class="col-form-label">Jabatan</label>
                                    <input type="text" name="jabatan" id="jabatan" class="form-control" placeholder="Masukkan Jabatan" maxlength="250">
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-12">
                                <!-- <div class="mb-3">
                                    <label class="col-form-label">Jabatan</label>
                                    <input type="text" name="jabatan" id="jabatan" class="form-control" placeholder="Masukkan jabatan" maxlength="50">
                                </div> -->
                                <div class="mb-3">
                                    <label class="col-form-label">Pernah Menggunakan Layanan LLDIKTI IV ?</label>
                                    <select name="layanan" id="layanan" class="form-control form-select">
                                        <option value="">Pilih Jawaban</option>
                                        <option value="1">Ya</option>
                                        <option value="2">Tidak</option>
                                    </select>
                                    <!-- <input type="text" name="no_hp" id="no_hp" class="form-control" placeholder="Masukkan Nomor Handphone" maxlength="16"> -->
                                </div>
                                <!-- <div class="mb-3">
                                    <label class="col-form-label">Pendidikan</label>
                                    <input type="text" name="pendidikan" id="pendidikan" class="form-control" placeholder="Masukkan Pendidikan" maxlength="100">
                                </div> -->

                                <div id="detail-layanan" style="display: none;" class="mb-3">
                                    <label class="col-form-label" for="namalayanan">Layanan Apa yang Anda Pernah Terima?</label>
                                    <input type="text" id="nama_layanan" name="nama_layanan" class="form-control">
                                </div>
                                
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-2">
                            <button type="button" class="btn btn-danger me-3" data-bs-dismiss="modal" data-bs-target="#modal-isi-survey">Batal</button>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


@endsection

@section('script')
<script>
        const question1Input = document.getElementById('layanan');
        const petDetailsContainer = document.getElementById('detail-layanan');

        question1Input.addEventListener('change', function() {
            if (question1Input.value === '1') {
            petDetailsContainer.style.display = 'block';
            } else {
            petDetailsContainer.style.display = 'none';
            }
        });
    function addKuesioner() {
        let respondenData = $('#form-isi-data').serialize();
        let kuesionerData = $('#form-isi-survey').serialize();
        
        $.ajax({
            url: "{{ url('kuesioner/add-kuesioner') }}",
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            beforeSend: function() {
                showLoading();
            },
            data: respondenData + '&' + kuesionerData + '&id_layanan=' + $('#id_layanan').val(),
            success: function(response) {
                $('#form-isi-data')[0].reset();
                $('#form-isi-survey')[0].reset();
                $("#survey-question").prop('hidden', true);
                $('#btn-isi-survey').show();
                Swal.fire({
                title: '',
                text: `Berhasil melakukan survey`,
                icon: 'success',
            })
            .then(function(result) {
                if(result.isConfirmed) {
                    window.location.href = '{{ url('/') }}';
                }
            });
                
            },
            error: function(xhr, stat, err) {
                swal.close();
                if (xhr.status == 500) {
                    alertError(); 
                } else if (xhr.status === 422)
                {
                    alertError(xhr.responseJSON.message);
                }
            }
        });
    }

    $(document).ready(function() {
        const modal = new bootstrap.Modal('#modal-isi-survey');

        // $(window).scroll(function(){
		// 	if ($(this).scrollTop() > 100) {
		// 		$('#scroll-down').fadeIn();
		// 	} else {
		// 		$('#scroll-down').fadeOut();
		// 	}
		// });

		$('#scroll-down').click(function(){
            console.log($(document).height())
			$('html, body').animate({scrollTop : $(document).height()}, 350);
			return false;
		});

        // $('#form-isi-survey').on('submit', function(e) {
        //     e.preventDefault();

        //     modal.hide();

        //     $("#survey-question").prop('hidden', false);
        //     $('#btn-isi-survey').hide();

        //     // let nilaiKepuasan = $(`[name="nilai_kepuasan"]:checked`).val();
        //     // console.log(nilaiKepuasan)

        // });  
        
        // $('input[type=radio][name=nilai_kepuasan]').change(function() {
        //     $('div.nilai-div').removeClass('bg-primary text-white')
        //     $(this).parent('div').addClass('bg-primary text-white');
        // });

        $.validator.setDefaults({
            debug: true,
            ignore: [],
            highlight: function(element) {
                $(element).closest('.form-control').addClass('is-invalid');
                $(element).siblings('.select2-container').find('.select2-selection').addClass('is-invalid');
            },
            unhighlight: function(element) {
                $(element).closest('.form-control').removeClass('is-invalid');
                $(element).siblings('.select2-container').find('.select2-selection').removeClass('is-invalid');
            },
            errorPlacement: function(error, element) {
                if (element[0].name== 'nilai_kepuasan') {
                    error.insertAfter(element.parents('.d-flex.nilai'));
                } else if (element.hasClass('answer')) {
                    error.insertAfter(element.parents('.soal'));
                } else {
                    error.insertAfter(element);   
                }
            }
        });

        $("#form-isi-data").validate({
            submitHandler: function(form) {
                modal.hide();
                $('#btn-isi-survey').hide();
                $("#survey-question").prop('hidden', false);
            },
            rules: {
                nama_lengkap: {
                    required: true,
                    minlength: 3,
                    maxlength: 50
                },
                layanan: {
                    required: true 
                },
                email: {
                    required: true,
                    email: true
                },
                jabatan: {
                    required: true,
                    maxlength: 250
                }
            },
            messages: {
                nama_lengkap: {
                    required: 'Harap isi nama lengkap',
                    minlength: 'Nama lengkap minimal 3 karakter',
                    maxlength: 'Nama lengkap minimal 50 karakter'
                },
                layanan: {
                    required: 'Harap pilih status penggunaan layanan' 
                },
                email: {
                    required: 'Harap isi alamat email.',
                    email: 'Harap masukkan alamat email yang valid.'
                },
                jabatan: {
                    required: 'Harap isi jabatan',
                    maxlength: 'Jabatan maksimal 250 karakter'
                }
            }
        });

        $('#form-isi-survey').validate({
            submitHandler: function(form) {
                addKuesioner();
            },
        });

        $('[name^="answers"]').each(function() {
            $(this).rules('add', {
                required: true,
                messages: {
                    required: "Harap pilih jawaban.",
                }
            });
        });


    });
</script>
@endsection