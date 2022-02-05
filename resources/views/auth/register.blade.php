@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-sm-6">
            <div class="card">

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="ui col-9 mx-auto text-center p-2">
                            <i class="fas fa-id-card" ></i>
                                <input id="name" type="text" placeholder="name" class=" @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="ui col-9 mt-3 mx-auto text-center p-2">
                            <i class="fas fa-envelope" ></i>
                                <input id="email" type="email" placeholder="email" class="@error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="ui col-9 mt-3 mx-auto text-center p-2">
                            <i class="fas fa-lock-alt" ></i>
                        <input id="password" type="password" placeholder="password" class=" @error('password') is-invalid @enderror" 
                                name="password" required autocomplete="new-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="ui col-9 mt-3 mx-auto text-center p-2">
                            <i class="fas fa-lock-alt" ></i>
                                <input id="password-confirm" placeholder="confirm-password" type="password" class="" 
                                        name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <div class="form-group row mt-5">
                            <div class="col-12">
                                <button type="submit" class="btn-block col-10 mx-auto" >
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                    <li class="col-4 mt-2 ml-2 text-center">
                        <a class="nav-link" href="{{ route('login') }}">
                            <i class="fad fa-sign-in"></i> {{ __('Login') }}
                        </a>
                    </li>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
