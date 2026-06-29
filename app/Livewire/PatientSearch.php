<?php

namespace App\Livewire;

use App\Models\Patient;
use Livewire\Component;

class PatientSearch extends Component
{
    public string $query = '';
    public bool $showDropdown = false;

    public function updatedQuery(): void
    {
        $this->showDropdown = strlen($this->query) > 1;
    }

    public function selectPatient(int $id): void
    {
        $this->dispatch('patientSelected', id: $id);
        $this->query = '';
        $this->showDropdown = false;
    }

    public function render()
    {
        $results = [];

        if (strlen($this->query) > 1) {
            $results = Patient::where('name', 'like', "%{$this->query}%")
                ->orWhere('file_number', 'like', "%{$this->query}%")
                ->take(10)
                ->get();
        }

        return view('livewire.patient-search', compact('results'));
    }
}
