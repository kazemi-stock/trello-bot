@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">ویرایش مخاطب</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">خانه</a></li>
                            <li class="breadcrumb-item active">مخاطبین</li>
                            <li class="breadcrumb-item active">ویرایش</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">

            </div>
        </section>
    </div>
    <script>
        document.getElementById('dashboard').className = 'nav-link text-black-50'
        document.getElementById('contact').className = 'nav-link active text-white'
        document.getElementById('inbox').className = 'nav-link text-black-50'
        document.getElementById('send').className = 'nav-link text-black-50'
        document.getElementById('card').className = 'nav-link text-black-50'
        document.getElementById('event').className = 'nav-link text-black-50'
        document.getElementById('users').className = 'nav-link text-black-50'
        document.getElementById('setting').className = 'nav-link text-black-50'
    </script>
@endsection
