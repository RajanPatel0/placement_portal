@extends('dashboardLayouts.base')

@section('title', 'All Placement Drives')

@section('content')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">




    <div class="container-fluid">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="h4 mb-1">All Placement Drives</h2>
                <p class="text-muted mb-0">Manage and view all placement drives for your college</p>
            </div>
            <div class="d-flex gap-2">
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-filter me-2"></i>Filter
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="filterDropdown">
                        <li><a class="dropdown-item filter-link" href="#" data-status="all">All Drives</a></li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li><a class="dropdown-item filter-link" href="#" data-status="upcoming">Upcoming</a></li>
                        <li><a class="dropdown-item filter-link" href="#" data-status="ongoing">Ongoing</a></li>
                        <li><a class="dropdown-item filter-link" href="#" data-status="completed">Completed</a></li>
                        <li><a class="dropdown-item filter-link" href="#" data-status="cancelled">Cancelled</a></li>
                    </ul>
                </div>
                <div class="input-group" style="width: 300px;">
                    <input type="text" class="form-control" placeholder="Search drives..." id="searchInput">
                    <button class="btn btn-outline-secondary" type="button" id="searchButton">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Drives</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalDrives">
                                    {{ count($placementDrives) }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Upcoming Drives</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="upcomingDrives">
                                    {{ $placementDrives->where('status', 'upcoming')->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-clock fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Ongoing Drives</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="ongoingDrives">
                                    {{ $placementDrives->where('status', 'ongoing')->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-play-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Completed Drives</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800" id="completedDrives">
                                    {{ $placementDrives->where('status', 'completed')->count() }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Info -->
        <div id="filterInfo" class="alert alert-info alert-dismissible fade show mb-4" style="display: none;">
            <i class="fas fa-info-circle me-2"></i>
            <span id="filterMessage">Showing filtered results</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

        <!-- Drives Table -->
        <div class="card shadow pb-4">
            <div class="card-body">
                @if ($placementDrives->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover" id="drivesTable">
                            <thead>
                                <tr>
                                    <th>Company</th>
                                    <th>Job Title</th>
                                    <th>Drive Date</th>
                                    <th>Status</th>
                                    <th>Type</th>
                                    <th>Application</th>
                                    <th>Package</th>
                                    <th>Vacancies</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="drivesTableBody">
                                @foreach ($placementDrives as $drive)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center"
                                                        style="width: 40px; height: 40px;">
                                                        <i class="fas fa-building"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <h6 class="mb-0">{{ $drive->company_name }}</h6>
                                                    <small class="text-muted">
                                                        <i class="fas fa-calendar me-1"></i>
                                                        {{ date('d M Y', strtotime($drive->drive_date)) }}
                                                    </small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>{{ $drive->job_title }}</strong>
                                            @if ($drive->job_role)
                                                <br>
                                                <small class="text-muted">{{ $drive->job_role }}</small>
                                            @endif
                                        </td>
                                        <td>
                                            {{ date('d M Y', strtotime($drive->drive_date)) }}
                                            @if ($drive->application_deadline)
                                                <br>
                                                <small class="text-danger">
                                                    <i class="fas fa-clock me-1"></i>
                                                    Deadline: {{ date('d M Y', strtotime($drive->application_deadline)) }}
                                                </small>
                                            @endif
                                        </td>
                                        <td>
                                            <span
                                                class="status-badge badge 
                                                @if ($drive->status == 'upcoming') bg-info
                                                @elseif($drive->status == 'ongoing') bg-success
                                                @elseif($drive->status == 'completed') bg-secondary
                                                @elseif($drive->status == 'cancelled') bg-danger @endif"
                                                data-status="{{ $drive->status }}">
                                                {{ ucfirst($drive->status) }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-light text-dark">
                                                @if ($drive->drive_type == 'on_campus')
                                                    <i class="fas fa-university me-1"></i> On Campus
                                                @elseif($drive->drive_type == 'off_campus')
                                                    <i class="fas fa-map-marker-alt me-1"></i> Off Campus
                                                @elseif($drive->drive_type == 'virtual')
                                                    <i class="fas fa-video me-1"></i> Virtual
                                                @endif
                                            </span>
                                        </td>
                                        <td>
                                            @if ($drive->application_count > 0)
                                                <div class="d-flex align-items-center">
                                                    <span class="badge bg-info bg-opacity-10 text-info">
                                                        <i class="fas fa-file-alt me-1"></i>
                                                        {{ $drive->application_count }}
                                                    </span>

                                                </div>
                                            @else
                                                <span class="badge bg-light text-muted">No applications</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($drive->package_offered)
                                                <span class="badge bg-success bg-opacity-10 text-success">
                                                    <i class="fas fa-rupee-sign me-1"></i>
                                                    {{ $drive->package_offered }} LPA
                                                </span>
                                            @else
                                                <span class="text-muted">Not specified</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($drive->vacancies)
                                                <span class="badge bg-primary bg-opacity-10 text-primary">
                                                    <i class="fas fa-users me-1"></i>
                                                    {{ $drive->vacancies }}
                                                </span>
                                            @else
                                                <span class="text-muted">Not specified</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="" class="btn btn-sm btn-outline-primary"
                                                    title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>

                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Results Info -->
                    <div id="resultsInfo" class="mt-3 text-center">
                        <p class="text-muted mb-0" id="resultsText">Showing all {{ count($placementDrives) }} drives</p>
                    </div>
                @else
                    <div class="text-center py-5" id="noDrivesMessage">
                        <div class="mb-4">
                            <i class="fas fa-calendar-times fa-4x text-muted"></i>
                        </div>
                        <h4 class="text-muted mb-3">No Placement Drives Found</h4>
                        <p class="text-muted mb-4">There are no placement drives scheduled for your college yet.</p>
                        <a href="{{ route('placement.officer.dashboard') }}" class="btn btn-primary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <style>
        .table td {
            vertical-align: middle;
        }

        .badge {
            font-size: 0.8em;
            padding: 0.35em 0.65em;
        }

        .card {
            border: none;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.05);
        }

        .border-left-primary {
            border-left: 4px solid #4e73df;
        }

        .border-left-success {
            border-left: 4px solid #1cc88a;
        }

        .border-left-info {
            border-left: 4px solid #36b9cc;
        }

        .border-left-warning {
            border-left: 4px solid #f6c23e;
        }

        .btn-group .btn {
            border-radius: 4px;
            margin-right: 2px;
        }

        .btn-outline-primary:hover {
            background-color: #4e73df;
            color: white;
        }

        .btn-outline-info:hover {
            background-color: #36b9cc;
            color: white;
        }

        .btn-outline-success:hover {
            background-color: #1cc88a;
            color: white;
        }

        #filterInfo {
            transition: all 0.3s ease;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get DOM elements
            const filterLinks = document.querySelectorAll('.filter-link');
            const filterDropdown = document.getElementById('filterDropdown');
            const filterInfo = document.getElementById('filterInfo');
            const filterMessage = document.getElementById('filterMessage');
            const searchInput = document.getElementById('searchInput');
            const searchButton = document.getElementById('searchButton');
            const drivesTableBody = document.getElementById('drivesTableBody');
            const resultsInfo = document.getElementById('resultsInfo');
            const resultsText = document.getElementById('resultsText');

            // Get stat card elements
            const totalDrivesElement = document.getElementById('totalDrives');
            const upcomingDrivesElement = document.getElementById('upcomingDrives');
            const ongoingDrivesElement = document.getElementById('ongoingDrives');
            const completedDrivesElement = document.getElementById('completedDrives');

            // Store current filter state
            let currentFilter = 'all';
            let currentSearch = '';

            // Initialize
            updateResultsText();

            // Filter functionality
            filterLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const status = this.getAttribute('data-status');
                    applyFilter(status);

                    // Update dropdown button text
                    const filterText = status === 'all' ? 'Filter' : status.charAt(0)
                        .toUpperCase() + status.slice(1);
                    filterDropdown.innerHTML = `<i class="fas fa-filter me-2"></i>${filterText}`;

                    // Close dropdown
                    const dropdown = new bootstrap.Dropdown(filterDropdown);
                    dropdown.hide();
                });
            });

            // Search functionality
            if (searchInput && searchButton) {
                searchButton.addEventListener('click', performSearch);
                searchInput.addEventListener('keyup', function(e) {
                    if (e.key === 'Enter') {
                        performSearch();
                    } else {
                        performSearch();
                    }
                });
            }

            function applyFilter(status) {
                currentFilter = status;
                updateTable();
                showFilterInfo(status);
            }

            function performSearch() {
                currentSearch = searchInput.value.trim().toLowerCase();
                updateTable();
            }

            function updateTable() {
                if (!drivesTableBody) return;

                const rows = drivesTableBody.getElementsByTagName('tr');
                let visibleCount = 0;
                let upcomingCount = 0;
                let ongoingCount = 0;
                let completedCount = 0;
                let cancelledCount = 0;
                let hasVisibleRows = false;

                for (let row of rows) {
                    // Get status from data attribute
                    const statusBadge = row.querySelector('.status-badge');
                    const status = statusBadge ? statusBadge.getAttribute('data-status') : '';

                    // Apply filter
                    let shouldShowByFilter = currentFilter === 'all' || status === currentFilter;

                    // Apply search
                    let shouldShowBySearch = true;
                    if (currentSearch) {
                        const rowText = row.textContent.toLowerCase();
                        shouldShowBySearch = rowText.includes(currentSearch);
                    }

                    // Combine both filters
                    const shouldShow = shouldShowByFilter && shouldShowBySearch;
                    row.style.display = shouldShow ? '' : 'none';

                    if (shouldShow) {
                        hasVisibleRows = true;
                        visibleCount++;

                        // Count by status
                        switch (status) {
                            case 'upcoming':
                                upcomingCount++;
                                break;
                            case 'ongoing':
                                ongoingCount++;
                                break;
                            case 'completed':
                                completedCount++;
                                break;
                            case 'cancelled':
                                cancelledCount++;
                                break;
                        }
                    }
                }

                // Update statistics
                updateStatistics(visibleCount, upcomingCount, ongoingCount, completedCount, cancelledCount);

                // Update results text
                updateResultsText(visibleCount);

                // Show/hide empty state
                showEmptyState(!hasVisibleRows);
            }

            function updateStatistics(total, upcoming, ongoing, completed, cancelled) {
                totalDrivesElement.textContent = total;
                upcomingDrivesElement.textContent = upcoming;
                ongoingDrivesElement.textContent = ongoing;
                completedDrivesElement.textContent = completed;
            }

            function updateResultsText(visibleCount) {
                if (!resultsText) return;

                const totalRows = drivesTableBody ? drivesTableBody.getElementsByTagName('tr').length : 0;

                let text = '';
                if (currentFilter === 'all' && !currentSearch) {
                    text = `Showing all ${totalRows} drives`;
                } else if (currentFilter !== 'all' && !currentSearch) {
                    text = `Showing ${visibleCount} ${currentFilter} drive${visibleCount !== 1 ? 's' : ''}`;
                } else if (currentFilter === 'all' && currentSearch) {
                    text =
                        `Found ${visibleCount} drive${visibleCount !== 1 ? 's' : ''} matching "${currentSearch}"`;
                } else {
                    text =
                        `Found ${visibleCount} ${currentFilter} drive${visibleCount !== 1 ? 's' : ''} matching "${currentSearch}"`;
                }

                resultsText.textContent = text;
            }

            function showFilterInfo(status) {
                if (status === 'all') {
                    filterInfo.style.display = 'none';
                } else {
                    const statusText = status.charAt(0).toUpperCase() + status.slice(1);
                    filterMessage.textContent = `Showing ${statusText} drives only`;
                    filterInfo.style.display = 'block';
                }
            }

            function showEmptyState(show) {
                const noDrivesMessage = document.getElementById('noDrivesMessage');
                const table = document.querySelector('.table-responsive');

                if (!noDrivesMessage) return;

                if (show) {
                    noDrivesMessage.style.display = 'block';
                    if (table) table.style.display = 'none';
                    if (resultsInfo) resultsInfo.style.display = 'none';

                    // Update empty state message
                    const emptyTitle = noDrivesMessage.querySelector('h4');
                    const emptyText = noDrivesMessage.querySelector('p');

                    if (currentFilter !== 'all' || currentSearch) {
                        let filterText = '';
                        if (currentFilter !== 'all' && currentSearch) {
                            filterText = `No ${currentFilter} drives found matching "${currentSearch}"`;
                        } else if (currentFilter !== 'all') {
                            filterText = `No ${currentFilter} drives found`;
                        } else {
                            filterText = `No drives found matching "${currentSearch}"`;
                        }

                        emptyTitle.textContent = 'No Results Found';
                        emptyText.textContent = filterText;
                    } else {
                        emptyTitle.textContent = 'No Placement Drives Found';
                        emptyText.textContent = 'There are no placement drives scheduled for your college yet.';
                    }
                } else {
                    noDrivesMessage.style.display = 'none';
                    if (table) table.style.display = '';
                    if (resultsInfo) resultsInfo.style.display = 'block';
                }
            }

            // Add clear search button
            function addClearSearchButton() {
                if (!searchInput) return;

                const clearButton = document.createElement('button');
                clearButton.className = 'btn btn-outline-danger ms-2';
                clearButton.innerHTML = '<i class="fas fa-times"></i>';
                clearButton.title = 'Clear search';
                clearButton.addEventListener('click', function() {
                    searchInput.value = '';
                    currentSearch = '';
                    updateTable();
                    searchInput.focus();
                });

                const searchContainer = searchInput.parentElement;
                searchContainer.appendChild(clearButton);
            }

            // Add clear filter button
            function addClearFilterButton() {
                const clearFilterButton = document.createElement('button');
                clearFilterButton.className = 'btn btn-outline-warning ms-2';
                clearFilterButton.innerHTML = '<i class="fas fa-filter-circle-xmark"></i>';
                clearFilterButton.title = 'Clear filter';
                clearFilterButton.addEventListener('click', function() {
                    applyFilter('all');
                    filterDropdown.innerHTML = '<i class="fas fa-filter me-2"></i>Filter';
                });

                const filterContainer = filterDropdown.parentElement;
                filterContainer.appendChild(clearFilterButton);
            }

            // Initialize buttons
            addClearSearchButton();
            addClearFilterButton();
        });
    </script>
@endsection
