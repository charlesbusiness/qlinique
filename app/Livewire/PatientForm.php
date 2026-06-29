<?php

namespace App\Livewire;

use App\Enums\AccountType;
use App\Models\Patient;
use App\Services\PatientService;
use Illuminate\Http\UploadedFile;
use Livewire\Component;
use Livewire\WithFileUploads;

class PatientForm extends Component
{
    use WithFileUploads;

    public ?Patient $patient = null;
    public string $name = '';
    public string $gender = '';
    public string $date_of_birth = '';
    public string $phone = '';
    public string $email = '';
    public string $address = '';
    public string $occupation = '';
    public string $marital_status = '';
    public $photo = null;
    public string $account_type = 'individual';
    public ?string $account_holder_id = null;
    public array $next_of_kin = [];
    public array $consent = [];
    public string $religion = '';
    public string $denomination = '';

    public $existingPhoto = null;

    public string $signature_type = '';
    public string $signature = '';
    public $signature_upload = null;

    public ?string $existingSignature = null;
    public ?string $existingSignatureType = null;

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'required|date|before:today',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:1000',
            'occupation' => 'nullable|string|max:255',
            'marital_status' => 'nullable|string|max:50',
            'photo' => 'nullable|image|max:2048',
            'account_type' => 'required|in:individual,family,corporate',
            'religion' => 'nullable|string|max:255',
            'denomination' => 'nullable|string|max:255',
            'signature_type' => 'nullable|in:typed,drawn,uploaded',
            'signature' => 'nullable|string',
            'signature_upload' => 'nullable|image|max:2048',
        ];
    }

    public function mount(?Patient $patient = null): void
    {
        if ($patient?->id) {
            $this->patient = $patient;
            $this->name = $patient->name ?? '';
            $this->gender = $patient->gender ?? '';
            $this->date_of_birth = $patient->date_of_birth?->format('Y-m-d') ?? '';
            $this->phone = $patient->phone ?? '';
            $this->email = $patient->email ?? '';
            $this->address = $patient->address ?? '';
            $this->occupation = $patient->occupation ?? '';
            $this->marital_status = $patient->marital_status ?? '';
            $this->account_type = $patient->account_type ?? 'individual';
            $this->next_of_kin = is_array($patient->next_of_kin) ? $patient->next_of_kin : [];
            $this->consent = is_array($patient->consent) ? $patient->consent : [];
            $this->religion = $patient->religion ?? '';
            $this->denomination = $patient->denomination ?? '';
            $this->existingPhoto = $patient->photo_path;
            $this->signature_type = $patient->signature_type ?? '';
            $this->signature = $patient->signature ?? '';
            $this->existingSignature = $patient->signature;
            $this->existingSignatureType = $patient->signature_type;
        }
    }

    public function save(PatientService $patientService): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'phone' => $this->phone ?: null,
            'email' => $this->email ?: null,
            'address' => $this->address ?: null,
            'occupation' => $this->occupation ?: null,
            'marital_status' => $this->marital_status ?: null,
            'account_type' => $this->account_type,
            'next_of_kin' => $this->next_of_kin,
            'consent' => $this->consent,
            'religion' => $this->religion ?: null,
            'denomination' => $this->denomination ?: null,
            'signature_type' => $this->signature_type ?: null,
            'signature' => null,
        ];

        $photoFile = $this->photo?->getRealPath() ? $this->photo : null;
        $signatureFile = null;

        if ($this->signature_type === 'typed') {
            $data['signature'] = $this->signature;
        } elseif ($this->signature_type === 'drawn' && $this->signature && str_starts_with($this->signature, 'data:image')) {
            $signatureFile = $this->base64ToUploadedFile($this->signature);
        } elseif ($this->signature_type === 'uploaded' && $this->signature_upload) {
            $signatureFile = $this->signature_upload;
        }

        if ($this->patient) {
            $patientService->update($this->patient, $data, $photoFile, $signatureFile);
            session()->flash('status', 'Patient updated successfully.');
        } else {
            $patientService->register($data, $photoFile, $signatureFile);
            session()->flash('status', 'Patient registered successfully.');
        }

        $this->redirect(route('patients.index'), navigate: true);
    }

    private function base64ToUploadedFile(string $base64): UploadedFile
    {
        $decoded = base64_decode(substr($base64, strpos($base64, ',') + 1));
        $tmpPath = tempnam(sys_get_temp_dir(), 'sig_') . '.png';
        file_put_contents($tmpPath, $decoded);
        return new UploadedFile($tmpPath, 'signature.png', 'image/png', null, true);
    }

    public function render()
    {
        return view('livewire.patient-form');
    }
}
