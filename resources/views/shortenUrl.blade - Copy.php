@extends('layouts.layout')

@section('content')
<url-short-component></url-short-component>
    <div class="container">
        <h1>URLs Shortener</h1>

        <div class="card">
            <div class="card-header">
                <form method="POST" action="{{ route('generate.short.url.post') }}">
                    @csrf
                    <div class="input-group mb-3">
                        <input required type="text" name="link" class="form-control" placeholder="Enter URL" aria-label="Recipient's username" aria-describedby="basic-addon2">
                        <div class="input-group-append">
                            <button class="btn btn-success" type="submit">Generate Short Url</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">

                @if (Session::has('success'))
                    <div class="alert alert-success">
                        <p>{{ Session::get('success') }}</p>
                    </div>
                @endif

                <table class="table table-bordered table-sm">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Short Url</th>
                        <th>Full Link</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($shortUrls as $row)
                        <tr>
                            <td>{{ $row->id }}</td>
                            <td><a href="{{ route('short.url', $row->hash) }}" target="_blank">{{ route('short.url', $row->hash) }}</a></td>
                            <td>{{ $row->link }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

@endsection
