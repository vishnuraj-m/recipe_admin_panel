@extends('layouts.app', ['activePage' => 'recipes', 'titlePage' => __('Recipes')])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form method="post" action="/recipe-update/{{ $recipes->id }}" autocomplete="off" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <div class="card ">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">{{ __('Edit Recipe') }}</h4>
                            <p class="card-category">{{ __('Recipe information') }}</p>
                        </div>
                        <div class="card-body ">
                            @if (session('status'))
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="alert alert-success">
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <i class="material-icons">close</i>
                                        </button>
                                        <span>{{ session('status') }}</span>
                                    </div>
                                </div>
                            </div>
                            @endif

                            <div class="row">
                                <label class="col-sm-2 col-form-label">{{ __('Language') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('language_code') ? ' has-danger' : '' }}">
                                        <select name="language_code" id="language_code" class="form-control">
                                            @foreach($available_locales as $i => $locale )
                                            <option value="{{ $i }}" {{ $i == $recipes->language_code ? 'selected': '' }} >{{ $locale }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('language_code'))
                                        <span id="language_code-error" class="error text-danger" for="input-language_code">{{ $errors->first('language_code') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div><br>

                            <div class="row">
                                <label class="col-sm-2 col-form-label">{{ __('Image') }}</label>
                                <div class="col-sm-7">
                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                        <div class="fileinput-new img-thumbnail" style="width: 200px; height: 125px;">
                                            @if ($recipes->image != null)
                                            <img src="/uploads/recipes/{{ $recipes->image }}" alt="...">
                                            @else
                                            <img src="/default/upload_image.png" alt="...">
                                            @endif
                                        </div>
                                        <div class="fileinput-preview fileinput-exists img-thumbnail" style="max-width: 200px; max-height: 125px;"></div>
                                        <div>
                                            <span class="btn btn-outline-secondary btn-file"><span class="fileinput-new">Select image</span><span class="fileinput-exists">Change</span><input id="image" type="file" name="image" value="{{ $recipes->image }}"></span>
                                            <a href="#" class="btn btn-outline-secondary fileinput-exists" data-dismiss="fileinput">Remove</a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('userId') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('userId') ? ' is-invalid' : '' }}" name="userId" id="input-userId" type="text" placeholder="{{ __('userId') }}" value="{{ $recipes->userId }}" hidden />
                                        @if ($errors->has('userId'))
                                        <span id="userId-error" class="error text-danger" for="input-userId">{{ $errors->first('userId') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-sm-2 col-form-label">{{ __('Name') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="input-name" type="text" placeholder="{{ __('Name') }}" value="{{ $recipes->name }}" required="true" aria-required="true" />
                                        @if ($errors->has('name'))
                                        <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-sm-2 col-form-label">{{ __('Duration') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('duration') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('duration') ? ' is-invalid' : '' }}" name="duration" id="input-duration" type="text" value="{{ $recipes->duration }}" placeholder="{{ __('Duration') }}" required />
                                        @if ($errors->has('duration'))
                                        <span id="duration-error" class="error text-danger" for="input-duration">{{ $errors->first('duration') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-sm-2 col-form-label">{{ __('Serving') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('noOfServing') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('noOfServing') ? ' is-invalid' : '' }}" name="noOfServing" id="input-noOfServing" type="text" value="{{ $recipes->noOfServing }}" placeholder="{{ __('Serving') }}" required />
                                        @if ($errors->has('noOfServing'))
                                        <span id="noOfServing-error" class="error text-danger" for="input-noOfServing">{{ $errors->first('noOfServing') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-sm-2 col-form-label">{{ __('Difficulty') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('difficulty_id') ? ' has-danger' : '' }}">
                                        <select name="difficulty_id" id="difficulty_id" class="form-control">
                                            @foreach($difficulties as $difficulty )
                                            <option value="{{ $difficulty->id }}" {{ ( $difficulty->id  == $recipes->difficulty_id) ? 'selected' : '' }}>{{ $difficulty->difficulty }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('difficulty_id'))
                                        <span id="difficulty_id-error" class="error text-danger" for="input-difficulty_id">{{ $errors->first('difficulty_id') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            

                            <div class="row">
                                <label class="col-sm-2 col-form-label">{{ __('Category') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('categories') ? ' has-danger' : '' }}">
                                        <select multiple style="height: 100px;" class="form-control" data-style="btn btn-link" id="input-category" name="category[]">
                                            @foreach($categories as $category )
                                            <option value="{{ $category->id }}" {{ ($selectedCategories->contains('categoryId', $category->id)) ? 'selected' : ''}}>{{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('categories'))
                                        <span id="categories-error" class="error text-danger" for="input-categories">{{ $errors->first('categories') }}</span>
                                        @endif
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <label class="col-sm-2 col-form-label">{{ __('Cuisine (optional)') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('cuisine_id') ? ' has-danger' : '' }}">
                                        <select name="cuisine_id" id="cuisine_id" class="form-control">
                                            <option value="">Select Cuisine</option>
                                            @foreach($cuisines as $cuisine )
                                            <option value="{{ $cuisine->id }}" {{ ( $cuisine->id  == $recipes->cuisine_id) ? 'selected' : '' }}>{{ $cuisine->name }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('cuisine_id'))
                                        <span id="cuisine_id-error" class="error text-danger" for="input-cuisine_id">{{ $errors->first('cuisine_id') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-sm-2 col-form-label">{{ __('Ingredients') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('ingredients') ? ' has-danger' : '' }}">
                                        <textarea class="form-control{{ $errors->has('ingredients') ? ' is-invalid' : '' }}" name="ingredients" id="input-ingredients" type="text" placeholder="{{ __('Ingredients') }}" rows="8" required>{{ $recipes->ingredients }}</textarea>
                                        @if ($errors->has('ingredients'))
                                        <span id="ingredients-error" class="error text-danger" for="input-ingredients">{{ $errors->first('ingredients') }}</span>
                                        @endif
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <label class="col-sm-2 col-form-label">{{ __('Steps') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('steps') ? ' has-danger' : '' }}">
                                        <textarea class="form-control{{ $errors->has('steps') ? ' is-invalid' : '' }}" name="steps" id="input-steps" type="text" placeholder="{{ __('Steps') }}" required rows="8">{{ $recipes->steps }}</textarea>
                                        @if ($errors->has('steps'))
                                        <span id=" steps-error" class="error text-danger" for="input-steps">{{ $errors->first('steps') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <label class="col-sm-2 col-form-label">{{ __('Website URL (optional)') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('websiteUrl') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('websiteUrl') ? ' is-invalid' : '' }}" name="websiteUrl" id="input-websiteUrl" type="text" value="{{ $recipes->websiteUrl }}" placeholder="{{ __('Website URL') }}" />
                                        @if ($errors->has('websiteUrl'))
                                        <span id="websiteUrl-error" class="error text-danger" for="input-websiteUrl">{{ $errors->first('websiteUrl') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-sm-2 col-form-label">{{ __('Youtube URL (optional)') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('youtubeUrl') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('youtubeUrl') ? ' is-invalid' : '' }}" name="youtubeUrl" id="input-youtubeUrl" type="text" value="{{ $recipes->youtubeUrl }}" placeholder="{{ __('Youtube URL') }}" />
                                        @if ($errors->has('youtubeUrl'))
                                        <span id="youtubeUrl-error" class="error text-danger" for="input-youtubeUrl">{{ $errors->first('youtubeUrl') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-sm-2 col-form-label">{{ __('Status') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('status') ? ' has-danger' : '' }}">
                                        <select name="status" id="status" class="form-control">
                                            @foreach($statuses as $status )
                                            <option value="{{ $status->id }}" {{ ( $status->id  == $recipes->status) ? 'selected' : '' }}>{{ $status->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer ml-auto mr-auto">
                            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                            <a href="/recipes/all" class="btn btn-danger">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script type='text/javascript'>
    $(document).ready(function() {
        // Language Change
        $('#language_code').change(function() {

            // Language id
            var id = $(this).val();
            var recipeId = $('#recipeId').val();

            // Empty the dropdown
            $('#difficulty_id').find('option').not(':first').remove();
            $('#input-category').empty();
            $('#cuisine_id').find('option').not(':first').remove();

            // AJAX request 
            getDifficulties(id);
            getCategories(id);
            getCuisines(id);
            
        });

        function getDifficulties(id) {
            $.ajax({
                url: '/getDifficulties/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {

                    var len = 0;
                    if (response['data'] != null) {
                        len = response['data'].length;
                    }
                    
                    difficltyId = $('#selDifficulty').val();

                    if (len > 0) {
                        // Read data and create <option >
                        for (var i = 0; i < len; i++) {
                            var id = response['data'][i].id;
                            var name = response['data'][i].difficulty;
                            var option = "<option value='" + id + "'>" + name + "</option>";
                            $('#difficulty_id').append(option);
                        }
                    }

                }
            });
        }

        function getCategories(id) {
            $.ajax({
                url: '/getCategories/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {

                    var len = 0;
                    if (response['data'] != null) {
                        len = response['data'].length;
                    }

                    if (len > 0) {
                        // Read data and create <option >
                        for (var i = 0; i < len; i++) {
                            var id = response['data'][i].id;
                            var name = response['data'][i].name;
                            var option = "<option value='" + id + "'>" + name + "</option>";
                            $('#input-category').append(option);
                        }
                    }

                }
            });
        }

        function getCuisines(id) {
            $.ajax({
                url: '/getCuisines/' + id,
                type: 'get',
                dataType: 'json',
                success: function(response) {

                    var len = 0;
                    if (response['data'] != null) {
                        len = response['data'].length;
                    }

                    if (len > 0) {
                        // Read data and create <option >
                        for (var i = 0; i < len; i++) {
                            var id = response['data'][i].id;
                            var name = response['data'][i].name;
                            var option = "<option value='" + id + "'>" + name + "</option>";
                            $('#cuisine_id').append(option);
                        }
                    }

                }
            });
        }
    });
</script>
@endpush