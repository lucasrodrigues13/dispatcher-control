@extends('layouts.app4')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="auth-card p-5">
                <h2 class="mb-4 text-center">Verify Your Email Address</h2>
                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            A fresh verification link has been sent to your email address.
                        </div>
                    @endif

                    <p class="text-center mb-4">
                        We have sent a verification link to your email.
                        Please check your inbox or Spam folder before continuing.
                    </p>

                    <p class="text-center mb-3">
                        Didn’t receive the email?
                    </p>

                    <form method="POST" action="{{ route('verification.send') }}" class="text-center">
                        @csrf
                        <button type="submit" class="btn btn-primary px-4">
                            Request a New Verification Email
                        </button>
                    </form>
                </div>

                {{-- Caixa separada para logout --}}
                <div class="mt-5 p-4 border rounded bg-light text-center">
                    <p class="mb-3">Want to leave?</p>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary px-4">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
