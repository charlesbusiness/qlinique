<?php

namespace App\Services;

use App\Enums\AccountType;
use App\Models\Patient;
use App\Repositories\PatientRepository;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;

class PatientService
{
    public function __construct(
        protected PatientRepository $repository,
        protected FileService $fileService,
    ) {}

    public function register(array $data, ?UploadedFile $photo = null, ?UploadedFile $signatureFile = null): Patient
    {
        return DB::transaction(function () use ($data, $photo, $signatureFile) {
            if ($photo) {
                $data['photo_path'] = $this->fileService->uploadPatientPhoto($photo);
            }
            if ($signatureFile) {
                $data['signature'] = $this->fileService->uploadSignature($signatureFile);
            }

            return $this->repository->create($data);
        });
    }

    public function update(Patient $patient, array $data, ?UploadedFile $photo = null, ?UploadedFile $signatureFile = null): Patient
    {
        return DB::transaction(function () use ($patient, $data, $photo, $signatureFile) {
            if ($photo) {
                if ($patient->photo_path) {
                    $this->fileService->delete($patient->photo_path);
                }
                $data['photo_path'] = $this->fileService->uploadPatientPhoto($photo);
            }
            if ($signatureFile) {
                if ($patient->signature && in_array($patient->signature_type, ['drawn', 'uploaded'])) {
                    $this->fileService->delete($patient->signature);
                }
                $data['signature'] = $this->fileService->uploadSignature($signatureFile);
            }

            return $this->repository->update($patient, $data);
        });
    }

    public function search(string $query)
    {
        return $this->repository->search($query);
    }

    public function findByFileNumber(string $fileNumber): ?Patient
    {
        return $this->repository->findByFileNumber($fileNumber);
    }
}
