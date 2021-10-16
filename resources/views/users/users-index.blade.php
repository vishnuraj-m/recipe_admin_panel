@extends('layouts.app', ['activePage' => 'users', 'titlePage' => __('User')])

@section('content')

<?php

use Illuminate\Support\Facades\DB; ?>
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">Users</h4>
                        <p class="card-category"> Here you can manage users</p>
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
                                <a href="/user-add" class="btn btn-sm btn-primary">Add user</a>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table id="datatable" class="table" style="width:100%">
                                <thead class=" text-primary">
                                    <tr>
                                        <th>#</th>
                                        <th>Photo</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Recipes</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $i => $user)
                                    <tr>
                                        <th>{{ $i + 1 }}</th>
                                        @if ($user->image != null)
                                        <td><img style="border-radius: 50%;" width="50" height="50" src="{{ str_contains($user->image, 'http') == true ? $user->image : '/uploads/users/'. $user->image  }}" /></td>
                                        @else
                                        <td><img style="border-radius: 50%;" width="50" height="50" src="/default/no_image_placeholder.jpg" alt="..."></td>
                                        @endif
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        @if($user->usertype == 0) <td>Author</td>
                                        @elseif($user->usertype == 1) <td>Admin</td>
                                        @else <td>Moderator</td>
                                        @endif
                                        <td>{{ $user->recipes->count() }}</td>
                                        <td style="font-size: 18px;"><span class="badge badge-success">{{ $user->status == 1 ? 'Enabled' : 'Disabled' }}</span></td>
                                        <td class="td-actions text-center">
                                            <a href="/user-edit/{{ $user->id }}"><button type="button" rel="tooltip" class="btn btn-success" data-original-title="" title="">
                                                    <i class="material-icons">edit</i>
                                                </button></a>
                                            <a href="/user-delete/{{ $user->id }}"><button type="button" rel="tooltip" class="btn btn-danger" data-original-title="" title="" onclick="return confirm('Are you sure?')">
                                                    <i class="material-icons">close</i>
                                                </button></a>
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
@endsection