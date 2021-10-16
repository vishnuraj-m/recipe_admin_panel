@extends('layouts.app', ['activePage' => 'notifications', 'titlePage' => __('notifications')])

@section('content')

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">Notifications</h4>
                        <p class="card-notification"> Here you can manage notifications</p>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <i class="material-icons">close</i>
                            </button>
                            <span>
                                {{ session('status')}}</span>
                        </div>
                        @endif

                        <div class="row">
                            <div class="col-12 text-right">
                                <a href="/notification-add" class="btn btn-sm btn-primary">Add Notification</a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="datatable" class="table" style="width:100%">
                                <thead class=" text-primary">
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Message</th>
                                        <th>Image</th>
                                        <th style="text-align: center;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($notifications as $i => $notification)
                                    <tr>
                                    <th>{{ $i + 1 }}</th>
                                        <td>{{ $notification->title }}</td>
                                        <td>{{ $notification->message }}</td>
                                        <td><img width="80" height="80" src="/uploads/notifications/{{ $notification->image }}" /></td>
                                        <td class="td-actions text-center">
                                            <form action="/bulksend/{{ $notification->id }}" method="post">
                                                @csrf
                                                @method('post')
                                                <button type="submit" rel="tooltip" class="btn btn-info" data-original-title="" title="">
                                                    <i class="material-icons">send</i>
                                                </button>
                                                <a href="/notification-edit/{{ $notification->id }}"><button type="button" rel="tooltip" class="btn btn-success" data-original-title="" title="">
                                                        <i class="material-icons">edit</i>
                                                    </button></a>
                                                <a href="/notification-delete/{{ $notification->id }}"><button type="button" rel="tooltip" class="btn btn-danger" data-original-title="" title="" onclick="return confirm('Are you sure?')">
                                                        <i class="material-icons">close</i>
                                                    </button></a>
                                            </form>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<footer class="footer">
    <div class="container-fluid">
        <div class="copyright float-right">
            © Copyright 2020 by <a href="https://tac-techs.com">Tac-Techs</a>. All Rights Reserved
        </div>
    </div>
</footer>
</div>
@endsection