@extends('dashboardLayouts.base')

@section('title', 'Placement Companies')

@section('content')
<div class="container mt-5">
    <h1 class="mb-4">
        <i class="fas fa-edit text-primary me-2"></i>
        Edit Record in {{ $table }}
    </h1>
    
    <a href="{{ route('tabledb', $table) }}" class="btn btn-secondary mb-4">
        <i class="fas fa-arrow-left me-1"></i> Back to Table
    </a>
    
    <div class="card shadow-sm border-0">
        <div class="card-header bg-light py-3">
            <h5 class="mb-0 text-primary">
                <i class="fas fa-database me-2"></i>
                Editing Record #{{ $id }}
            </h5>
        </div>
        
        <div class="card-body p-4">
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-check-circle fa-lg me-3"></i>
                        <div>
                            <h6 class="mb-0">Success!</h6>
                            <p class="mb-0">{{ session('success') }}</p>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            
            <form action="{{ route('updatedb', [$table, $id]) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row">
                    @foreach($columns as $column)
                        @if($column !== 'id' && $column !== 'created_at' && $column !== 'updated_at')
                            <div class="col-md-6 mb-4">
                                <label for="{{ $column }}" class="form-label fw-medium">
                                    {{ ucfirst(str_replace('_', ' ', $column)) }}
                                </label>
                                <input type="text" 
                                       class="form-control form-control-lg border-1 shadow-sm" 
                                       id="{{ $column }}" 
                                       name="{{ $column }}" 
                                       value="{{ old($column, $record->{$column}) }}"
                                       style="border-color: #dee2e6;">
                                @error($column)
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif
                    @endforeach
                </div>
                
                <div class="border-top pt-4 mt-2">
                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ route('tabledb', $table) }}" class="btn btn-outline-secondary px-4 py-2">
                            <i class="fas fa-times me-1"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary px-4 py-2">
                            <i class="fas fa-save me-1"></i> Update Record
                        </button>
                    </div>
                </div>
            </form>
        </div>
        
        <div class="card-footer bg-light py-3">
            <div class="row">
                <div class="col-md-6">
                    <small class="text-muted">
                        <i class="fas fa-info-circle me-1"></i>
                        Table: {{ $table }}
                    </small>
                </div>
                <div class="col-md-6 text-md-end">
                    <small class="text-muted">
                        <i class="fas fa-id-card me-1"></i>
                        Record ID: {{ $id }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.container {
    max-width: 1200px;
}

.card {
    border-radius: 12px;
    overflow: hidden;
}

.card-header {
    border-bottom: 2px solid #f0f0f0;
}

.card-body {
    background-color: #fafafa;
}

.form-control {
    transition: all 0.3s ease;
    background-color: white;
}

.form-control:focus {
    border-color: #5d87ff;
    box-shadow: 0 0 0 3px rgba(93, 135, 255, 0.1);
    transform: translateY(-1px);
}

.form-label {
    color: #495057;
    font-size: 14px;
    text-transform: capitalize;
    margin-bottom: 8px;
}

.btn-primary {
    background: linear-gradient(135deg, #5d87ff 0%, #4a6fff 100%);
    border: none;
    transition: all 0.3s ease;
}

.btn-primary:hover {
    background: linear-gradient(135deg, #4a6fff 0%, #3a5fef 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(93, 135, 255, 0.3);
}

.btn-outline-secondary:hover {
    background-color: #f8f9fa;
    border-color: #dee2e6;
}

.alert-success {
    border-left: 4px solid #28a745;
    background-color: #f8fff9;
}

.text-danger {
    color: #dc3545 !important;
    font-size: 13px;
}

h1 {
    color: #2c3e50;
    font-weight: 600;
}

/* Responsive design */
@media (max-width: 768px) {
    .container {
        padding: 15px;
    }
    
    .card-body {
        padding: 20px !important;
    }
    
    .col-md-6 {
        width: 100%;
        margin-bottom: 20px;
    }
    
    .d-flex {
        flex-direction: column;
        gap: 10px;
    }
    
    .btn {
        width: 100%;
    }
}

/* Animation for alerts */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.alert {
    animation: fadeIn 0.3s ease-out;
}
</style>
@endsection