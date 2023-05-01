@extends('layouts.app')
@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <div class="content-header">
            @include('flash-message')
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1 class="m-0 text-dark">داشبورد</h1>
                    </div><!-- /.col -->
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-left">
                            <li class="breadcrumb-item"><a href="{{ route('home') }}">خانه</a></li>
                            <li class="breadcrumb-item active">داشبورد</li>
                        </ol>
                    </div><!-- /.col -->
                </div><!-- /.row -->
            </div><!-- /.container-fluid -->
        </div>
        <!-- /.content-header -->

        <!-- Main content -->
        <section class="content">
            <div class="container-fluid">
                <!-- Small boxes (Stat box) -->
                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ \App\Models\Contact::count() }}</h3>

                                <p>مخاطبین</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-person"></i>
                            </div>
                            <a href="{{ route('contacts.index') }}" class="small-box-footer">اطلاعات بیشتر <i
                                    class="fa fa-arrow-circle-left"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ \App\Models\Message::count() }}</h3>

                                <p>پیامهای دریافتی</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-android-chat"></i>
                            </div>
                            <a href="{{ route('messages.index') }}" class="small-box-footer">اطلاعات بیشتر <i
                                    class="fa fa-arrow-circle-left"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-warning">
                            <div class="inner">
                                <h3>{{ \App\Models\Comment::count() }}</h3>

                                <p>پیامهای ارسالی</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-android-send"></i>
                            </div>
                            <a href="{{ route('comments.index') }}" class="small-box-footer">اطلاعات بیشتر <i
                                    class="fa fa-arrow-circle-left"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-danger">
                            <div class="inner">
                                <h3>{{ \App\Models\Card::count() }}</h3>

                                <p>کارتهای ثبت شده</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-card"></i>
                            </div>
                            <a href="{{ route('cards.index') }}" class="small-box-footer">اطلاعات بیشتر <i
                                    class="fa fa-arrow-circle-left"></i></a>
                        </div>
                    </div>
                    <!-- ./col -->
                </div>
                <!-- /.row -->
                <!-- Main row -->
                <div class="row">
                    <!-- Left col -->
                    <section class="col-lg-7 connectedSortable">
                        <!-- CHAT -->
                        <div class="card direct-chat direct-chat-primary">
                            <div class="card-header">
                                <h3 class="card-title d-inline-block py-0 ml-2">
                                    <i class="ion ion-android-chat"></i>
                                    گفتگو
                                </h3>
                                <input type="text" id="phone" name="phone" form="chat" value="{{ request('phone') ?? '' }}" class="form-control w-50 d-inline-block"
                                       placeholder="شماره موبایل...">
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-widget="collapse">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-widget="remove">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <div class="direct-chat-messages">
                                    @if(!empty($chats))
                                    @foreach($chats as $chat)
                                        @if($chat['name'] != 'admin')
                                    <div class="direct-chat-msg">
                                        <div class="direct-chat-info clearfix">
                                            <span
                                                class="direct-chat-name float-left">{{ $chat['name'] }}</span>
                                            <span class="direct-chat-timestamp float-right">{{ $chat['date'] }}</span>
                                        </div>
                                        <!-- /.direct-chat-info -->
                                        <div class="direct-chat-text">
                                            {{ $chat['text'] }}
                                        </div>
                                        <!-- /.direct-chat-text -->
                                    </div>
                                        @else
                                    <div class="direct-chat-msg right">
                                        <div class="direct-chat-info clearfix">
                                            <span
                                                class="direct-chat-name float-right">{{ $chat['name'] }}</span>
                                            <span class="direct-chat-timestamp float-left">{{ $chat['date'] }}</span>
                                        </div>
                                        <!-- /.direct-chat-info -->
                                        <div class="direct-chat-text">
                                            {{ $chat['text'] }}
                                        </div>
                                        <!-- /.direct-chat-text -->
                                    </div>
                                        @endif
                                    @endforeach
                                    @endif
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <form action="{{ route('home') }}" id="chat" method="get">
                                    <div class="input-group">
                                        <input type="text" name="message" placeholder="متن پیام..."
                                               class="form-control">
                                        <span class="input-group-append">
                      <button type="submit" class="btn btn-primary">Send</button>
                    </span>
                                    </div>
                                </form>
                            </div>
                            <!-- /.card-footer-->
                        </div>
                        <!--/.chat -->

                        <!-- List -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="ion ion-clipboard mr-1"></i>
                                    رویدادهای اخیر
                                </h3>

                                <div class="card-tools">
                                    <div class="card-tools">
                                        <button type="button" class="btn btn-tool" data-widget="collapse">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool" data-widget="remove">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                <ul class="todo-list">
                                    @foreach(\App\Models\Action::latest()->take(7)->get()->toArray() as $action)
                                        <li> کارت: {{ $action['data']['card']['name'] ?? '' }} -
                                            زمان: {{ new \Carbon\Carbon($action['date'], 'Asia/Tehran') }} - نوع: {{ $action['type'] }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <!-- /.card -->
                    </section>
                    <!-- /.Left col -->
                    <!-- right col (We are only adding the ID to make the widgets sortable)-->
                    <section class="col-lg-5 connectedSortable">

                        <!-- Map card -->
                        <div class="card bg-primary-gradient">
                            <div class="card-header no-border">
                                <h3 class="card-title">
                                    <i class="fa fa-send"></i>
                                    پیام های ارسالی اخیر
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-widget="collapse">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-widget="remove">
                                        <i class="fa fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <ul class=" list-group px-4">
                                    @foreach(\App\Models\Comment::latest()->limit(10)->with('contact')->get() as $comment)
                                        <li>{{ $comment->contact->name ?? '' }} : {{ $comment->body ?? '' }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="card bg-success-gradient">
                            <div class="card-header no-border">
                                <h3 class="card-title">
                                    <i class="fa fa-inbox"></i>
                                    پیامهای دریافتی اخیر
                                </h3>
                                <div class="card-tools">
                                    <!-- button with a dropdown -->
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-tool" data-widget="collapse">
                                            <i class="fa fa-minus"></i>
                                        </button>
                                        <button type="button" class="btn btn-tool" data-widget="remove">
                                            <i class="fa fa-times"></i>
                                        </button>
                                    </div>
                                    <!-- /. tools -->
                                </div>
                            </div>
                            <div class="card-body">
                                <ul class="list-group px-4">
                                    @foreach(\App\Models\Message::latest()->limit(10)->with('contact')->get() as $message)
                                        <li>{{ $message->contact->name ?? $message->contact->phone }} : {{ $message->body ?? '' }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </section>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <!-- /.content -->
    </div>
@endsection
