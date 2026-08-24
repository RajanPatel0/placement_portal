@extends('dashboardLayouts.base')

@section('title', 'Placement Companies')

@section('content')
hii
<!DOCTYPE html>
<html>
<head>
    <title>{{ $table }} - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="mb-1">{{ ucfirst(str_replace('_', ' ', $table)) }} Table</h1>
                <p class="text-muted mb-0">Total records: {{ $data->total() }}</p>
            </div>
            <div>
                <a href="{{ route('indexdb') }}" class="btn btn-outline-secondary me-2">
                    <i class="fas fa-arrow-left me-1"></i> Back to Tables
                </a>
                <a href="{{ route('createdb', $table) }}" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i> Add New Record
                </a>
            </div>
        </div>
        
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        <div class="card shadow-sm">
            <div class="card-header bg-light py-3">
                <h5 class="mb-0">Table Data</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                @foreach($columns as $column)
                                    <th class="py-3 px-3 border-bottom">
                                        {{ ucfirst(str_replace('_', ' ', $column)) }}
                                    </th>
                                @endforeach
                                <th class="py-3 px-3 border-bottom text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $row)
                                <tr class="border-bottom">
                                    @foreach($columns as $column)
                                        <td class="py-3 px-3">
                                            {{ $row->{$column} }}
                                        </td>
                                    @endforeach
                                    <td class="py-3 px-3 text-end">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('editdb', [$table, $row->id]) }}" 
                                               class="btn btn-outline-primary btn-sm me-1">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('deletedb', [$table, $row->id]) }}" 
                                                  method="POST" 
                                                  class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        
        @if($data->hasPages())
            <div class="mt-4">
                {{ $data->links() }}
            </div>
        @endif
    </div>
</body>
</html>

<style>
.container {
    max-width: 1400px;
    padding: 20px;
}

.card {
    border-radius: 8px;
    border: 1px solid #e9ecef;
    overflow: hidden;
}

.table th {
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    color: #495057;
    background-color: #f8f9fa;
}

.table td {
    vertical-align: middle;
    font-size: 14px;
}

.table-hover tbody tr:hover {
    background-color: #f8f9fa;
}

.btn-group .btn {
    border-radius: 4px;
    padding: 5px 10px;
}

.btn-outline-primary {
    border-color: #5d87ff;
    color: #5d87ff;
}

.btn-outline-primary:hover {
    background-color: #5d87ff;
    border-color: #5d87ff;
    color: white;
}

.btn-outline-danger {
    border-color: #f46a6a;
    color: #f46a6a;
}

.btn-outline-danger:hover {
    background-color: #f46a6a;
    border-color: #f46a6a;
    color: white;
}

.btn-success {
    background-color: #00b09b;
    border-color: #00b09b;
}

.btn-success:hover {
    background-color: #009688;
    border-color: #009688;
}

.alert {
    border-radius: 6px;
    border: none;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.alert-success {
    background-color: #f0fff4;
    border-left: 4px solid #28a745;
}

.pagination {
    margin-bottom: 0;
}

.pagination .page-item.active .page-link {
    background-color: #5d87ff;
    border-color: #5d87ff;
}

.pagination .page-link {
    color: #5d87ff;
    border: 1px solid #dee2e6;
    margin: 0 2px;
    border-radius: 4px;
}

.pagination .page-link:hover {
    background-color: #f8f9fa;
}

/* Responsive design */
@media (max-width: 768px) {
    .container {
        padding: 15px;
    }
    
    .d-flex {
        flex-direction: column;
        align-items: flex-start;
        gap: 15px;
    }
    
    .btn {
        width: 100%;
        margin-bottom: 5px;
    }
    
    .table-responsive {
        margin: 0 -15px;
        width: calc(100% + 30px);
    }
    
    .btn-group {
        display: flex;
        gap: 5px;
    }
}

/* Animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: fadeIn 0.3s ease-out;
}
</style>
@endsection