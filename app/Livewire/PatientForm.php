<?php

namespace App\Livewire;

use App\Livewire\TreatmentComponents\Concerns\HasBase64Signature;
use App\Models\Patient;
use App\Models\PatientFile;
use App\Services\PatientService;
use Livewire\Component;
use Livewire\WithFileUploads;

class PatientForm extends Component
{
    use HasBase64Signature;
    use WithFileUploads;

    public ?Patient $patient = null;

    public ?int $fileId = null;

    public string $name = '';

    public string $gender = '';

    public string $date_of_birth = '';

    public string $phone = '';

    public string $email = '';

    public string $address = '';

    public string $occupation = '';

    public string $marital_status = '';

    public string $blood_group = '';

    public string $genotype = '';

    public $photo = null;

    public string $account_type = 'individual';

    public ?string $patient_type = null;

    public ?string $selected_file_id = null;

    public array $next_of_kin = [];

    public array $consent = [];

    public string $religion = '';

    public $existingPhoto = null;

    public ?string $signature_type = null;

    public string $signature = '';

    public $signature_upload = null;

    public ?string $existingSignature = null;

    public ?string $existingSignatureType = null;

    public int $step = 1;

    public bool $show_create_file = false;

    public string $new_file_name = '';

    public string $new_file_email = '';

    public string $new_file_phone = '';

    public string $new_file_address = '';

    protected function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'nullable|date',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:1000',
            'occupation' => 'nullable|string|max:255',
            'marital_status' => 'nullable|string|max:50',
            'blood_group' => 'nullable|in:A+,A-,B+,B-,AB+,AB-,O+,O-',
            'genotype' => 'nullable|in:AA,AS,SS,AC',
            'photo' => 'nullable|image|max:2048',
            'account_type' => 'required|in:individual,family,corporate',
            'patient_type' => 'nullable|in:admission,outpatient,outreach',
            'religion' => 'nullable|in:Christianity,Islam,Others',
            'signature_type' => 'nullable|in:typed,drawn,uploaded',
            'signature' => 'nullable|string',
            'signature_upload' => 'nullable|image|max:2048',
            'consent.treatment' => 'accepted',
            'consent.privacy' => 'accepted',
        ];

        return $rules;
    }

    public function mount(?Patient $patient = null): void
    {
        if ($this->fileId) {
            $patientFile = PatientFile::find($this->fileId);
            if ($patientFile) {
                $this->account_type = $patientFile->type;
                $this->selected_file_id = (string) $patientFile->id;
                $this->step = 2;
            }
        }

        if ($patient?->id) {
            $this->patient = $patient;
            $this->name = $patient->name ?? '';
            $this->gender = $patient->gender ?? '';
            $this->date_of_birth = $patient->date_of_birth ? $patient->date_of_birth->format('Y-m-d') : '';
            $this->phone = $patient->phone ?? '';
            $this->email = $patient->email ?? '';
            $this->address = $patient->address ?? '';
            $this->occupation = $patient->occupation ?? '';
            $this->marital_status = $patient->marital_status ?? '';
            $this->blood_group = $patient->blood_group ?? '';
            $this->genotype = $patient->genotype ?? '';
            $this->account_type = $patient->file?->type ?? 'individual';
            $this->patient_type = $patient->patient_type ?? null;
            $this->selected_file_id = $patient->file_id ? (string) $patient->file_id : null;
            $this->next_of_kin = is_array($patient->next_of_kin) ? $patient->next_of_kin : [];
            $this->consent = is_array($patient->consent) ? $patient->consent : [];
            $this->religion = $patient->religion ?? '';
            $this->existingPhoto = $patient->photo_path;
            $this->signature_type = $patient->signature_type ?? null;
            $this->signature = $patient->signature ?? '';
            $this->existingSignature = $patient->signature;
            $this->existingSignatureType = $patient->signature_type;
        }
    }

    public function updatedAccountType($value): void
    {
        if (! in_array($value, ['family', 'corporate'])) {
            $this->selected_file_id = null;
            $this->show_create_file = false;
        }
    }

    public function nextStep(): void
    {
        $this->step++;
    }

    public function prevStep(): void
    {
        $this->step--;
    }

    public function toggleCreateFile(): void
    {
        $this->show_create_file = ! $this->show_create_file;
        if (! $this->show_create_file) {
            $this->new_file_name = '';
            $this->new_file_email = '';
            $this->new_file_phone = '';
            $this->new_file_address = '';
        }
    }

    public function createPatientFile(): void
    {
        $this->validate([
            'new_file_name' => 'required|string|max:255',
            'new_file_email' => 'required|email|max:255',
            'new_file_phone' => [
                'required',
                'string',
                'max:36',
                function ($attribute, $value, $fail) {
                    foreach (explode(',', $value) as $num) {
                        $num = trim($num);
                        if (! preg_match('/^\d{1,11}$/', $num)) {
                            $fail('Each phone number must be up to 11 digits, separated by commas.');

                            return;
                        }
                    }
                },
            ],
            'new_file_address' => 'nullable|string|max:1000',
        ]);

        $file = PatientFile::create([
            'name' => $this->new_file_name,
            'email' => $this->new_file_email,
            'phone' => $this->new_file_phone,
            'address' => $this->new_file_address ?: null,
            'type' => $this->account_type,
        ]);

        $this->selected_file_id = (string) $file->id;
        $this->show_create_file = false;
        $this->new_file_name = '';
        $this->new_file_email = '';
        $this->new_file_phone = '';
        $this->new_file_address = '';
    }

    public function save(PatientService $patientService): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth ?: null,
            'phone' => $this->phone ?: null,
            'email' => $this->email ?: null,
            'address' => $this->address ?: null,
            'occupation' => $this->occupation ?: null,
            'marital_status' => $this->marital_status ?: null,
            'blood_group' => $this->blood_group ?: null,
            'genotype' => $this->genotype ?: null,
            'patient_type' => $this->patient_type ?: null,
            'next_of_kin' => $this->next_of_kin,
            'consent' => $this->consent,
            'religion' => $this->religion ?: null,
            'signature_type' => $this->signature_type ?: null,
            'signature' => null,
        ];

        $data['file_type'] = $this->account_type;

        if (in_array($this->account_type, ['family', 'corporate']) && $this->selected_file_id) {
            $data['file_id'] = $this->selected_file_id;
        }

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

    public function render()
    {
        $patientFiles = in_array($this->account_type, ['family', 'corporate'])
            ? PatientFile::where('type', $this->account_type)
                ->orderBy('name')
                ->get(['id', 'name', 'file_number'])
            : collect();

        return view('livewire.patient-form', compact('patientFiles'));
    }
}
