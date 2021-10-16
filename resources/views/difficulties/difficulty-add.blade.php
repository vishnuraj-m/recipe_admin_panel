@extends('layouts.app', ['activePage' => 'difficulties', 'titlePage' => __('Difficulties')])

@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form method="post" action="/difficulty-add" autocomplete="off" class="form-horizontal" enctype="multipart/form-data">
                    @csrf
                    @method('put')

                    <div class="card ">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">{{ __('Add Difficulty') }}</h4>
                            <p class="card-difficulty">{{ __('Difficulty information') }}</p>
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
                                        <option value=""></option>
                                            @foreach($available_locales as $i => $locale )
                                            <option value="{{ $i }}">{{ $locale }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('language_code'))
                                        <span id="language_code-error" class="error text-danger" for="input-language_code">{{ $errors->first('language_code') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-sm-2 col-form-label">{{ __('Difficulty') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('difficulty') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('difficulty') ? ' is-invalid' : '' }}" name="difficulty" id="input-difficulty" type="text" placeholder="{{ __('Difficulty') }}" required="true" aria-required="true" />
                                        @if ($errors->has('difficulty'))
                                        <span id="difficulty-error" class="error text-danger" for="input-difficulty">{{ $errors->first('difficulty') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer ml-auto mr-auto">
                            <button type="submit" class="btn btn-primary">{{ __('Add') }}</button>
                            <a href="/difficulties" class="btn btn-danger">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection