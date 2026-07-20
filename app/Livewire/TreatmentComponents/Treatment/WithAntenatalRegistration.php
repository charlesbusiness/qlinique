<?php

namespace App\Livewire\TreatmentComponents\Treatment;

use App\Livewire\TreatmentComponents\Concerns\HasBase64Signature;
use App\Models\Patient;
use App\Models\PatientFile;
use Illuminate\Support\Facades\Auth;

trait WithAntenatalRegistration
{
    use HasBase64Signature;
    // Antenatal Registration: Bio Data
    public string $reg_name = '';

    public string $reg_phone = '';

    public string $reg_email = '';

    public string $reg_gender = '';

    public string $reg_blood_group = '';

    public string $reg_genotype = '';

    public string $reg_occupation = '';

    public string $reg_marital_status = '';

    public string $reg_religion = '';

    public string $reg_address = '';

    public string $reg_date = '';

    public $reg_photo = null;

    public string $reg_signature = '';

    public string $reg_signature_name = '';

    public string $reg_signature_date = '';

    public string $reg_signature_type = 'typed';

    public $reg_signature_upload = null;

    // Antenatal Registration: Next of Kin
    public string $nok_name = '';

    public string $nok_relationship = '';

    public string $nok_phone = '';

    public string $nok_address = '';

    // Antenatal Registration: Consent
    public bool $nok_consent = false;

    public bool $nok_privacy_consent = false;

    public string $nok_signature = '';

    public string $nok_signature_type = 'typed';

    public $nok_signature_upload = null;

    public string $nok_date = '';

    // Existing file option
    public $useExistingFile = '0';

    public $existing_file_id = '';

    public array $existingFileResults = [];

    // Registration success state
    public bool $registrationSuccess = false;

    public string $registeredPatientName = '';

    public function resetRegistrationFields(): void
    {
        $this->reg_name = '';
        $this->reg_phone = '';
        $this->reg_email = '';
        $this->reg_gender = '';
        $this->reg_blood_group = '';
        $this->reg_genotype = '';
        $this->reg_occupation = '';
        $this->reg_marital_status = '';
        $this->reg_religion = '';
        $this->reg_address = '';
        $this->reg_date = '';
        $this->reg_photo = null;
        $this->reg_signature = '';
        $this->reg_signature_name = '';
        $this->reg_signature_date = '';
        $this->reg_signature_type = 'typed';
        $this->reg_signature_upload = null;
        $this->nok_name = '';
        $this->nok_relationship = '';
        $this->nok_phone = '';
        $this->nok_address = '';
        $this->nok_consent = false;
        $this->nok_privacy_consent = false;
        $this->nok_signature = '';
        $this->nok_signature_type = 'typed';
        $this->nok_signature_upload = null;
        $this->nok_date = '';
        $this->useExistingFile = '0';
        $this->existing_file_id = '';
        $this->existingFileResults = [];
    }

    public function updatedUseExistingFile(): void
    {
        if ($this->useExistingFile === '0') {
            $this->existing_file_id = '';
            $this->existingFileResults = [];
        }
    }

    public function searchPatientFiles(string $search): void
    {
        $this->existingFileResults = PatientFile::query()
            ->where('file_number', 'like', "%{$search}%")
            ->orWhere('name', 'like', "%{$search}%")
            ->limit(10)
            ->get()
            ->map(fn ($file) => [
                'id' => $file->id,
                'label' => "{$file->file_number} — {$file->name} ({$file->type})",
            ])
            ->toArray();
    }

    public function selectExistingFile($fileId): void
    {
        $file = PatientFile::find($fileId);

        if ($file) {
            $this->existing_file_id = $file->id;
            $this->existingFileResults = [
                ['id' => $file->id, 'label' => "{$file->file_number} — {$file->name} ({$file->type})"],
            ];
        }
    }

    public function submitAntenatalRegistration(): void
    {
        $rules = [
            'reg_name' => 'required|string|max:255',
            'reg_phone' => 'required|string|max:20',
            'reg_gender' => 'required|string|in:Female,Male',
            'reg_blood_group' => 'nullable|string',
            'reg_genotype' => 'nullable|string',
            'reg_occupation' => 'nullable|string|max:255',
            'reg_marital_status' => 'nullable|string',
            'reg_religion' => 'nullable|string',
            'reg_address' => 'nullable|string|max:1000',
            'reg_date' => 'required|date',
            'reg_photo' => 'nullable|image|max:2048',
            'reg_signature_type' => 'nullable|in:typed,drawn,uploaded',
            'reg_signature' => 'nullable|string',
            'reg_signature_upload' => 'nullable|image|max:2048',
            'nok_name' => 'nullable|string|max:255',
            'nok_relationship' => 'nullable|string|max:255',
            'nok_phone' => 'nullable|string|max:20',
            'nok_address' => 'nullable|string|max:1000',
            'nok_consent' => 'accepted',
            'nok_privacy_consent' => 'accepted',
        ];

        if ($this->useExistingFile === '1') {
            $rules['existing_file_id'] = 'required|exists:patient_files,id';
        }

        $validated = $this->validate($rules);

        if ($this->useExistingFile === '1') {
            $file = PatientFile::findOrFail($this->existing_file_id);
            $fileId = $file->id;
        } else {
            $file = PatientFile::create([
                'name' => $validated['reg_name'],
                'email' => $validated['reg_email'] ?? null,
                'phone' => $validated['reg_phone'],
                'address' => $validated['reg_address'] ?? null,
                'type' => 'individual',
            ]);
            $fileId = $file->id;
        }

        $patient = Patient::create([
            'file_id' => $fileId,
            'name' => $validated['reg_name'],
            'phone' => $validated['reg_phone'],
            'email' => $validated['reg_email'] ?? null,
            'gender' => $validated['reg_gender'],
            'blood_group' => $validated['reg_blood_group'] ?? null,
            'genotype' => $validated['reg_genotype'] ?? null,
            'occupation' => $validated['reg_occupation'] ?? null,
            'marital_status' => $validated['reg_marital_status'] ?? null,
            'religion' => $validated['reg_religion'] ?? null,
            'address' => $validated['reg_address'] ?? null,
            'date_of_birth' => $validated['reg_date'],
            'patient_type' => 'antenatal',
            'next_of_kin' => [
                'name' => $this->nok_name,
                'relationship' => $this->nok_relationship,
                'phone' => $this->nok_phone,
                'address' => $this->nok_address,
            ],
            'consent' => [
                'treatment' => $this->nok_consent,
                'privacy' => $this->nok_privacy_consent,
            ],
            'is_active' => true,
            'created_by' => Auth::id(),
        ]);

        if ($this->reg_photo) {
            $photoPath = $this->reg_photo->store('patients', 'public');
            $patient->update(['photo_path' => $photoPath]);
        }

        $patientSigPath = null;
        if ($this->reg_signature_type === 'drawn' && $this->reg_signature) {
            $patientSigPath = $this->base64ToUploadedFile($this->reg_signature, 'patient_sig_' . $patient->id . '.png')->store('signatures', 'public');
        } elseif ($this->reg_signature_type === 'uploaded' && $this->reg_signature_upload) {
            $patientSigPath = $this->reg_signature_upload->store('signatures', 'public');
        }
        $patient->update([
            'signature' => $patientSigPath ?: $this->reg_signature,
            'signature_type' => $this->reg_signature_type,
        ]);

        $nokSigPath = null;
        if ($this->nok_signature_type === 'drawn' && $this->nok_signature) {
            $nokSigPath = $this->base64ToUploadedFile($this->nok_signature)->store('signatures', 'public');
        } elseif ($this->nok_signature_type === 'uploaded' && $this->nok_signature_upload) {
            $nokSigPath = $this->nok_signature_upload->store('signatures', 'public');
        }

        $nextOfKin = $patient->next_of_kin ?? [];
        $nextOfKin['signature'] = $nokSigPath ?: $this->nok_signature;
        $nextOfKin['signature_type'] = $this->nok_signature_type;
        $patient->update(['next_of_kin' => $nextOfKin]);

        $this->registeredPatientName = $patient->name;
        $this->registrationSuccess = true;
        $this->resetRegistrationFields();
    }
}
