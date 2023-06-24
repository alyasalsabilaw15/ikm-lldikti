<?php 

declare(strict_types=1);

namespace App\Services;

use App\Models\Kuesioner;
use App\Models\Responden;
use Illuminate\Support\Facades\DB;
use App\Contracts\KuesionerContract;

final class KuesionerService implements KuesionerContract
{
    public function addResponden(
        $nama_responden,
        $pt,
        $jabatan,
        $email,
        $id_layanan,
        $nama_layanan,
        $saran
    )
    {
        // Check if email already exists in the past 3 months
        $threeMonthsAgo = date('Y-m-d H:i:s', strtotime('-3 months'));
        $existingResponden = Responden::where('email', $email)
            ->where('created_at', '>', $threeMonthsAgo)
            ->first();

        if ($existingResponden) {
            // Email already exists within the past 3 months, display error message
            return 'Anda telah mengisi survei sebelumnya.';
        }

        $responden = Responden::create([
            'nama_responden' => $nama_responden, 
            'pt' => $pt, 
            'jabatan' => $jabatan, 
            'email' => $email,
            'id_layanan' => $id_layanan,
            'nama_layanan'=>$nama_layanan,
            'saran'=>$saran
        ]);

        return $responden;
    }

    public function addSaran($id_responden, $saran)
    {
        $responden = Responden::find($id_responden);

        if ($responden) {
            $responden->saran = $saran;
            $responden->save();
        }
    }

    public function addKuesioner(
        $id_responden,
        $id_layanan,
        array $answers
    ) 
    {
        $data_insert = [];
        $now = date('Y-m-d H:i:s');
        $setting = DB::table('tbl_setting')->where('status', '1')->first();

        foreach ($answers as $answer) {
            $explode_answer = explode('_', $answer);
            $id_pertanyaan = $explode_answer[0];
            $id_jawaban = $explode_answer[1];

            $data_insert[] = [
                'id_responden' => $id_responden,
                'id_layanan' => $id_layanan,
                'id_pertanyaan' => $id_pertanyaan,
                'id_jawaban' => $id_jawaban,
                'id_setting' => $setting->id,
                'created_at' => $now
            ];
        }

        // Use query builder for better performance
        Kuesioner::insert($data_insert);
    }

    public function getListPagination()
    {
        $date_from = request()->date_from ? date('Y-m-d', strtotime(str_replace('/', '-', request()->date_from))) : null;
        $date_to = request()->date_to ? date('Y-m-d', strtotime(str_replace('/', '-', request()->date_to))) : null;
        $search = request()->search;
        $layanan = request()->layanan;
        $nilai = request()->nilai;
        $limit = request('length') ? intval(request('length')) : 10; // param => length 
        $offset = request('start') ? intval(request('start')) : 0; // param => start 

        $query = "SELECT 
                    a.id,
                    a.nama_responden,
                    a.email,
                    a.pt,
                    l.namalayanan, 
                    AVG(c.nilai) AS avg_nilai,
                    a.created_at,
                    CASE 
                        WHEN avg(c.nilai) < 2 THEN 'Sangat Buruk'
                        WHEN avg(c.nilai) >= 2 AND avg(c.nilai) <= 2.5 THEN 'Buruk'
                        WHEN avg(c.nilai) > 2.5 AND avg(c.nilai) < 3 THEN 'Cukup'
                        WHEN avg(c.nilai) >= 3 AND avg(c.nilai) < 3.6 THEN 'Baik'
                        ELSE 'Sangat Baik'
                    END AS nilai
                FROM tbl_responden a 
                INNER JOIN tbl_kuesioner b ON a.id = b.id_responden
                INNER JOIN tbl_layanan l ON l.id = a.id_layanan
                INNER JOIN tbl_jawaban c ON b.id_jawaban = c.id 
                WHERE true ";

        // TOTAL 
        $queryTotal = "SELECT 
                            a.id
                        FROM tbl_responden a 
                        INNER JOIN tbl_kuesioner b ON a.id = b.id_responden
                        INNER JOIN tbl_jawaban c ON b.id_jawaban = c.id 
                        WHERE true ";

        if ($date_from && $date_to) {
            $query .= " AND DATE(a.created_at) BETWEEN '$date_from' AND '$date_to'";
            $queryTotal .= " AND DATE(a.created_at) BETWEEN '$date_from' AND '$date_to'";
        }
        if ($layanan && $layanan != '') {
            $query .= " AND a.id_layanan = $layanan";
            $queryTotal .= " AND a.id_layanan = $layanan";
        }

        $query .= " GROUP BY a.id, a.id_layanan, a.nama_responden, a.pt, a.email, l.namalayanan, a.created_at, nilai";
        
        if ($nilai && $nilai != '') {
            if ($nilai === 'sangat_buruk') {
                $query .= " HAVING avg(c.nilai) < 2 ";
            } else if ($nilai === 'buruk') {
                $query .= " HAVING avg(c.nilai) >= 2 AND avg(c.nilai) <= 2.5 ";
            } else if ($nilai === 'cukup') {
                $query .= " HAVING  avg(c.nilai) > 2.5 AND avg(c.nilai) < 3 ";
            } else if ($nilai === 'baik') {
                $query .= " HAVING avg(c.nilai) >= 3 AND avg(c.nilai) < 3.6 ";
            } else {
                $query .= " HAVING avg(c.nilai) > 3.6";
            }
        }

        $query .= " ORDER BY avg_nilai DESC
        LIMIT $limit OFFSET $offset";

        $queryTotal .= " GROUP BY a.id";

        $dataList = DB::select($query);
        $dataTotal = DB::select($queryTotal);

        return [
            'data' => $dataList,
            'total' => count($dataTotal),
        ];
        
    }

    public function findRespondenById($id)
    {
        return Responden::with(['layanan', 'kuesioners'])
                ->where('id', $id)
                ->first(); 
    }

    public function updateKuesionerByRespondenId($id_responden, array $answers)
    {
        $now = date('Y-m-d H:i:s');

        foreach ($answers as $answer) {
            $explode_answer = explode('_', $answer);
            $id_pertanyaan = $explode_answer[0];
            $id_jawaban = $explode_answer[1];
            $id_kuesioner = $explode_answer[2];

            DB::table('tbl_kuesioner')
                ->where('id', $id_kuesioner)
                ->update([
                    'id_jawaban' => $id_jawaban,
                    'updated_at' => $now
                ]);
        }
    }

    public function getDataExcelExport($date_from = null, $date_to = null, $jenis_layanan = null)
    {
        $query = "
            SELECT 
                a.id AS id_responden,
                b.id_pertanyaan,
                c.nilai,
                d.unsur
            FROM tbl_responden a
            JOIN tbl_kuesioner b ON b.id_responden = a.id
            JOIN tbl_jawaban c ON b.id_jawaban = c.id
            JOIN tbl_pertanyaan d ON b.id_pertanyaan = d.id
            WHERE 
                a.id is NOT NULL
        ";

        if (
            $date_from != null && $date_from != '-' 
            && $date_to != null && $date_to != '-'
        ) {
            $date_from_format = date('Y-m-d', strtotime($date_from));
            $date_to_format = date('Y-m-d', strtotime($date_to));

            $query .= " 
                AND DATE(a.created_at) BETWEEN '$date_from_format' AND '$date_to_format'
            ";
        }

        if ($jenis_layanan != null && $jenis_layanan != '') {
            $query .= " 
                AND a.id_layanan = $jenis_layanan 
            ";
        }

        return DB::select($query);
    }
}
