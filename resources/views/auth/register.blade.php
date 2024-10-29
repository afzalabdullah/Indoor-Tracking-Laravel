@extends('layouts.app')

@section('content')
<div class="container">
    <section class="section register min-vh-100 d-flex flex-column align-items-center justify-content-center py-4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6 d-flex flex-column align-items-center justify-content-center">
                    <div class="d-flex justify-content-center py-4">
                        <p class="logo d-flex align-items-center w-auto">
                            <img src="assets/img/delivery.png" alt="">
                            <span class="d-none d-lg-block text-decoration-none">MapMySpace</span>
                        </p>
                    </div><!-- End Logo -->

                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="pt-4 pb-2">
                                <h5 class="card-title text-center pb-0 fs-4">Create Your Account</h5>
                                <p class="text-center small">Fill in the information below to register</p>
                            </div>

                            <form class="row g-3" method="POST" action="{{ route('auth.register') }}" enctype="multipart/form-data">
                                @csrf

                                <div class="col-12">
                                    <label for="name" class="form-label">{{ __('Name') }}</label>
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name') }}" required autocomplete="name" autofocus>
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email">
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="password" class="form-label">{{ __('Password') }}</label>
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="new-password">
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" required autocomplete="new-password">
                                </div>

                                <div class="col-12">
                                    <label for="image" class="form-label">{{ __('Profile Image') }}</label>
                                    <input id="image" type="file"
                                        class="form-control @error('image') is-invalid @enderror" name="image"
                                        required accept="image/*">
                                    @error('image')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-12">
                                    <button class="btn btn-primary w-100" type="submit">Register</button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="credits">
                        Designed by <a href="">Abdullah</a>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>
@endsection
