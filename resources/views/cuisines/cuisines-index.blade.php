@extends('layouts.app', ['activePage' => 'cuisines', 'titlePage' => __('Cuisines')])

@section('content')

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">Cuisines</h4>
                        <p class="card-category"> Here you can manage cuisines</p>
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
                                <a href="/cuisine-add" class="btn btn-sm btn-primary">Add Cuisine</a>
                            </div>
                        </div>

                        <ul class="subsubsub">
                            <li class="all"><a href="/cuisines/all" class="{{ $_SERVER['REQUEST_URI'] == '/cuisines/all'  ? 'current' : ''}}">ALL <span class="count">({{ $allCuisinesCount }})</span></a> |</li>
                            @foreach($cuisineLangs as $key => $lang)
                            <li class="publish"><a href="/cuisines/{{ $lang->language_code }}" class="{{ $_SERVER['REQUEST_URI'] == '/cuisines/'. $lang->language_code  ? 'current' : ''}}" aria-current="page"> {{ Str::upper($lang->language_code) }} <span class="count"> ({{ $lang->total }}) </span></a> {{ $key != count($cuisineLangs) - 1 ? '|' : ''}}</li>
                            @endforeach
                        </ul>

                        <div class="table-responsive">
                            <table id="datatable" class="table" style="width:100%">
                                <thead class=" text-primary">
                                    <tr>
                                        <th>#</th>
                                        <th>Language</th>
                                        <th>Name</th>
                                        <th>Image</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cuisines as $i => $cuisine)
                                    <tr>
                                        <th>{{ $i + 1 }}</th>
                                        <td>{{ $cuisine->language() }}</td>
                                        <td>{{ $cuisine->name }}</td>
                                        <td><img width="80" src="/uploads/cuisines/{{ $cuisine->image }}" /></td>
                                        <td style="font-size: 18px;"><span class="badge badge-success">{{ $cuisine->status == 1 ? 'Enabled' : 'Disabled' }}</span></td>
                                        <td class="td-actions text-center">
                                            <a href="/cuisine-edit/{{ $cuisine->id }}"><button type="button" rel="tooltip" class="btn btn-success" data-original-title="" title="">
                                                    <i class="material-icons">edit</i>
                                                </button></a>
                                            <a href="/cuisine-delete/{{ $cuisine->id }}"><button type="button" rel="tooltip" class="btn btn-danger" data-original-title="" title="" onclick="return confirm('Are you sure?')">
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