<?php

namespace App\Livewire;

use App\Enums\AccountType;
use App\Models\FamilyFile;
use App\Models\Patient;
use App\Services\PatientService;
use Illuminate\Http\UploadedFile;
use Livewire\Component;
use Livewire\WithFileUploads;

class PatientForm extends Component
{
    use WithFileUploads;

    public ?Patient $patient = null;
    public ?int $familyFileId = null;
    public string $name = '';
    public string $gender = '';
    public string $age = '';
    public string $phone = '';
    public string $email = '';
    public string $address = '';
    public string $occupation = '';
    public string $marital_status = '';
    public $photo = null;
    public string $account_type = 'individual';
    public ?string $patient_type = null;
    public ?string $selected_family_id = null;
    public array $next_of_kin = [];
    public array $consent = [];
    public string $religion = '';
    public string $denomination = '';

    public $existingPhoto = null;

    public ?string $signature_type = null;
    public string $signature = '';
    public $signature_upload = null;

    public ?string $existingSignature = null;
    public ?string $existingSignatureType = null;

    public int $step = 1;
    public bool $show_create_family = false;
    public string $new_family_name = '';
    public string $new_family_email = '';
    public string $new_family_phone = '';
    public string $new_family_address = '';

    protected function rules(): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'gender' => 'required|in:male,female',
            'age' => 'required|integer|min:0|max:150',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:1000',
            'occupation' => 'nullable|string|max:255',
            'marital_status' => 'nullable|string|max:50',
            'photo' => 'nullable|image|max:2048',
            'account_type' => 'required|in:individual,family,corporate',
            'patient_type' => 'nullable|in:admission,outpatient,outreach',
            'religion' => 'nullable|string|max:255',
            'denomination' => 'nullable|string|max:255',
            'signature_type' => 'nullable|in:typed,drawn,uploaded',
            'signature' => 'nullable|string',
            'signature_upload' => 'nullable|image|max:2048',
        ];

        return $rules;
    }

    public function mount(?Patient $patient = null): void
    {
        if ($this->familyFileId) {
            $familyFile = FamilyFile::find($this->familyFileId);
            if ($familyFile) {
                $this->account_type = $familyFile->type;
                $this->selected_family_id = (string) $familyFile->id;
                $this->step = 2;
            }
        }

        if ($patient?->id) {
            $this->patient = $patient;
            $this->name = $patient->name ?? '';
            $this->gender = $patient->gender ?? '';
            $this->age = $patient->date_of_birth ? (string) $patient->date_of_birth->age : '';
            $this->phone = $patient->phone ?? '';
            $this->email = $patient->email ?? '';
            $this->address = $patient->address ?? '';
            $this->occupation = $patient->occupation ?? '';
            $this->marital_status = $patient->marital_status ?? '';
            $this->account_type = $patient->account_type ?? 'individual';
            $this->patient_type = $patient->patient_type ?? null;
            $this->selected_family_id = $patient->family_file_id ? (string) $patient->family_file_id : null;
            $this->next_of_kin = is_array($patient->next_of_kin) ? $patient->next_of_kin : [];
            $this->consent = is_array($patient->consent) ? $patient->consent : [];
            $this->religion = $patient->religion ?? '';
            $this->denomination = $patient->denomination ?? '';
            $this->existingPhoto = $patient->photo_path;
            $this->signature_type = $patient->signature_type ?? null;
            $this->signature = $patient->signature ?? '';
            $this->existingSignature = $patient->signature;
            $this->existingSignatureType = $patient->signature_type;
        }
    }

    public function updatedAccountType($value): void
    {
        if (!in_array($value, ['family', 'corporate'])) {
            $this->selected_family_id = null;
            $this->show_create_family = false;
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

    public function toggleCreateFamily(): void
    {
        $this->show_create_family = !$this->show_create_family;
        if (!$this->show_create_family) {
            $this->new_family_name = '';
            $this->new_family_email = '';
            $this->new_family_phone = '';
            $this->new_family_address = '';
        }
    }

    public function createFamilyFile(): void
    {
        $this->validate([
            'new_family_name' => 'required|string|max:255',
            'new_family_email' => 'required|email|max:255',
            'new_family_phone' => [
                'required',
                'string',
                'max:36',
                function ($attribute, $value, $fail) {
                    foreach (explode(',', $value) as $num) {
                        $num = trim($num);
                        if (!preg_match('/^\d{1,11}$/', $num)) {
                            $fail('Each phone number must be up to 11 digits, separated by commas.');
                            return;
                        }
                    }
                },
            ],
            'new_family_address' => 'nullable|string|max:1000',
        ]);

        $family = FamilyFile::create([
            'name' => $this->new_family_name,
            'email' => $this->new_family_email,
            'phone' => $this->new_family_phone,
            'address' => $this->new_family_address ?: null,
            'type' => $this->account_type,
        ]);

        $this->selected_family_id = (string) $family->id;
        $this->show_create_family = false;
        $this->new_family_name = '';
        $this->new_family_email = '';
        $this->new_family_phone = '';
        $this->new_family_address = '';
    }

    public function save(PatientService $patientService): void
    {
        $this->validate();

        $data = [
            'account_type' => $this->account_type,
            'name' => $this->name,
            'gender' => $this->gender,
            'date_of_birth' => $this->age ? now()->subYears((int) $this->age)->startOfYear() : null,
            'phone' => $this->phone ?: null,
            'email' => $this->email ?: null,
            'address' => $this->address ?: null,
            'occupation' => $this->occupation ?: null,
            'marital_status' => $this->marital_status ?: null,
            'patient_type' => $this->patient_type ?: null,
            'next_of_kin' => $this->next_of_kin,
            'consent' => $this->consent,
            'religion' => $this->religion ?: null,
            'denomination' => $this->denomination ?: null,
            'signature_type' => $this->signature_type ?: null,
            'signature' => null,
        ];

        if (in_array($this->account_type, ['family', 'corporate']) && $this->selected_family_id) {
            $data['family_file_id'] = $this->selected_family_id;
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

    private function base64ToUploadedFile(string $base64): UploadedFile
    {
        $decoded = base64_decode(substr($base64, strpos($base64, ',') + 1));
        $tmpPath = tempnam(sys_get_temp_dir(), 'sig_') . '.png';
        file_put_contents($tmpPath, $decoded);
        return new UploadedFile($tmpPath, 'signature.png', 'image/png', null, true);
    }

    public function render()
    {
        $familyFiles = in_array($this->account_type, ['family', 'corporate'])
            ? FamilyFile::where('type', $this->account_type)
                ->orderBy('name')
                ->get(['id', 'name', 'file_number'])
            : collect();

        return view('livewire.patient-form', compact('familyFiles'));
    }
}
