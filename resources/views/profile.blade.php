@extends('layouts.app')

@section('conteudo')
<style>
    .big-exclamation {
        font-size: 20px;
        font-weight: bold;
        vertical-align: middle;
        margin-left: 5px;
    }
</style>
<div class="container py-5">
    <h2 class="text-center mb-5">My Profile</h2>

    {{-- Alerts --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row justify-content-center g-4">
        {{-- Account Information --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4">Account Information</h5>

                    <div class="mb-3">
                        <label class="text-muted small">Name</label>
                        <p class="mb-0">{{ $user->name }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Email</label>
                        <p class="mb-0">{{ $user->email }}</p>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small">Member Since</label>
                        <p class="mb-0">{{ $user->created_at->format('M d, Y') }}</p>
                    </div>

                    @if($user->dispatcher)
                        <hr class="my-4">

                        <div class="mb-3">
                            <label class="text-muted small">Company</label>
                            <p class="mb-0">{{ $user->dispatcher->company_name ?? 'Not specified' }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small">Phone</label>
                            <p class="mb-0">{{ $user->dispatcher->phone ?? 'Not specified' }}</p>
                        </div>

                        <div class="mb-3">
                            <label class="text-muted small">Address</label>
                            <p class="mb-0">{{ $user->dispatcher->address ?? 'Not specified' }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Change Password --}}
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    @if(session('warning'))
                        <h5 class="card-title mb-4">Change Password <span class="text-danger big-exclamation">!</span></h5>
                    @else
                        <h5 class="card-title mb-4">Change Password </h5>
                    @endif
                    <form id="passwordForm" method="POST" action="{{ route('profile.password.update') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Current Password</label>
                            <div class="input-group">
                                <input id="current_password"
                                       name="current_password"
                                       type="password"
                                       class="form-control @error('current_password') is-invalid @enderror"
                                       required>
                                <button class="btn btn-outline-secondary toggle-password"
                                        type="button"
                                        data-target="current_password">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                            @error('current_password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">New Password</label>
                            <div class="input-group">
                                <input id="password"
                                       name="password"
                                       type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       required>
                                <button class="btn btn-outline-secondary toggle-password"
                                        type="button"
                                        data-target="password">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                            <div id="passwordHelp" class="form-text text-danger d-none">
                                Password must have at least 6 characters, one uppercase letter, and one special character.
                            </div>
                            @error('password')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label">Confirm New Password</label>
                            <div class="input-group">
                                <input id="password_confirmation"
                                       name="password_confirmation"
                                       type="password"
                                       class="form-control"
                                       required>
                                <button class="btn btn-outline-secondary toggle-password"
                                        type="button"
                                        data-target="password_confirmation">
                                    <i class="far fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            Update Password
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Password visibility toggle
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const targetInput = document.getElementById(targetId);
            const icon = this.querySelector('i');

            if (targetInput.type === 'password') {
                targetInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                targetInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
    });

    // Password validation
    const passwordRegex = /^(?=.*[A-Z])(?=.*[!@#$%^&*()_\-+=\[\]{};':"\\|,.<>\/?]).{6,}$/;

    document.getElementById('passwordForm').addEventListener('submit', function(e) {
        const password = document.getElementById('password').value;
        const passwordHelp = document.getElementById('passwordHelp');

        if (!passwordRegex.test(password)) {
            e.preventDefault();
            passwordHelp.classList.remove('d-none');
            document.getElementById('password').focus();
        }
    });
});
</script>

@endsection
