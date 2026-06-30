<?php

namespace App\Livewire;

use App\Models\Document;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

class DocumentUpload extends Component
{
    use WithFileUploads;

    public string $documentableType;
    public int $documentableId;
    public $file = null;
    public string $description = '';
    public bool $showUploadForm = false;

    protected function rules(): array
    {
        return [
            'file' => 'required|file|max:10240',
            'description' => 'nullable|string|max:1000',
        ];
    }

    public function mount(Model $documentable): void
    {
        $this->documentableType = get_class($documentable);
        $this->documentableId = $documentable->id;
    }

    public function toggleForm(): void
    {
        $this->showUploadForm = !$this->showUploadForm;
        if (!$this->showUploadForm) {
            $this->reset(['file', 'description']);
        }
    }

    public function upload(): void
    {
        $this->validate();

        $path = $this->file->store('documents', 'public');

        Document::create([
            'name' => $this->file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $this->file->getMimeType(),
            'file_size' => $this->file->getSize(),
            'description' => $this->description ?: null,
            'uploaded_by' => auth()->id(),
            'documentable_type' => $this->documentableType,
            'documentable_id' => $this->documentableId,
        ]);

        $this->reset(['file', 'description', 'showUploadForm']);
    }

    public function delete(int $documentId): void
    {
        $doc = Document::where('documentable_type', $this->documentableType)
            ->where('documentable_id', $this->documentableId)
            ->findOrFail($documentId);

        Storage::disk('public')->delete($doc->file_path);
        $doc->delete();
    }

    public function render()
    {
        $documents = Document::where('documentable_type', $this->documentableType)
            ->where('documentable_id', $this->documentableId)
            ->latest()
            ->get();

        return view('livewire.document-upload', compact('documents'));
    }
}
