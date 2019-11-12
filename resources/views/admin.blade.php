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
                <div class="panel-heading header-style">Admin Dashboard</div>

                <div class="panel-body body-text-style">
                    You are logged-in to
                    <i>
                        <b>Admin View!</b>
                    </i>
                    <br/><br/>

                    Only admin can see this screen.... Congratulations!!!!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
