@extends('dashboardLayouts.base')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">All Placement Drives</h1>
                <p class="mb-0">View and manage all your scheduled placement drives</p>
            </div>

        </div>

        <!-- Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center pl-3 pr-3">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Drives
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $getAllDrives->count() }}</div>
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
                        <div class="row no-gutters align-items-center pl-3 pr-3">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                    Upcoming Drives
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $getAllDrives->where('drive_date', '>=', now())->count() }}
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
                        <div class="row no-gutters align-items-center pl-3 pr-3">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                    Total Applications
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    @php
                                        $totalApplications = 0;
                                        foreach ($getAllDrives as $drive) {
                                            $totalApplications += DB::table('placement_applications')
                                                ->where('placement_drive_id', $drive->id)
                                                ->count();
                                        }
                                    @endphp
                                    {{ $totalApplications }}
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center pl-3 pr-3 ">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Completed Drives
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    {{ $getAllDrives->where('status', 'completed')->count() }}
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

        <!-- Drives Table -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">All Drives List</h6>
            </div>
            <div class="card-body">
                @if ($getAllDrives->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-calendar-times fa-3x text-gray-300 mb-3"></i>
                        <h5 class="text-gray-500">No drives scheduled yet</h5>
                        <p class="text-gray-500">Schedule your first placement drive to get started</p>
                      
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Company Name</th>
                                    <th>Job Title</th>
                                    <th>Drive Date</th>
                                    <th>Location</th>
                                    <th>Drive Type</th>
                                    <th>Applications</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($getAllDrives as $index => $drive)
                                    @php
                                        // Format drive type
                                        $driveTypeMap = [
                                            'on_campus' => 'On Campus',
                                            'off_campus' => 'Off Campus',
                                            'virtual' => 'Virtual',
                                        ];
                                        $driveType = $driveTypeMap[$drive->drive_type] ?? $drive->drive_type;

                                        // Get application count
                                        $applicationCount = DB::table('placement_applications')
                                            ->where('placement_drive_id', $drive->id)
                                            ->count();

                                        // Determine status display
                                        $statusClass = '';
                                        $statusText = '';

                                        if ($drive->status) {
                                            $statusText = ucfirst($drive->status);
                                            switch ($drive->status) {
                                                case 'active':
                                                case 'upcoming':
                                                    $statusClass = 'success';
                                                    break;
                                                case 'completed':
                                                    $statusClass = 'warning';
                                                    break;
                                                case 'cancelled':
                                                    $statusClass = 'danger';
                                                    break;
                                                default:
                                                    $statusClass = 'secondary';
                                            }
                                        } else {
                                            $statusText = 'Pending';
                                            $statusClass = 'secondary';
                                        }
                                    @endphp
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <strong>{{ $drive->company_name ?? 'N/A' }}</strong>
                                            @if ($drive->description)
                                                <br><small
                                                    class="text-muted">{{ Str::limit($drive->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $drive->job_title ?? 'N/A' }}</td>
                                        <td>
                                            @if ($drive->drive_date)
                                                {{ \Carbon\Carbon::parse($drive->drive_date)->format('d M, Y') }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>{{ $drive->location ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge badge-info">{{ $driveType }}</span>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge badge-info badge-pill">
                                                {{ $applicationCount }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge badge-{{ $statusClass }}">
                                                {{ $statusText }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('company.detailedAllViewDrive', $drive->id) }}"
                                                    class="btn btn-sm btn-info" title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </a>


                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        <!-- Additional Sections -->
        <div class="row">
            <!-- Upcoming Drives Card -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-success">Upcoming Drives</h6>
                    </div>
                    <div class="card-body">
                        @php
                            $upcomingDrives = $getAllDrives
                                ->filter(function ($drive) {
                                    $driveDate = $drive->drive_date ? \Carbon\Carbon::parse($drive->drive_date) : null;
                                    return $driveDate && $driveDate->isFuture();
                                })
                                ->take(5);
                        @endphp

                        @if ($upcomingDrives->isEmpty())
                            <p class="text-muted">No upcoming drives</p>
                        @else
                            <div class="list-group">
                                @foreach ($upcomingDrives as $drive)
                                    <a href="{{ route('company.detailedAllViewDrive', $drive->id) }}"
                                        class="list-group-item list-group-item-action">
                                        <div class="d-flex w-100 justify-content-between">
                                            <h6 class="mb-1">{{ $drive->company_name ?? 'Unnamed Drive' }}</h6>
                                            <small>{{ \Carbon\Carbon::parse($drive->drive_date)->diffForHumans() }}</small>
                                        </div>
                                        <p class="mb-1">{{ $drive->job_title ?? 'No job title' }}</p>
                                        <small class="text-muted">
                                            <i class="fas fa-map-marker-alt"></i>
                                            {{ $drive->location ?? 'Location not set' }}
                                        </small>
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Recent Applications Card -->
            <div class="col-lg-6 mb-4">
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Drive Statistics</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="driveChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <script>
        // Initialize DataTable
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "pageLength": 10,
                "order": [
                    [3, 'desc']
                ], // Sort by date descending
                "columnDefs": [{
                        "orderable": false,
                        "targets": [8]
                    } // Disable sorting for Actions column
                ]
            });
        });

        // Drive Chart
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('driveChart').getContext('2d');

            @php
                $monthlyData = [];
                foreach ($getAllDrives as $drive) {
                    if ($drive->drive_date) {
                        $month = \Carbon\Carbon::parse($drive->drive_date)->format('M Y');
                        if (!isset($monthlyData[$month])) {
                            $monthlyData[$month] = 0;
                        }
                        $monthlyData[$month]++;
                    }
                }
                krsort($monthlyData); // Sort by latest month first
                $months = array_keys($monthlyData);
                $counts = array_values($monthlyData);
            @endphp

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: @json($months),
                    datasets: [{
                        label: 'Drives per Month',
                        data: @json($counts),
                        backgroundColor: 'rgba(54, 162, 235, 0.5)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        }
                    }
                }
            });
        });
    </script>

    <style>
        .card {
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .table th {
            background-color: #f8f9fc;
            font-weight: 600;
        }

        .badge-pill {
            padding: 5px 10px;
            font-size: 12px;
        }

        .btn-group .btn {
            margin-right: 5px;
            border-radius: 4px;
        }

        .btn-group .btn:last-child {
            margin-right: 0;
        }

        .list-group-item:hover {
            background-color: #f8f9fa;
            transform: translateY(-2px);
            transition: all 0.3s ease;
        }

        .list-group-item {
            border-left: 4px solid transparent;
            margin-bottom: 5px;
        }

        .list-group-item:hover {
            border-left-color: #4e73df;
        }

        #driveChart {
            width: 100% !important;
        }
    </style>
@endsection