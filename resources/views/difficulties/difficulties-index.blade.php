@extends('layouts.app', ['activePage' => 'difficulties', 'titlePage' => __('Difficulties')])

@section('content')

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">Difficulties</h4>
                        <p class="card-category"> Here you can manage difficulties</p>
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
                                <a href="/difficulty-add" class="btn btn-sm btn-primary">Add Difficulty</a>
                            </div>
                        </div>

                        <ul class="subsubsub">
                            <li class="all"><a href="/difficulties/all" class="{{ $_SERVER['REQUEST_URI'] == '/difficulties/all'  ? 'current' : ''}}">ALL <span class="count">({{ $allDifficultiesCount }})</span></a> |</li>
                            @foreach($difficultyLangs as $key => $lang)
                            <li class="publish"><a href="/difficulties/{{ $lang->language_code }}" class="{{ $_SERVER['REQUEST_URI'] == '/difficulties/'. $lang->language_code  ? 'current' : ''}}" aria-current="page"> {{ Str::upper($lang->language_code) }} <span class="count"> ({{ $lang->total }}) </span></a> {{ $key != count($difficultyLangs) - 1 ? '|' : ''}}</li>
                            @endforeach
                        </ul>

                        <div class="table-responsive">
                            <table id="datatable" class="table" style="width:100%">
                                <thead class=" text-primary">
                                    <tr>
                                        <th>#</th>
                                        <th>Language</th>
                                        <th>Difficulty</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($difficulties as $i => $difficulty)
                                    <tr>
                                        <th>{{ $i + 1 }}</th>
                                        <td>{{ $difficulty->language() }}</td>
                                        <td>{{ $difficulty->difficulty }}</td>
                                        <td class="td-actions">
                                            <a href="/difficulty-edit/{{ $difficulty->id }}"><button type="button" rel="tooltip" class="btn btn-success" data-original-title="" title="">
                                                    <i class="material-icons">edit</i>
                                                </button></a>
                                            <a href="/difficulty-delete/{{ $difficulty->id }}"><button type="button" rel="tooltip" class="btn btn-danger" data-original-title="" title="" onclick="return confirm('Are you sure?')">
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