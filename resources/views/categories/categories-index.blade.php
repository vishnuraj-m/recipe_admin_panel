@extends('layouts.app', ['activePage' => 'categories', 'titlePage' => __('Categories')])

@section('content')

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">Categories</h4>
                        <p class="card-category"> Here you can manage categories</p>
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
                                <a href="/category-add" class="btn btn-sm btn-primary">Add Category</a>
                            </div>
                        </div>

                        <ul class="subsubsub">
                            <li class="all"><a href="/categories/all" class="{{ $_SERVER['REQUEST_URI'] == '/categories/all'  ? 'current' : ''}}">ALL <span class="count">({{ $allCategoriesCount }})</span></a> |</li>
                            @foreach($categoryLangs as $key => $lang)
                            <li class="publish"><a href="/categories/{{ $lang->language_code }}" class="{{ $_SERVER['REQUEST_URI'] == '/categories/'. $lang->language_code  ? 'current' : ''}}" aria-current="page"> {{ Str::upper($lang->language_code) }} <span class="count"> ({{ $lang->total }}) </span></a> {{ $key != count($categoryLangs) - 1 ? '|' : ''}}</li>
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
                                    @foreach ($categories as $i => $category)
                                    <tr>
                                        <th>{{ $i + 1 }}</th>
                                        <td>{{ $category->language() }}</td>
                                        <td>{{ $category->name }}</td>
                                        <td><img width="80" src="/uploads/categories/{{ $category->image }}" /></td>
                                        <td style="font-size: 18px;"><span class="badge badge-success">{{ $category->status == 1 ? 'Enabled' : 'Disabled' }}</span></td>
                                        <td class="td-actions text-center">
                                            <a href="/category-edit/{{ $category->id }}"><button type="button" rel="tooltip" class="btn btn-success" data-original-title="" title="">
                                                    <i class="material-icons">edit</i>
                                                </button></a>
                                            <a href="/category-delete/{{ $category->id }}"><button type="button" rel="tooltip" class="btn btn-danger" data-original-title="" title="" onclick="return confirm('Are you sure?')">
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