<style>
    .header-style {
        text-align: center;
        font-size: x-large;
        font-family: "Andale Mono";
    }

    .body-text-style {
        font-family: "Andale Mono";
    }
</style>


@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading header-style">Dashboard</div>

                <div class="panel-body body-text-style">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


