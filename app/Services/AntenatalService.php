<?php

namespace App\Services;

use App\Models\AntenatalRecord;
use App\Repositories\AntenatalRepository;
use Illuminate\Support\Facades\DB;

class AntenatalService
{
    public function __construct(
        protected AntenatalRepository $repository
    ) {}

    public function register(array $data): AntenatalRecord
    {
        return DB::transaction(function () use ($data) {
            return $this->repository->create($data);
        });
    }

    public function addPartograph(AntenatalRecord $antenatal, array $data)
    {
        return $antenatal->partographs()->create($data);
    }

    public function updateRiskLevel(AntenatalRecord $antenatal, string $level): AntenatalRecord
    {
        return $this->repository->update($antenatal, ['risk_level' => $level]);
    }
}
