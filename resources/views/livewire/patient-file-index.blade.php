<div>
    @if (session('status'))
        <div class="alert alert-success alert-dismissible fade show">{{ session('status') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="d-flex gap-2">
            <input type="text" class="form-control" wire:model.live.debounce.300ms="search" placeholder="Search files..." style="max-width: 280px;">
            <select class="form-select" wire:model.live="filterType" style="max-width: 160px;">
                <option value="">All Types</option>
                <option value="individual">Individual</option>
                <option value="family">Family</option>
                <option value="corporate">Corporate</option>
            </select>
        </div>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createFileModal">
            + New File
        </button>
    </div>

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
                        <td>{{ ucfirst($file->type) }}</td>
                        <td>
                            <a href="{{ route('patient-files.members', $file) }}" class="text-decoration-none">
                                {{ $file->patients_count }} member{{ $file->patients_count !== 1 ? 's' : '' }}
                            </a>
                        </td>
                        <td>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-sm btn-outline-primary" wire:click="editFile({{ $file->id }})" title="Edit">Edit</button>
                                <a href="{{ route('patients.create', ['file_id' => $file->id]) }}" class="btn btn-sm btn-outline-success" title="Add Patient">+ Patient</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">No files found.</td></tr>
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

    <div wire:ignore.self class="modal fade" id="createFileModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Create New {{ ucfirst($new_type) }} File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
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
                                <option value="individual">Individual</option>
                                <option value="family">Family</option>
                                <option value="corporate">Corporate</option>
                            </select>
                            @error('new_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" wire:click="createFile">Create File</button>
                </div>
            </div>
        </div>
    </div>

    <div wire:ignore.self class="modal fade" id="editFileModal" tabindex="-1" data-bs-backdrop="static">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit File</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('edit_name') is-invalid @enderror" wire:model="edit_name">
                            @error('edit_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('edit_email') is-invalid @enderror" wire:model="edit_email">
                            @error('edit_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label">Phone <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('edit_phone') is-invalid @enderror" wire:model="edit_phone">
                            @error('edit_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" wire:model="edit_address" rows="2"></textarea>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Type <span class="text-danger">*</span></label>
                            <select class="form-select @error('edit_type') is-invalid @enderror" wire:model="edit_type">
                                <option value="individual">Individual</option>
                                <option value="family">Family</option>
                                <option value="corporate">Corporate</option>
                            </select>
                            @error('edit_type') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-success" wire:click="updateFile">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

</div>
