@extends('dashboardLayouts.base')

@section('title', 'Database Tables Manager')

@section('content')

<div class="content-body">
    <div class="container-fluid">
        <!-- Page Header -->
        <div class="row page-titles mx-0">
            <div class="col-sm-6 p-md-0">
                <div class="welcome-text">
                    <h4>Database Manager</h4>
                    <p class="mb-0">Manage all database tables and records</p>
                </div>
            </div>
            <div class="col-sm-6 p-md-0 justify-content-sm-end mt-2 mt-sm-0 d-flex">
                <div class="dropdown">
                    <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="fas fa-cog me-2"></i> Actions
                    </button>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="#"><i class="fas fa-sync-alt me-2"></i> Refresh All</a>
                        <a class="dropdown-item" href="#"><i class="fas fa-download me-2"></i> Export List</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#"><i class="fas fa-info-circle me-2"></i> Database Info</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row">
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-md">
                                    <span class="avatar-title bg-soft-primary text-primary rounded-circle font-size-18">
                                        <i class="fas fa-database"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="font-size-14 mb-1">Total Tables</h5>
                                <h4 class="mb-0">{{ count($tables) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-md">
                                    <span class="avatar-title bg-soft-success text-success rounded-circle font-size-18">
                                        <i class="fas fa-table"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="font-size-14 mb-1">Active</h5>
                                <h4 class="mb-0">{{ count($tables) }}</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-md">
                                    <span class="avatar-title bg-soft-info text-info rounded-circle font-size-18">
                                        <i class="fas fa-server"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="font-size-14 mb-1">Database Size</h5>
                                <h4 class="mb-0">-- MB</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-sm-6">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <div class="flex-shrink-0 me-3">
                                <div class="avatar-md">
                                    <span class="avatar-title bg-soft-warning text-warning rounded-circle font-size-18">
                                        <i class="fas fa-users"></i>
                                    </span>
                                </div>
                            </div>
                            <div class="flex-grow-1">
                                <h5 class="font-size-14 mb-1">Users Table</h5>
                                <h4 class="mb-0">--</h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success Message -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <div class="d-flex align-items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle fa-lg"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-0">Success!</h6>
                        <p class="mb-0">{{ session('success') }}</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            </div>
        @endif

        <!-- Search and Filter -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group">
                                    <span class="input-group-text bg-transparent">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" class="form-control" id="searchTables" placeholder="Search tables...">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="d-flex justify-content-end">
                                    <div class="btn-group" role="group">
                                        <input type="radio" class="btn-check" name="filter" id="all" checked>
                                        <label class="btn btn-outline-primary" for="all">All</label>
                                        
                                        <input type="radio" class="btn-check" name="filter" id="system">
                                        <label class="btn btn-outline-primary" for="system">System</label>
                                        
                                        <input type="radio" class="btn-check" name="filter" id="custom">
                                        <label class="btn btn-outline-primary" for="custom">Custom</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Grid -->
        <div class="row" id="tablesContainer">
            @forelse($tables as $table)
                <div class="col-xl-3 col-lg-4 col-md-6 mb-4 table-card" data-table="{{ $table }}">
                    <div class="card h-100">
                        <div class="card-header bg-transparent">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0">
                                    <i class="fas fa-table text-primary me-2"></i>
                                    <span class="table-name">{{ $table }}</span>
                                </h6>
                                <span class="badge bg-soft-primary">Table</span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Display Name</small>
                                <h6 class="mb-0">{{ ucwords(str_replace(['_', '-'], ' ', $table)) }}</h6>
                            </div>
                            <div class="mb-3">
                                <small class="text-muted d-block mb-1">Records Count</small>
                                <h6 class="mb-0">--</h6>
                            </div>
                        </div>
                        <div class="card-footer bg-transparent border-top">
                            <div class="d-grid gap-2 d-md-flex justify-content-md-between">
                                <a href="{{ route('tabledb', $table) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i> View
                                </a>
                                <a href="{{ route('createdb', $table) }}" class="btn btn-sm btn-success">
                                    <i class="fas fa-plus me-1"></i> Add New
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="card">
                        <div class="card-body text-center py-5">
                            <div class="empty-state">
                                <i class="fas fa-database fa-4x text-muted mb-4"></i>
                                <h4>No Database Tables Found</h4>
                                <p class="text-muted mb-4">There are no tables available in the database.</p>
                                <button class="btn btn-primary">
                                    <i class="fas fa-plus me-2"></i>Create New Table
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination (if needed) -->
        @if(count($tables) > 12)
            <div class="row">
                <div class="col-12">
                    <nav aria-label="Page navigation">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Previous</a>
                            </li>
                            <li class="page-item active"><a class="page-link" href="#">1</a></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.table-card .card {
    border: 1px solid #e9ecef;
    transition: all 0.2s ease;
    border-radius: 8px;
    overflow: hidden;
}

.table-card .card:hover {
    border-color: #5d87ff;
    transform: translateY(-2px);
}

.table-card .card-header {
    background-color: #f8fafc;
    border-bottom: 1px solid #e9ecef;
    padding: 12px 16px;
}

.table-card .card-body {
    padding: 16px;
}

.table-card .card-footer {
    background-color: #f8fafc;
    padding: 12px 16px;
}

.table-card .badge {
    font-size: 11px;
    padding: 4px 8px;
}

.empty-state {
    padding: 40px 0;
}

.empty-state i {
    opacity: 0.5;
}

.btn-check:checked + .btn-outline-primary {
    background-color: #5d87ff;
    border-color: #5d87ff;
    color: white;
}

.avatar-md {
    width: 50px;
    height: 50px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.bg-soft-primary {
    background-color: rgba(93, 135, 255, 0.1) !important;
}

.bg-soft-success {
    background-color: rgba(25, 195, 125, 0.1) !important;
}

.bg-soft-info {
    background-color: rgba(73, 190, 255, 0.1) !important;
}

.bg-soft-warning {
    background-color: rgba(255, 159, 67, 0.1) !important;
}
</style>

<script>
$(document).ready(function() {
    // Search functionality
    $('#searchTables').on('keyup', function() {
        var value = $(this).val().toLowerCase();
        $('.table-card').filter(function() {
            var tableName = $(this).data('table').toLowerCase();
            var displayName = $(this).find('.table-name').text().toLowerCase();
            $(this).toggle(tableName.indexOf(value) > -1 || displayName.indexOf(value) > -1);
        });
    });

    // Filter functionality
    $('input[name="filter"]').change(function() {
        var filter = $(this).attr('id');
        $('.table-card').show();
        
        if (filter === 'system') {
            $('.table-card').each(function() {
                var tableName = $(this).data('table');
                if (!tableName.startsWith('system_') && 
                    !tableName.startsWith('auth_') && 
                    !tableName.startsWith('password_') &&
                    tableName !== 'migrations' &&
                    tableName !== 'cache' &&
                    tableName !== 'failed_jobs' &&
                    tableName !== 'jobs') {
                    $(this).hide();
                }
            });
        } else if (filter === 'custom') {
            $('.table-card').each(function() {
                var tableName = $(this).data('table');
                if (tableName.startsWith('system_') || 
                    tableName.startsWith('auth_') || 
                    tableName.startsWith('password_') ||
                    tableName === 'migrations' ||
                    tableName === 'cache' ||
                    tableName === 'failed_jobs' ||
                    tableName === 'jobs') {
                    $(this).hide();
                }
            });
        }
    });

    // Card hover effect
    $('.table-card .card').hover(
        function() {
            $(this).addClass('shadow-sm');
        },
        function() {
            $(this).removeClass('shadow-sm');
        }
    );
});
</script>



@endsection


