@extends('layouts.layout')
<!-- CSRF Token -->
@section('content')
    <div class="bg_message">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card" style="min-height: 300px;">
                        <h2 class="card-header">Message</h2>

                        <div class="card-body" style="text-align: center; color: red;">{{ $message }}</div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
