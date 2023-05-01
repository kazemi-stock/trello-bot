@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            @include('flash-message')
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">لیست مخاطبین</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">خانه</a></li>
                            <li class="breadcrumb-item active">مخاطبین</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header text-left">
                        <a href="#" onclick="$('#create-form').css('display', 'block')" class="btn btn-success px-4">جدید</a>
                    </div>
                    <table class="table table-bordered table-responsive table-striped table-hover d-table">
                        <thead>
                        <tr>
                            <th>ردیف</th>
                            <th>نام</th>
                            <th>تلفن</th>
                            <th>پیام ها ارسالی</th>
                            <th>پیام ها دریافتی</th>
                            <th>تاریخ ایجاد</th>
                            <th>عملیات</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($contacts as $contact)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $contact->name ?? '' }}</td>
                                <td>{{ $contact->phone ?? '' }}</td>
                                <td>{{ $contact->comments()->count() ?? 0 }}</td>
                                <td>{{ $contact->messages()->count() ?? 0 }}</td>
                                <td>{{ new \Carbon\Carbon($contact->created_at) }}</td>
                                <td>
                                    <a href="#!" onclick="edit({{ $contact }})" class="btn btn-sm btn-warning">ویرایش</a>
                                    <a href="#!"
                                       onclick="event.preventDefault();document.getElementById('delete-form').submit()"
                                       class="btn btn-sm btn-danger px-3">حذف</a>
                                    <form action="{{ route('contacts.delete', ['contact' => $contact]) }}"
                                          id="delete-form" method="post" style="display: none">
                                        @csrf
                                        @method('delete')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>

    <div class="col-6 position-absolute" style="top: 15%;left: 15%;display: none" id="create-form">
        <div class="container">
            <form action="{{ route('contacts.store') }}" method="post" class="card border rounded shadow">
                @csrf
                <div class="card-header">
                    <h4>فرم ایجاد مخاطب</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <p>نام:  <span class="text-danger">*</span></p>
                        </label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="نام را به فارسی وارد کنید... (مثال: احسان ملکی)" required>
                    </div>
                    <div class="form-group">
                        <label for="phone" class="form-label">
                            <p>تلفن:  <span class="text-danger">*</span></p>
                        </label>
                        <input type="text" name="phone" id="phone" class="form-control" placeholder="شماره موبایل را با 0 وارد کنید... (مثال: ...0938)" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary px-4">ثبت</button>
                        <button type="button" onclick="$('#create-form').css('display', 'none')" class="btn btn-danger px-4 float-left">بستن</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="col-6 position-absolute" style="top: 17%;left: 17%;display: none" id="edit-form">
        <div class="container">
            <form action="{{ route('contacts.update') }}" method="post" class="card border rounded shadow">
                @csrf
                @method('put')
                <input type="hidden" name="id" id="edit-id">
                <div class="card-header">
                    <h4>فرم ویرایش مخاطب</h4>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="name" class="form-label">
                            <p>نام:  <span class="text-danger">*</span></p>
                        </label>
                        <input type="text" name="name" id="edit-name" class="form-control" placeholder="نام را به فارسی وارد کنید... (مثال: احسان ملکی)" required>
                    </div>
                    <div class="form-group">
                        <label for="phone" class="form-label">
                            <p>تلفن:  <span class="text-danger">*</span></p>
                        </label>
                        <input type="text" name="phone" id="edit-phone" class="form-control" placeholder="شماره موبایل را با 0 وارد کنید... (مثال: ...0938)" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-warning px-4">ویرایش</button>
                        <button type="button" onclick="$('#edit-form').css('display', 'none')" class="btn btn-danger px-4 float-left">بستن</button>
                    </div>
                </div>
            </form>
        </div>
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
    <script>
        function edit(data) {
            $('#edit-name').val(data.name)
            $('#edit-phone').val(data.phone)
            $('#edit-id').val(data.id)
            $('#edit-form').css('display', 'block')
        }
    </script>
@endsection
