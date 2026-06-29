<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileService
{
    public function upload(UploadedFile $file, string $path = 'uploads'): string
    {
        return $file->store($path, 'public');
    }

    public function uploadPatientPhoto(UploadedFile $file): string
    {
        return $this->upload($file, 'patients');
    }

    public function uploadLabResult(UploadedFile $file): string
    {
        return $this->upload($file, 'lab_results');
    }

    public function uploadReceipt(UploadedFile $file): string
    {
        return $this->upload($file, 'receipts');
    }

    public function uploadSignature(UploadedFile $file): string
    {
        return $this->upload($file, 'signatures');
    }

    public function delete(string $path): bool
    {
        if (Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }

    public function url(string $path): string
    {
        return Storage::disk('public')->url($path);
    }
}
