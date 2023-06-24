<?php 

declare(strict_types=1);

namespace App\Contracts;

interface SettingContract 
{
    public function getListPagination();

    public function addSetting(array $data);

    public function updateSetting(array $data, $id);

    public function deleteSetting($id);
}