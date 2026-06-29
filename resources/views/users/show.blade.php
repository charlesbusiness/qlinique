<x-app-layout>
    <x-slot name="header">
        <div class="d-flex justify-content-between align-items-center">
            <h2 class="fw-semibold fs-5 text-dark">User Details</h2>
            <div class="d-flex gap-2">
                <a href="{{ route('users.edit', $user) }}" class="btn btn-primary btn-sm">Edit</a>
                <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">Back</a>
            </div>
        </div>
    </x-slot>

    <div class="py-4">
        <div class="container">
            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="fw-semibold text-muted small text-uppercase">Name</label>
                            <p class="mb-0">{{ $user->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold text-muted small text-uppercase">Email</label>
                            <p class="mb-0">{{ $user->email }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold text-muted small text-uppercase">Staff ID</label>
                            <p class="mb-0">{{ $user->staff_id ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold text-muted small text-uppercase">Phone</label>
                            <p class="mb-0">{{ $user->phone ?? '—' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold text-muted small text-uppercase">Role</label>
                            <p class="mb-0"><span class="badge bg-primary">{{ ucfirst($user->role) }}</span></p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold text-muted small text-uppercase">Status</label>
                            <p class="mb-0">
                                @if ($user->is_active)
                                    <span class="badge bg-success">Active</span>
                                @else
                                    <span class="badge bg-secondary">Inactive</span>
                                @endif
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold text-muted small text-uppercase">Email Verified</label>
                            <p class="mb-0">{{ $user->email_verified_at ? $user->email_verified_at->format('M d, Y H:i') : 'Not verified' }}</p>
                        </div>
                        <div class="col-md-6">
                            <label class="fw-semibold text-muted small text-uppercase">Member Since</label>
                            <p class="mb-0">{{ $user->created_at->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
