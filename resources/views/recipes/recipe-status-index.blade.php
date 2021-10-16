@extends('layouts.app', ['activePage' => 'statuses', 'titlePage' => __('Recipe Statuses')])

@section('content')

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">Recipe Statuses</h4>
                        <p class="card-category"> Here you can manage recipe statuses</p>
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

                        <!-- <div class="row">
                            <div class="col-12 text-right">
                                <a href="/status-add" class="btn btn-sm btn-primary">Add Recipe Status</a>
                            </div>
                        </div> -->

                        <div class="table-responsive">
                            <table id="datatable" class="table" style="width:100%">
                                <thead class=" text-primary">
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Color</th>
                                        <th style="text-align: center;">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($statuses as $status)
                                    <tr>
                                        <th>{{ $status->id }}</th>
                                        <td>{{ $status->name }}</td>
                                        <td style="font-size: 18px;"><span class="badge" style="color:white;background-color:{{ $status-> color }};">{{ $status->color }}</span></td>
                                        <td class="td-actions text-center">
                                            <a href="/status-edit/{{ $status->id }}"><button type="button" rel="tooltip" class="btn btn-success" data-original-title="" title="">
                                                    <i class="material-icons">edit</i>
                                                </button></a>
                                            <!-- <a href="/status-delete/{{ $status->id }}"><button type="button" rel="tooltip" class="btn btn-danger" data-original-title="" title="" onclick="return confirm('Are you sure?')">
                                                    <i class="material-icons">close</i>
                                                </button></a> -->
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