<div class="modal fade" id="modal-add-setting">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header px-4">
                <h5 class="mb-0 modal-title">Tambah Setting</h5>
                <button type="button" class="btn-close btn-sm" data-bs-dismiss="modal" data-bs-target="#modal-add-setting"></button>
            </div>
            <div class="modal-body px-4">
                <form method="post" id="form-add-setting" spellcheck="false">
                    @csrf
                    <div class="mb-3">
                        <label class="col-form-label" style="font-weight: 600;">Tahun <span class="text-danger">*</span></label>
                        <select name="tahun" class="form-select" id="tahun">
                            @php
                                $currentYear = date('Y');
                                $endYear = $currentYear + 5;
                            @endphp
                            @for ($year = $endYear; $year >= $currentYear; $year--)
                                <option hidden value="">Pilih Tahun</option>
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label" style="font-weight: 600;">Survei Ke <span class="text-danger">*</span></label>
                        <select name="survei_ke" class="form-select" id="survei_ke">
                            <option hidden value="">Pilih Survei</option>
                            <option value="1">Survei Ke-1</option>
                            <option value="2">Survei Ke-2</option>
                            <option value="3">Survei Ke-3</option>
                        </select> 
                    </div>
                    <div class="mb-3">
                        <label class="col-form-label" style="font-weight: 600;">Status <span class="text-danger">*</span></label>
                        <select name="status" class="form-select" id="status">
                            <option hidden value="">Pilih Status</option>                           
                            <option value="1">Aktif</option>
                            <option value="0">Tidak Aktif</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="button" data-bs-dismiss="modal" data-bs-target="#modal-add-setting" class="btn btn-danger me-3">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>