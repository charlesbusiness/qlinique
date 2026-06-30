<div>
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('status') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-2">
            <input type="text" class="form-control" wire:model.live.debounce.300ms="search" placeholder="Search files..." style="max-width: 280px;">
            <select class="form-select" wire:model.live="filterType" style="max-width: 160px;">
                <option value="">All Types</option>
                <option value="family">Family</option>
                <option value="corporate">Corporate</option>
            </select>
        </div>
        <button type="button" class="btn btn-primary" wire:click="$toggle('showCreateForm')">
            {{ $showCreateForm ? 'Cancel' : '+ New File' }}
        </button>
    </div>

    @if ($showCreateForm)
        <div class="card mb-3 border-primary">
            <div class="card-body">
                <h5 class="card-title">Create New {{ ucfirst($new_type) }} File</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('new_name') is-invalid @enderror" wire:model="new_name">
                        @error('new_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Email <span class="text-danger">*</span></label>
                        <input type="email" class="form-control @error('new_email') is-invalid @enderror" wire:model="new_email">
                        @error('new_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-12 mb-3">
                        <label class="form-label">Phone <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('new_phone') is-invalid @enderror" wire:model="new_phone" placeholder="e.g. 08012345678,08087654321">
                        @error('new_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Address</label>
                        <textarea class="form-control" wire:model="new_address" rows="2"></textarea>
                    </div>
                    <div class="col-md-3 mb-3">
                        <label class="form-label">Type <span class="text-danger">*</span></label>
                        <select class="form-select @error('new_type') is-invalid @enderror" wire:model="new_type">
                            <option value="family">Family</option>
                            <option value="corporate">Corporate</option>
                        </select>
                        @error('new_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-3 mb-3 d-flex align-items-end">
                        <button type="button" class="btn btn-success w-100" wire:click="createFile">Create File</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>File #</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Type</th>
                        <th>Members</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($files as $file)
                    <tr>
                        <td><code>{{ $file->file_number }}</code></td>
                        <td>{{ $file->name }}</td>
                        <td>{{ $file->email }}</td>
                        <td>{{ $file->phone }}</td>
                        <td><span class="badge bg-{{ $file->type === 'family' ? 'info' : 'secondary' }}">{{ ucfirst($file->type) }}</span></td>
                        <td>
                            <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#membersModal-{{ $file->id }}">
                                {{ $file->patients_count }} member{{ $file->patients_count !== 1 ? 's' : '' }}
                            </a>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-outline-primary" wire:click="editFile({{ $file->id }})" title="Edit">Edit</button>
                                <a href="{{ route('patients.create', ['family_file_id' => $file->id]) }}" class="btn btn-sm btn-outline-success" title="Add Patient">+ Patient</a>
                            </div>
                        </td>
                    </tr>
                    @if ($editingFileId === $file->id)
                    <tr class="table-warning">
                        <td colspan="7" class="p-3">
                            <div class="row">
                                <div class="col-md-4 mb-2">
                                    <label class="form-label small">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm @error('edit_name') is-invalid @enderror" wire:model="edit_name">
                                    @error('edit_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label small">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control form-control-sm @error('edit_email') is-invalid @enderror" wire:model="edit_email">
                                    @error('edit_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label class="form-label small">Type <span class="text-danger">*</span></label>
                                    <select class="form-select form-select-sm @error('edit_type') is-invalid @enderror" wire:model="edit_type">
                                        <option value="family">Family</option>
                                        <option value="corporate">Corporate</option>
                                    </select>
                                    @error('edit_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-12 mb-2">
                                    <label class="form-label small">Phone <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control form-control-sm @error('edit_phone') is-invalid @enderror" wire:model="edit_phone">
                                    @error('edit_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                                <div class="col-md-6 mb-2">
                                    <label class="form-label small">Address</label>
                                    <textarea class="form-control form-control-sm" wire:model="edit_address" rows="1"></textarea>
                                </div>
                                <div class="col-md-6 mb-2 d-flex align-items-end gap-1">
                                    <button type="button" class="btn btn-success btn-sm" wire:click="updateFile">Save</button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" wire:click="cancelEdit">Cancel</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endif
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">No family files found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if ($files->hasPages())
        <div class="card-footer">
            {{ $files->links() }}
        </div>
        @endif
    </div>

    @foreach ($files as $file)
    <div class="modal fade" id="membersModal-{{ $file->id }}" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ $file->name }} — Members</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    @if ($file->patients->isEmpty())
                        <p class="text-muted mb-0">No patients linked to this file.</p>
                    @else
                        <ul class="list-group">
                            @foreach ($file->patients as $patient)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <a href="{{ route('patients.show', $patient) }}">{{ $patient->name }}</a>
                                <code>{{ $patient->file_number }}</code>
                            </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                <div class="modal-footer">
                    <a href="{{ route('patients.create', ['family_file_id' => $file->id]) }}" class="btn btn-sm btn-success">+ Add Patient</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
