@extends('layouts.app', ['activePage' => 'profile', 'titlePage' => __('User Profile')])


@section('content')

<script src="https://cdn.ckeditor.com/ckeditor5/25.0.0/classic/ckeditor.js"></script>

<div class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <form method="post" action="/settings-update" autocomplete="off" class="form-horizontal">
                    @csrf
                    @method('put')

                    @foreach ($settings as $setting)

                    <div class="card ">
                        <div class="card-header card-header-primary">
                            <h4 class="card-title">{{ __('Settings') }}</h4>
                        </div>
                        <div class="card-body ">
                            @if (session('status'))
                            <div class="alert alert-success">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <i class="material-icons">close</i>
                                </button>
                                <span>
                                    {{ session('status')}}</span>
                            </div>
                            @endif

                            <br>
                            <div><b>APPROVAL SYSTEM</b></div><br>
                            <div class="row">
                                <label class="col-sm-2 col-form-label">{{ __('Auto Approve Recipes') }}</label>
                                <div class="col-sm-7">
                                    <div class="togglebutton">
                                        <label>
                                            <input id="auto_approve" name="auto_approve" type="checkbox" {{ $setting->auto_approve == 0 ? '' : 'checked' }}>
                                            <span class="toggle"></span>
                                        </label>
                                    </div>
                                </div>
                            </div><br>

                            <div><b>NOTIFICATION SETTINGS</b></div><br>
                            <div class="row">
                                <label class="col-sm-2 col-form-label">{{ __('Firebase FCM Key') }}</label>
                                <div class="col-sm-7">
                                    <div class="form-group{{ $errors->has('fcm_key') ? ' has-danger' : '' }}">

                                        <input class="form-control{{ $errors->has('fcm_key') ? ' is-invalid' : '' }}" name="fcm_key" id="input-fcm_key" type="text" value="{{ $setting->fcm_key }}" />
                                        @if ($errors->has('fcm_key'))
                                        <span id="fcm_key-error" class="error text-danger" for="input-fcm_key">{{ $errors->first('fcm_key') }}</span>
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <br>
                            <div><b>OTHER</b></div><br>
                            <div class="row">
                                <label class="col-sm-2 col-form-label">{{ __('Privacy Policy') }}</label>
                                <div class="col-sm-8">
                                    <textarea name="privacy_policy" id="privacy_policy">{{ $setting->privacy_policy }}</textarea>
                                    <script>
                                        ClassicEditor
                                            .create(document.querySelector('#privacy_policy'))
                                            .then(editor => {
                                                console.log(editor);
                                            })
                                            .catch(error => {
                                                console.error(error);
                                            });
                                    </script>
                                </div>
                            </div><br>

                            <div class="row">
                                <label class="col-sm-2 col-form-label">{{ __('Terms & Conditions') }}</label>
                                <div class="col-sm-8">
                                    <textarea name="terms_and_conditions" id="terms_and_conditions">{{ $setting->terms_and_conditions }}</textarea>
                                    <script>
                                        ClassicEditor
                                            .create(document.querySelector('#terms_and_conditions'))
                                            .then(editor => {
                                                console.log(editor);
                                            })
                                            .catch(error => {
                                                console.error(error);
                                            });
                                    </script>
                                </div>
                            </div><br>
                        </div>
                        <div class="card-footer ml-auto mr-auto">
                            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                        </div>
                    </div>

                    @endforeach
                </form>
            </div>
        </div>
    </div>
</div>
@endsection