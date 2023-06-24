<?php 

declare(strict_types=1);

namespace App\Contracts;

interface KuesionerContract 
{
    public function addResponden(
        $nama_responden,
        $pt,
        $jabatan,
        $email,
        $id_layanan,
        $nama_layanan,
        $saran
    );

    public function addKuesioner(
        $id_responden,
        $id_layanan,
        array $answers
    );

    public function addSaran(
        $id_responden,
        $saran
    );

    public function getListPagination();

    public function findRespondenById($id);

    public function updateKuesionerByRespondenId($id_responden, array $answers);

    public function getDataExcelExport($date_from = null, $date_to = null, $jenis_layanan = null);
}
