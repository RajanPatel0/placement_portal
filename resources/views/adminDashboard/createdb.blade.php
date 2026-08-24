@extends('dashboardLayouts.base')

@section('title', 'Placement Companies')

@section('content')<!DOCTYPE html>
<html>
<head>
    <title>Add to {{ $table }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Add New Record to {{ $table }}</h1>
        
        <a href="{{ route('createdb', $table) }}" class="btn btn-secondary mb-3">Back</a>
        
        <form action="{{ route('storedb', $table) }}" method="POST">
            @csrf
            
            @foreach($columns as $column)
                @if($column !== 'id' && $column !== 'created_at' && $column !== 'updated_at')
                    <div class="mb-3">
                        <label for="{{ $column }}" class="form-label">{{ ucfirst(str_replace('_', ' ', $column)) }}</label>
                        <input type="text" class="form-control" id="{{ $column }}" name="{{ $column }}">
                    </div>
                @endif
            @endforeach
            
            <button type="submit" class="btn btn-success">Add Record</button>
        </form>
    </div>
</body>
</html>
@endsection