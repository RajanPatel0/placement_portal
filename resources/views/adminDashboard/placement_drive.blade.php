@extends('dashboardLayouts.base')

@section('content')

<style>
    :root {
        --primary: #3498db;
        --secondary: #2c3e50;
        --success: #27ae60;
        --warning: #f39c12;
        --danger: #e74c3c;
        --light: #ecf0f1;
        --dark: #2c3e50;
    }



   


   

    .main-content {
        padding: 20px;
    }

    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        transition: transform 0.3s;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .card-header {
        background-color: white;
        border-bottom: 1px solid #eee;
        font-weight: 600;
        padding: 15px 20px;
        border-radius: 10px 10px 0 0 !important;
    }

    .status-badge {
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: 500;
    }

    .status-upcoming {
        background-color: #d4edda;
        color: #155724;
    }

    .status-ongoing {
        background-color: #fff3cd;
        color: #856404;
    }

    .status-completed {
        background-color: #d1ecf1;
        color: #0c5460;
    }

    .status-cancelled {
        background-color: #f8d7da;
        color: #721c24;
    }

    .drive-type-badge {
        padding: 4px 8px;
        border-radius: 15px;
        font-size: 0.75rem;
        background-color: #e9ecef;
        color: #495057;
    }

    .action-btn {
        padding: 5px 10px;
        border-radius: 5px;
        margin-right: 5px;
        font-size: 0.85rem;
    }

    .stats-card {
        text-align: center;
        padding: 20px;
    }

    .stats-number {
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 5px;
    }

    .stats-label {
        font-size: 0.9rem;
        color: #6c757d;
    }

    .form-section {
        background-color: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }

    .form-section-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 15px;
        padding-bottom: 10px;
        border-bottom: 1px solid #eee;
    }

    .required:after {
        content: " *";
        color: var(--danger);
    }

    .drive-card {
        cursor: pointer;
        transition: all 0.3s;
    }

    .drive-card:hover {
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .company-logo {
        width: 60px;
        height: 60px;
        border-radius: 10px;
        background-color: #f0f0f0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: var(--primary);
        margin-right: 15px;
    }

    .drive-details {
        flex: 1;
    }

    .drive-title {
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 5px;
    }

    .drive-meta {
        color: #6c757d;
        font-size: 0.9rem;
    }

    .drive-actions {
        display: flex;
        gap: 10px;
    }

    .empty-state {
        display: none;
        text-align: center;
        padding: 40px 20px;
    }

    .empty-state i {
        font-size: 3rem;
        margin-bottom: 1rem;
    }

    @media (max-width: 768px) {
        .drive-actions {
            flex-direction: column;
        }

        .drive-actions .btn {
            margin-bottom: 5px;
        }
    }
</style>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<div class="container-fluid">
    <div class="row">
        <div
            class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
            <h1 class="h2">Placement Drives</h1>
            <div class="btn-toolbar mb-2 mb-md-0">
                <a href="{{ route('admin.placement.drives.create') }}" class="btn btn-sm btn-primary">
                    <i class="fas fa-plus me-2"></i> Create New Drive
                </a>
            </div>
        </div>

        <!-- Filters -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="upcoming">Upcoming</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Drive Type</label>
                        <select class="form-select" id="typeFilter">
                            <option value="">All Types</option>
                            <option value="on_campus">On Campus</option>
                            <option value="off_campus">Off Campus</option>
                            <option value="virtual">Virtual</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Company</label>
                        <input type="text" class="form-control" id="companyFilter" placeholder="Search company...">
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button class="btn btn-outline-secondary w-100" id="resetFilters">Reset Filters</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Drives List -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>All Placement Drives</span>
                <div class="d-flex">
                    <div class="input-group input-group-sm me-2" style="width: 200px;">
                        <input type="text" class="form-control" id="searchInput" placeholder="Search drives...">
                        <button class="btn btn-outline-secondary" type="button" id="searchButton">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                    <button class="btn btn-sm btn-outline-secondary" id="sortButton">
                        <i class="fas fa-filter"></i> Sort
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Empty state for no filter results -->
                <div class="empty-state" id="emptyState">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No placement drives found matching your criteria.</p>
                    <button class="btn btn-outline-primary" id="clearFiltersBtn">
                        <i class="fas fa-times me-2"></i> Clear Filters
                    </button>
                </div>

                <!-- Default empty state when no drives exist in database -->
                @if(count($placementDrives) === 0)
                <div class="text-center py-4" id="defaultEmptyState">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No placement drives found.</p>
                    <a href="{{ route('admin.placement.drives.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i> Create Your First Drive
                    </a>
                </div>
                @else
                <!-- Drive Items -->
                @foreach($placementDrives as $drive)
                <div class="drive-card card mb-3" data-status="{{ $drive->status }}"
                    data-type="{{ $drive->drive_type }}" data-company="{{ strtolower($drive->company_name) }}">
                    <div class="card-body">
                        <div class="d-flex align-items-start">
                            <div class="company-logo">
                                <i class="fas fa-building"></i>
                            </div>
                            <div class="drive-details">
                                <div class="drive-title">{{ $drive->company_name }} - {{ $drive->job_role }}</div>
                                <div class="drive-meta mb-2">
                                    <span class="me-3"><i class="fas fa-calendar-alt me-1"></i> Drive Date:
                                        {{ $drive->drive_date }}</span>
                                    <span class="me-3"><i class="fas fa-map-marker-alt me-1"></i> Location:
                                        {{ $drive->location }}</span>
                                    <span><i class="fas fa-user me-1"></i> Coordinator:
                                        {{ $drive->drive_coordinator }}</span>
                                </div>
                                <div class="d-flex flex-wrap">
                                    <span
                                        class="status-badge status-{{ $drive->status }} me-2">{{ ucfirst($drive->status) }}</span>
                                    <span
                                        class="drive-type-badge me-2">{{ str_replace('_', ' ', ucfirst($drive->drive_type)) }}</span>
                                    <span class="me-2"><i class="fas fa-money-bill-wave me-1"></i> Package:
                                        {{ $drive->package_offered }}</span>
                                    <span><i class="fas fa-graduation-cap me-1"></i> Min CGPA:
                                        {{ $drive->eligibility_cgpa }}</span>
                                </div>
                            </div>
                            <div class="drive-actions">
                                 <a href="{{ route('admin.placement.drives.view', ['drive_id' => $drive->id]) }}"
                                    class="btn btn-sm btn-outline-primary action-btn">
                                    <i class="fas fa-eye"></i> View
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get DOM elements
        const statusFilter = document.getElementById('statusFilter');
        const typeFilter = document.getElementById('typeFilter');
        const companyFilter = document.getElementById('companyFilter');
        const resetFilters = document.getElementById('resetFilters');
        const searchInput = document.getElementById('searchInput');
        const searchButton = document.getElementById('searchButton');
        const emptyState = document.getElementById('emptyState');
        const clearFiltersBtn = document.getElementById('clearFiltersBtn');
        const defaultEmptyState = document.getElementById('defaultEmptyState');
        const driveCards = document.querySelectorAll('.drive-card');

        // If there are no drives in database, don't initialize filtering
        if (driveCards.length === 0) {
            return;
        }

        // Function to filter drives
        function filterDrives() {
            const statusValue = statusFilter.value;
            const typeValue = typeFilter.value;
            const companyValue = companyFilter.value.toLowerCase().trim();
            const searchValue = searchInput.value.toLowerCase().trim();

            let visibleCount = 0;

            driveCards.forEach(card => {
                const cardStatus = card.getAttribute('data-status');
                const cardType = card.getAttribute('data-type');
                const cardCompany = card.getAttribute('data-company');
                const cardText = card.textContent.toLowerCase();

                // Check status filter
                const statusMatch = !statusValue || cardStatus === statusValue;

                // Check type filter
                const typeMatch = !typeValue || cardType === typeValue;

                // Check company filter
                const companyMatch = !companyValue || cardCompany.includes(companyValue);

                // Check search filter
                const searchMatch = !searchValue || cardText.includes(searchValue);

                // Show card if all active filters match
                if (statusMatch && typeMatch && companyMatch && searchMatch) {
                    card.style.display = 'block';
                    visibleCount++;
                } else {
                    card.style.display = 'none';
                }
            });

            // Handle empty states
            if (defaultEmptyState) {
                defaultEmptyState.style.display = 'none';
            }

            if (visibleCount === 0) {
                emptyState.style.display = 'block';
            } else {
                emptyState.style.display = 'none';
            }
        }

        // Function to reset all filters
        function resetAllFilters() {
            statusFilter.value = '';
            typeFilter.value = '';
            companyFilter.value = '';
            searchInput.value = '';
            filterDrives();
        }

        // Function to handle search
        function handleSearch() {
            filterDrives();
        }

        // Event listeners
        statusFilter.addEventListener('change', filterDrives);
        typeFilter.addEventListener('change', filterDrives);
        companyFilter.addEventListener('input', filterDrives);
        resetFilters.addEventListener('click', resetAllFilters);
        searchInput.addEventListener('input', handleSearch);
        searchButton.addEventListener('click', handleSearch);
        clearFiltersBtn.addEventListener('click', resetAllFilters);

        // Add Enter key support for search
        searchInput.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                handleSearch();
            }
        });

        // Initial filter to handle any pre-filled values
        filterDrives();
    });
</script>

@endsection