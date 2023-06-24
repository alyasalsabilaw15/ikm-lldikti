<?php

namespace App\Http\Controllers;

use App\Models\Pertanyaan;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use App\Contracts\KuesionerContract;
use App\Contracts\PertanyaanContract;

class KuesionerController extends Controller
{
    public function __construct(
        private PertanyaanContract $pertanyaanService,
        private KuesionerContract $kuesionerService
    ) {
        //
    }

    public function ak1()
    {
        $questions = $this->pertanyaanService->getAllPertanyaan();
        
        return view('kuesioner.index', [
            'questions' => $questions,
            'namalayanan' => 'Survey Kepuasan',
            'id_layanan' => 1
        ]);
    }

    // public function rekomPassport()
    // {
    //     $questions = $this->pertanyaanService->getAllPertanyaan();
        
    //     return view('kuesioner.index', [
    //         'questions' => $questions,
    //         'namalayanan' => 'Rekom Passport',
    //         'id_layanan' => 2
    //     ]);
    // }

    // public function pelatihan()
    // {
    //     $questions = $this->pertanyaanService->getAllPertanyaan();
        
    //     return view('kuesioner.index', [
    //         'questions' => $questions,
    //         'namalayanan' => 'Pelatihan',
    //         'id_layanan' => 3
    //     ]);
    // }

    // public function lpk()
    // {
    //     $questions = $this->pertanyaanService->getAllPertanyaan();
        
    //     return view('kuesioner.index', [
    //         'questions' => $questions,
    //         'namalayanan' => 'LPK',
    //         'id_layanan' => 4
    //     ]);
    // }

    // public function pencatatanPerusahaan()
    // {
    //     $questions = $this->pertanyaanService->getAllPertanyaan();
        
    //     return view('kuesioner.index', [
    //         'questions' => $questions,
    //         'namalayanan' => 'Pencatatan Perusahaan',
    //         'id_layanan' => 5
    //     ]);
    // }

    // public function perselisihanHubunganIndustrial()
    // {
    //     $questions = $this->pertanyaanService->getAllPertanyaan();
        
    //     return view('kuesioner.index', [
    //         'questions' => $questions,
    //         'namalayanan' => 'Perselisihan Hubungan Industrial',
    //         'id_layanan' => 6
    //     ]);
    // }


        public function store(Request $request): JsonResponse
    {
        $this->validate($request, [
            'nama_lengkap' => 'required|string',
            'layanan' => 'required|string|in:1,2',
            'email' => 'required|string|unique:tbl_responden',
            'jabatan' => 'required|string',
            'answers' => 'required|array',
            'id_layanan' => 'required|numeric',
            'saran' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            $add_responden = $this->kuesionerService->addResponden(
                $request->nama_lengkap,
                $request->pt,
                $request->jabatan,
                $request->email,
                $request->id_layanan,
                $request->nama_layanan,
                $request->saran,
               
            );   

            $add_kuesioner = $this->kuesionerService->addKuesioner(
                $add_responden->id,
                1, 
                $request->answers
            );

            $add_saran = $this->kuesionerService->addSaran(
                $add_responden->id,
                $request->saran
            );
            

            DB::commit();

            return response()->json([
                'message' => 'Proses berhasil'
            ], 201);
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
}
