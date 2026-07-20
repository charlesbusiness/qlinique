<?php

namespace App\Livewire\TreatmentComponents\Concerns;

use Illuminate\Http\UploadedFile;

trait HasBase64Signature
{
    private function base64ToUploadedFile(string $base64, string $filename = 'drawn_sig.png'): UploadedFile
    {
        $decoded = base64_decode(substr($base64, strpos($base64, ',') + 1));
        $tmpPath = tempnam(sys_get_temp_dir(), 'sig_') . '.png';
        file_put_contents($tmpPath, $decoded);

        return new UploadedFile($tmpPath, $filename, 'image/png', null, true);
    }
}
