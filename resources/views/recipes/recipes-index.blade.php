@extends('layouts.app', ['activePage' => 'recipes', 'titlePage' => __('Recipes')])

@section('content')

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header card-header-primary">
                        <h4 class="card-title ">Recipes</h4>
                        <p class="card-category"> Here you can manage recipes</p>
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

                        @if (session('categories'))
                        <div>{{ session['categories'] }}</div>
                        @endif

                        <div class="row">
                            <div class="col-12 text-right">
                                <a href="/recipe-add" class="btn btn-sm btn-primary">Add Recipe</a>
                            </div>
                        </div>

                        <ul class="subsubsub">
                            <li class="all"><a href="/recipes/all" class="{{ $_SERVER['REQUEST_URI'] == '/recipes/all'  ? 'current' : ''}}">ALL <span class="count">({{ $allRecipesCount }})</span></a> |</li>
                            @foreach($recipeLangs as $key => $lang)
                            <li class="publish"><a href="/recipes/{{ $lang->language_code }}" class="{{ $_SERVER['REQUEST_URI'] == '/recipes/'. $lang->language_code  ? 'current' : ''}}" aria-current="page"> {{ Str::upper($lang->language_code) }} <span class="count"> ({{ $lang->total }}) </span></a> {{ $key != count($recipeLangs) - 1 ? '|' : ''}}</li>
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
                                        <th>Duration</th>
                                        <th>Cuisine</th>
                                        <th>Views</th>
                                        <th>Likes</th>
                                        <th>Author</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recipes as $i => $recipe)
                                    <tr>
                                        <th>{{ $i + 1 }}</th>
                                        <td>{{ $recipe->language() }}</td>
                                        <td>{{ $recipe->name }}</td>
                                        <td><img width="80" src="/uploads/recipes/{{ $recipe->image }}" /></td>
                                        <td>{{ Helpers::convertToHoursMins($recipe->duration) }}</td>
                                        <td style="text-align: center;">{{ $recipe->cuisine != null ? $recipe->cuisine->name : '-'}}</td>
                                        <td>{{ $recipe->noOfViews }}</td>
                                        <td>{{ $recipe->noOfLikes }}</td>
                                        <td>{{ $recipe->user->name }}</td>
                                        <td style="font-size: 18px;"><span class="badge" style="color:white;background-color:{{ $recipe->recipeStatus->color }};">{{ $recipe->recipeStatus->name }}</span></td>
                                        <td class="td-actions text-center">
                                            <a href="/recipe-edit/{{ $recipe->id }}"><button type="button" rel="tooltip" class="btn btn-success" data-original-title="" title="">
                                                    <i class="material-icons">edit</i>
                                                </button></a>
                                            <a href="/recipe-delete/{{ $recipe->id }}"><button type="button" rel="tooltip" class="btn btn-danger" data-original-title="" title="" onclick="return confirm('Are you sure?')">
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