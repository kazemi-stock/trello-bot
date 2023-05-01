@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="my-5 text-center">
                <img src="{{ asset('assets/img/parsgreen_logo.png') }}" alt="ParsGreen">
                <img src="{{ asset('assets/img/trello_logo.png') }}" alt="Trello">
            </div>
            <div class="card">
                <div class="card-header">ورود به پنل مدیریت ربات ترلو</div>

                <div class="card-body">
                    <form action="{{ route('login') }}" method="post" class="form-horizontal">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label for="email" class="w-100 control-label">
                                        <span>ایمیل:</span>
                                    <input type="email" class="form-control d-inline-block w-75 @error('email') is-invalid @enderror" name="email" id="email" placeholder="ایمیل را وارد کنید..." value="{{ old('email') }}" required autocomplete="email" autofocus>
                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-12">
                                    <label for="password" class="w-100 control-label">
                                        <span>پسورد:</span>
                                        <input type="password" class="form-control d-inline-block w-75 @error('password') is-invalid @enderror" name="password" id="password" placeholder="پسورد را وارد کنید..." required autocomplete="current-password">
                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </label>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-offset-2 col-sm-10">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">مرا به خاطر بسپار</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.card-body -->
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary px-4 float-left">ورود</button>
                        </div>
                        <!-- /.card-footer -->
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
