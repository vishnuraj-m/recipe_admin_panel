@extends('layouts.app', ['activePage' => 'statuses', 'titlePage' => __('Recipe Statuses')])


@section('content')
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form method="post" action="/status-update/{{ $statuses->id }}" autocomplete="off" class="form-horizontal">
                    @csrf
                    @method('put')

                    <div class="card ">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">{{ __('Edit Recipe Status') }}</h4>
                            <p class="card-category">{{ __('Recipe status information') }}</p>
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
                                <label class="col-sm-2 col-form-label">{{ __('Name') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('name') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" id="input-name" type="text" placeholder="{{ __('Name') }}" value="{{ $statuses->name }}" required="true" aria-required="true" />
                                        @if ($errors->has('name'))
                                        <span id="name-error" class="error text-danger" for="input-name">{{ $errors->first('name') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <label class="col-sm-2 col-form-label">{{ __('Color') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('color') ? ' has-danger' : '' }}">
                                        <input class="form-control{{ $errors->has('color') ? ' is-invalid' : '' }} colorpicker" name="color" id="input-color" type="text" placeholder="{{ __('Color') }}" value="{{ $statuses->color }}" />
                                        <input class="form-control{{ $errors->has('color') ? ' is-invalid' : '' }} color_value" name="color" id="input-color" type="text" placeholder="{{ __('Color') }}" value="{{ $statuses->color }}" hidden />
                                        @if ($errors->has('color'))
                                        <span id="color-error" class="error text-danger" for="input-color">{{ $errors->first('color') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer ml-auto mr-auto">
                            <button type="submit" class="btn btn-primary">{{ __('Update') }}</button>
                            <a href="/statuses" class="btn btn-danger">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
    $(".colorpicker").spectrum({
        allowEmpty: true,
        move: function(color) {
            $('.color_value').val(color.toHexString());
        }
    });
</script>
@endpush