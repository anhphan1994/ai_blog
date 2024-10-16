<?php

namespace App\Repositories\Interfaces;

interface BlogPostRepositoryInterface
{
    public function getAll($params);
    public function getById($id);
    public function create(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function getAllStatus();
    public function getAllPeriod();
    public function duplicate($id);
    public function getBLogSEOSetting($id);
}
