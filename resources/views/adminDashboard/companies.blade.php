@extends('dashboardLayouts.base')

@section('title', 'Placement Companies')

@section('content')
<div class="container-fluid pt-4">

    <!-- Quick Stats Cards -->
    <div class="row mb-3">
        <div class="col-md-3 col-sm-6">
            <div class="info-box">
                <span class="info-box-icon bg-info"><i class="fas fa-building"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Companies</span>
                    <span class="info-box-number">{{ $companies->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="info-box">
                <span class="info-box-icon bg-success"><i class="fas fa-play-circle"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Ongoing Drives</span>
                    <span class="info-box-number">{{ $companies->where('status', 'ongoing')->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="info-box">
                <span class="info-box-icon bg-warning"><i class="fas fa-clock"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Upcoming Drives</span>
                    <span class="info-box-number">{{ $companies->where('status', 'upcoming')->count() }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6">
            <div class="info-box">
                <span class="info-box-icon bg-primary"><i class="fas fa-users"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Total Vacancies</span>
                    <span class="info-box-number">{{ $companies->sum('vacancies') }}</span>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Placement Companies</h3>
                    <div class="card-tools">
                        <span class="badge badge-primary">{{ $companies->count() }} Companies</span>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Company Name</th>
                                    <th>Job Role</th>
                                    <th>Drive Date</th>
                                    <th>Deadline</th>
                                    <th>Package (LPA)</th>
                                    <th>Vacancies</th>
                                    <th>Location</th>
                                    <th>Status</th>
                                    <th>Drive Type</th>
                                    <th>Applications</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($companies as $index => $company)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <strong>{{ $company->company_name }}</strong>
                                    </td>
                                    <td>{{ $company->job_role ?? 'Not Specified' }}</td>
                                    <td>
                                        <span class="badge badge-warning">
                                            {{ \Carbon\Carbon::parse($company->drive_date)->format('d M Y') }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($company->application_deadline)
                                        <span
                                            class="badge badge-{{ \Carbon\Carbon::parse($company->application_deadline)->isPast() ? 'danger' : 'warning' }}">
                                            {{ \Carbon\Carbon::parse($company->application_deadline)->format('d M Y') }}
                                        </span>
                                        @else
                                        <span class="text-muted">Not Specified</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($company->package_offered)
                                        <span class="badge badge-success">₹{{ $company->package_offered }} LPA</span>
                                        @else
                                        <span class="text-muted">Not Disclosed</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($company->vacancies)
                                        <span class="badge badge-info">{{ $company->vacancies }}</span>
                                        @else
                                        <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>{{ $company->location ?? 'Not Specified' }}</td>
                                    <td>
                                        @php
                                        $statusColors = [
                                        'upcoming' => 'info',
                                        'ongoing' => 'success',
                                        'completed' => 'secondary',
                                        'cancelled' => 'danger'
                                        ];
                                        $statusIcons = [
                                        'upcoming' => 'fas fa-clock',
                                        'ongoing' => 'fas fa-play-circle',
                                        'completed' => 'fas fa-check-circle',
                                        'cancelled' => 'fas fa-times-circle'
                                        ];
                                        @endphp
                                        <span class="badge badge-{{ $statusColors[$company->status] ?? 'secondary' }}">
                                            <i class="{{ $statusIcons[$company->status] ?? 'fas fa-circle' }}"></i>
                                            {{ ucfirst($company->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @php
                                        $typeColors = [
                                        'on_campus' => 'primary',
                                        'off_campus' => 'warning',
                                        'virtual' => 'info'
                                        ];
                                        @endphp
                                        <span
                                            class="badge badge-{{ $typeColors[$company->drive_type] ?? 'secondary' }}">
                                            {{ str_replace('_', ' ', ucfirst($company->drive_type)) }}
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        @php
                                        $applicationCount = DB::table('placement_applications')
                                        ->where('placement_drive_id', $company->id)
                                        ->count();
                                        @endphp
                                        <span class="badge badge-dark">{{ $applicationCount }}</span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('admin.placement.applications.company', $company->id) }}"
                                                class="btn btn-info" title="View Applications">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <!-- <a href="" class="btn btn-warning" title="Edit Company">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="" class="btn btn-danger" title="Delete Company">
                                                <i class="fas fa-trash"></i>
                                            </a> -->
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="12" class="text-center py-4">
                                        <i class="fas fa-building fa-3x text-muted mb-3"></i>
                                        <h4 class="text-muted">No Companies Found</h4>
                                        <p class="text-muted">No placement drives have been created yet.</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Font Awesome Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    .table th {
        background-color: #f8f9fa;
        font-weight: 600;
        border-bottom: 2px solid #dee2e6;
    }

    .info-box {
        box-shadow: 0 0 1px rgba(0, 0, 0, .125), 0 1px 3px rgba(0, 0, 0, .2);
        border-radius: .25rem;
        background: #fff;
        display: flex;
        margin-bottom: 1rem;
        min-height: 80px;
        padding: .5rem;
        position: relative;
    }

    .info-box-icon {
        border-top-left-radius: .25rem;
        border-bottom-left-radius: .25rem;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 70px;
        font-size: 1.875rem;
        text-align: center;
        color: white;
    }

    .info-box-content {
        flex: 1;
        padding: 5px 10px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .info-box-text {
        display: block;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        text-transform: uppercase;
        font-size: .875rem;
        color: #6c757d;
    }

    .info-box-number {
        display: block;
        font-weight: 700;
        font-size: 1.5rem;
        color: #495057;
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .badge {
        font-size: 0.75em;
        padding: 0.4em 0.6em;
    }

    .btn-group-sm>.btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    .thead-dark {
        background-color: #343a40;
        color: white;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.075);
    }
</style>
@endsection