<?php 

declare(strict_types=1);

namespace App\Services;

use App\Models\Setting;
use App\Contracts\SettingContract;
use Illuminate\Support\Facades\DB;

final class SettingService implements SettingContract
{
    public function getListPagination()
    {
        $search = request()->get('search') ? strtolower(request()->get('search')) : null;
        $limit = request()->input('length') ? request()->input('length') : 0;
        
        return Setting::paginate($limit)->toArray();
    }

    public function addSetting(array $data)
    {
        return Setting::create([
            'kode' => $data['kode'],
            'jawaban' => $data['jawaban'],
            'nilai' => $data['nilai'],
        ]);
    }

    public function updateSetting(array $data, $id)
    {
        return Setting::where('id', $id)
            ->update([
                'kode' => $data['kode_edit'],
                'jawaban' => $data['jawaban_edit'],
                'nilai' => $data['nilai_edit'],
            ]);   
    }

    public function deleteSetting($id)
    {
        $this->deleteJawabanPertanyaan($id);

        Setting::where('id', $id)->delete();
    }

    private function deleteJawabanPertanyaan($jawaban_id): void
    {
        DB::table('pertanyaan_jawaban')->where('jawaban_id', $jawaban_id)->delete();
    }

}