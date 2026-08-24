@extends('dashboardLayouts.base')

@section('content')
    <div class="container-fluid py-4">
        <!-- Page Header -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-0 text-gray-800">Drive Details</h1>
                <p class="mb-0">View complete information about the placement drive</p>
            </div>
            <div>
                <a href="{{ route('company.all.drives') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Back to All Drives
                </a>
            </div>
        </div>

        <!-- Drive Stats Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center pl-3 pr-3">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                    Total Applications
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $applicationCount }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-users fa-2x text-gray-300"></i>
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
                                    Shortlisted
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $shortlistedCount }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-check-circle fa-2x text-gray-300"></i>
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
                                    Selected
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $selectedCount }}</div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-trophy fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center  pl-3 pr-3">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                    Status
                                </div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">
                                    <span class="badge badge-{{ $statusClass }}">{{ $status }}</span>
                                </div>
                            </div>
                            <div class="col-auto">
                                <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Drive Details -->
        <div class="row">
            <!-- Left Column - Basic Information -->
            <div class="col-lg-8 mb-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Drive Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Company Name:</strong><br>{{ $drive->company_name }}</p>
                                <p><strong>Job Title:</strong><br>{{ $drive->job_title }}</p>
                                <p><strong>Job Role:</strong><br>{{ $drive->job_role ?? 'N/A' }}</p>
                                <p><strong>Drive Type:</strong><br>{{ $driveType }}</p>
                                <p><strong>Status:</strong><br>
                                    <span class="badge badge-{{ $statusClass }}">{{ $status }}</span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Drive Date:</strong><br>
                                    @if ($drive->drive_date)
                                        {{ \Carbon\Carbon::parse($drive->drive_date)->format('d M, Y') }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                                <p><strong>Application Deadline:</strong><br>
                                    @if ($drive->application_deadline)
                                        {{ \Carbon\Carbon::parse($drive->application_deadline)->format('d M, Y') }}
                                    @else
                                        N/A
                                    @endif
                                </p>
                                <p><strong>Location:</strong><br>{{ $drive->location ?? 'N/A' }}</p>
                                <p><strong>Package Offered:</strong><br>
                                    @if ($drive->package_offered)
                                        ₹{{ number_format($drive->package_offered) }} LPA
                                    @else
                                        N/A
                                    @endif
                                </p>
                                <p><strong>Vacancies:</strong><br>{{ $drive->vacancies ?? 'N/A' }}</p>
                            </div>
                        </div>

                        @if ($drive->description)
                            <div class="mt-4">
                                <strong>Description:</strong>
                                <p class="mt-2">{{ $drive->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Eligibility Criteria -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Eligibility Criteria</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Eligible Courses:</strong><br>
                                    @if (!empty($courseNames))
                                        {{ implode(', ', $courseNames) }}
                                    @else
                                        All Courses
                                    @endif
                                </p>
                                <p><strong>Eligible Departments:</strong><br>
                                    @if (!empty($departmentNames))
                                        {{ implode(', ', $departmentNames) }}
                                    @else
                                        All Departments
                                    @endif
                                </p>
                                <p><strong>Eligible Campuses:</strong><br>
                                    @if (!empty($campusNames))
                                        {{ implode(', ', $campusNames) }}
                                    @else
                                        All Campuses
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Minimum CGPA:</strong><br>{{ $drive->eligibility_cgpa ?? 'N/A' }}</p>
                                <p><strong>Passing Years:</strong><br>
                                    @if (!empty($eligibilityPassingYears))
                                        {{ implode(', ', $eligibilityPassingYears) }}
                                    @else
                                        All Years
                                    @endif
                                </p>
                                <p><strong>Re-appear Students Allowed:</strong><br>
                                    {{ $drive->is_reappear ? 'Yes' : 'No' }}
                                </p>
                                @if ($drive->tenth_percentage)
                                    <p><strong>10th Percentage:</strong><br>{{ $drive->tenth_percentage }}%</p>
                                @endif
                                @if ($drive->twelfth_percentage)
                                    <p><strong>12th Percentage:</strong><br>{{ $drive->twelfth_percentage }}%</p>
                                @endif
                                @if ($drive->graduation_percentage)
                                    <p><strong>Graduation Percentage:</strong><br>{{ $drive->graduation_percentage }}%</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column - Additional Information -->
            <div class="col-lg-4 mb-4">
                <!-- Contact Information -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Contact Information</h6>
                    </div>
                    <div class="card-body">
                        <p><strong>Contact Person:</strong><br>{{ $drive->contact_person ?? 'N/A' }}</p>
                        <p><strong>Contact Email:</strong><br>{{ $drive->contact_email ?? 'N/A' }}</p>
                        <p><strong>Contact Phone:</strong><br>{{ $drive->contact_phone ?? 'N/A' }}</p>
                        <p><strong>Drive Coordinator:</strong><br>{{ $drive->drive_coordinator ?? 'N/A' }}</p>
                        @if ($drive->company_website)
                            <p><strong>Company Website:</strong><br>
                                <a href="{{ $drive->company_website }}" target="_blank">{{ $drive->company_website }}</a>
                            </p>
                        @endif
                    </div>
                </div>

                <!-- Required Skills -->
                @if (!empty($requiredSkills))
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 font-weight-bold text-primary">Required Skills</h6>
                        </div>
                        <div class="card-body">
                            <div class="d-flex flex-wrap gap-2">
                                @foreach ($requiredSkills as $skill)
                                    <span class="badge badge-info">{{ $skill }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Drive Info -->
                <div class="card shadow">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Drive Information</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Created By:</strong>
                            <p>{{ $creatorName ?? 'System' }}</p>
                        </div>
                        <div class="mb-3">
                            <strong>Created At:</strong>
                            <p>{{ \Carbon\Carbon::parse($drive->created_at)->format('d M, Y h:i A') }}</p>
                        </div>
                        <div>
                            <strong>Last Updated:</strong>
                            <p>{{ \Carbon\Carbon::parse($drive->updated_at)->format('d M, Y h:i A') }}</p>
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle"></i>
                            <strong>Note:</strong> This is a read-only view of the drive.
                            To manage applications, please go to the latest drive dashboard.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
