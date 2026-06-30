<?php

namespace App\Livewire;

use App\Models\FamilyFile;
use Livewire\Component;
use Livewire\WithPagination;

class FamilyFileIndex extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterType = '';
    public bool $showCreateForm = false;

    public string $new_name = '';
    public string $new_email = '';
    public string $new_phone = '';
    public string $new_address = '';
    public string $new_type = 'family';

    public ?int $editingFileId = null;
    public string $edit_name = '';
    public string $edit_email = '';
    public string $edit_phone = '';
    public string $edit_address = '';
    public string $edit_type = 'family';

    protected function rules(): array
    {
        return [
            'new_name' => 'required|string|max:255',
            'new_email' => 'required|email|max:255',
            'new_phone' => $this->phoneRule(),
            'new_address' => 'nullable|string|max:1000',
            'new_type' => 'required|in:family,corporate',
        ];
    }

    protected function editRules(): array
    {
        return [
            'edit_name' => 'required|string|max:255',
            'edit_email' => 'required|email|max:255',
            'edit_phone' => $this->phoneRule(),
            'edit_address' => 'nullable|string|max:1000',
            'edit_type' => 'required|in:family,corporate',
        ];
    }

    private function phoneRule(): array
    {
        return [
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
        ];
    }

    public function createFile(): void
    {
        $this->validate();

        FamilyFile::create([
            'name' => $this->new_name,
            'email' => $this->new_email,
            'phone' => $this->new_phone,
            'address' => $this->new_address ?: null,
            'type' => $this->new_type,
        ]);

        $this->reset(['new_name', 'new_email', 'new_phone', 'new_address', 'showCreateForm']);
        session()->flash('status', 'File created successfully.');
    }

    public function editFile(int $id): void
    {
        $file = FamilyFile::findOrFail($id);
        $this->editingFileId = $file->id;
        $this->edit_name = $file->name;
        $this->edit_email = $file->email;
        $this->edit_phone = $file->phone;
        $this->edit_address = $file->address ?? '';
        $this->edit_type = $file->type;
    }

    public function cancelEdit(): void
    {
        $this->reset(['editingFileId', 'edit_name', 'edit_email', 'edit_phone', 'edit_address', 'edit_type']);
    }

    public function updateFile(): void
    {
        $this->validate($this->editRules());

        $file = FamilyFile::findOrFail($this->editingFileId);
        $file->update([
            'name' => $this->edit_name,
            'email' => $this->edit_email,
            'phone' => $this->edit_phone,
            'address' => $this->edit_address ?: null,
            'type' => $this->edit_type,
        ]);

        $this->cancelEdit();
        session()->flash('status', 'File updated successfully.');
    }

    public function render()
    {
        $files = FamilyFile::withCount('patients')
            ->with('patients:id,name,file_number,family_file_id')
            ->when($this->search, fn($q) => $q->where(function ($q) {
                $q->where('name', 'like', "%{$this->search}%")
                  ->orWhere('file_number', 'like', "%{$this->search}%")
                  ->orWhere('email', 'like', "%{$this->search}%")
                  ->orWhere('phone', 'like', "%{$this->search}%");
            }))
            ->when($this->filterType, fn($q) => $q->where('type', $this->filterType))
            ->latest()
            ->paginate(15);

        return view('livewire.family-file-index', compact('files'));
    }
}
