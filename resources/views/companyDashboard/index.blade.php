@extends('dashboardLayouts.base')

@section('title', $company->company_name . ' - College Approved Applications')

@section('content')
    <div class="container-fluid pt-4">

        <!-- Notification Section -->
        <div class="notification-container">
            @if (session('success'))
                <div class="notification success" id="successNotification">
                    <button type="button" class="close-btn"
                        onclick="closeNotification('successNotification')">&times;</button>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="notification error" id="errorNotification">
                    <button type="button" class="close-btn"
                        onclick="closeNotification('errorNotification')">&times;</button>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="notification error" id="errorsNotification">
                    <button type="button" class="close-btn"
                        onclick="closeNotification('errorsNotification')">&times;</button>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <style>
            .notification-container {
                position: fixed;
                top: 50px;
                right: 20px;
                z-index: 1050;
                display: flex;
                flex-direction: column;
                gap: 15px;
            }

            .notification {
                max-width: 300px;
                padding: 20px 25px;
                background: linear-gradient(to right, #4caf50, #81c784);
                color: #fff;
                font-family: 'Arial', sans-serif;
                display: flex;
                flex-direction: column;
                gap: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
                animation: slideIn 0.3s ease-out;
                position: relative;
            }

            .notification.error {
                background: linear-gradient(to right, #e53935, #ef5350);
            }

            .notification p,
            .notification ul {
                margin: 0;
            }

            .notification ul {
                padding-left: 20px;
                list-style: none;
                font-size: 0.9rem;
            }

            .close-btn {
                position: absolute;
                top: .5px;
                right: .5px;
                background: transparent;
                border: none;
                font-size: 1.2rem;
                font-weight: bold;
                color: #fff;
                cursor: pointer;
            }
        </style>

        <script>
            setTimeout(() => {
                document.querySelectorAll('.notification').forEach(notification => {
                    notification.style.opacity = '0';
                    setTimeout(() => {
                        notification.style.display = 'none';
                    }, 1000);
                });
            }, 10000);

            function closeNotification(id) {
                const element = document.getElementById(id);
                if (element) {
                    element.style.opacity = '0';
                    setTimeout(() => {
                        element.style.display = 'none';
                    }, 1000);
                }
            }
        </script>

        <!-- Company Header -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card bg-light">
                    <div class="card-body py-3">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="mb-1">{{ $company->company_name }}</h4>
                                <p class="mb-0 text-muted">
                                    {{ $company->job_title ?? 'N/A' }} •
                                    {{ \Carbon\Carbon::parse($company->drive_date)->format('d M Y') }} •
                                    {{ $company->package_offered ? '₹' . $company->package_offered . ' LPA' : 'N/A' }}
                                </p>
                                <div class="mt-2">
                                    @if ($company->eligibility_cgpa)
                                        <small class="badge bg-info me-2">Min CGPA: {{ $company->eligibility_cgpa }}</small>
                                    @endif
                                    
                                    @if (!empty($company->course_names))
                                        <small class="badge bg-warning"> Courses: {{ implode(', ', $company->course_names) }} </small>
                                    @endif
                                
                                    @if (!empty($company->department_names))
                                        <small class="badge bg-success"> Departments: {{ implode(', ', $company->department_names) }}</small>
                                    @endif
                                
                                    @if (!empty($company->passing_years_display))
                                        <small class="badge bg-info"> Years: {{ implode(', ', $company->passing_years_display) }}</small>
                                    @endif
                                
                                    @if ($company->tenth_percentage)
                                        <small class="badge bg-warning"> 10th: {{ $company->tenth_percentage }}%</small>
                                    @endif
                                
                                    @if ($company->twelfth_percentage)
                                        <small class="badge bg-success"> 12th: {{ $company->twelfth_percentage }}%</small>
                                    @endif
                                
                                    @if ($company->graduation_percentage)
                                        <small class="badge bg-secondary"> Grad: {{ $company->graduation_percentage }}%</small>
                                    @endif
                                
                                    <small class="badge bg-warning"> Reappear: {{ $company->is_reappear ? 'Allowed' : 'Not Allowed' }}</small>
                                </div>
                            </div>
                            <div class="col-md-4 text-right">
                               <a href="{{ url()->previous() }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Back to Drives
                                </a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="d-flex justify-content-around bg-light p-3 rounded">
                    <div class="text-center">
                        <div class="h4 mb-0 text-primary" id="totalCount">{{ $applicationData->count() }}</div>
                        <small class="text-muted">Total Approved</small>
                    </div>
                    <div class="text-center">
                        <div class="h4 mb-0 text-success" id="shortlistedCount">
                            {{ $applicationData->where('company_by', 1)->count() }}
                        </div>
                        <small class="text-muted">Shortlisted</small>
                    </div>
                    <div class="text-center">
                        <div class="h4 mb-0 text-warning" id="pendingCount">
                            {{ $applicationData->where('company_by', 0)->count() }}
                        </div>
                        <small class="text-muted">Pending Shortlist</small>
                    </div>
                    <div class="text-center">
                        <div class="h4 mb-0 text-info" id="selectedCount">
                            {{ $applicationData->where('application_status', 'selected')->count() }}
                        </div>
                        <small class="text-muted">Final Selected</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Fast Filters -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="row g-3">
                    <!-- Status Filter -->
                    <div class="col-lg-1 col-md-4">
                        <label class="form-label fw-bold">Status</label>
                        <select class="form-control form-control-sm" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="applied">Applied</option>
                            <option value="shortlisted">Shortlisted</option>
                            <option value="selected">Selected</option>
                        </select>
                    </div>



                    <!-- Reappear Filter -->
                    <div class="col-lg-1 col-md-4">
                        <label class="form-label fw-bold">Reappear</label>
                        <select class="form-control form-control-sm" id="reappearFilter">
                            <option value="">All</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <!-- CGPA Filter -->
                    <div class="col-lg-1 col-md-4">
                        <label class="form-label fw-bold">CGPA ≥</label>
                        <input type="number" step="0.01" class="form-control form-control-sm" placeholder="0.00"
                            id="cgpaFilter">
                    </div>


                    <!-- Academic Percentage Filters -->
                    <div class="col-lg-3 col-md-8">
                        <label class="form-label fw-bold">Academic % ≥</label>
                        <div class="row g-1">
                            <div class="col-4">
                                <input type="number" step="0.01" class="form-control form-control-sm" placeholder="10th"
                                    id="tenthFilter" title="10th Percentage">
                            </div>
                            <div class="col-4">
                                <input type="number" step="0.01" class="form-control form-control-sm" placeholder="12th"
                                    id="twelfthFilter" title="12th Percentage">
                            </div>
                            <div class="col-4">
                                <input type="number" step="0.01" class="form-control form-control-sm"
                                    placeholder="Grad" id="graduationFilter" title="Graduation Percentage">
                            </div>
                        </div>
                    </div>

                    <!-- Passing Year -->
                    <div class="col-lg-1 col-md-4">
                        <label class="form-label fw-bold">Year</label>
                        <select class="form-control form-control-sm" id="yearFilter" multiple>
                            <option value="">All Years</option>
                            @foreach ($allYears as $year)
                                <option value="{{ $year }}">{{ $year }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Course Filter -->
                    <div class="col-lg-1 col-md-4">
                        <label class="form-label fw-bold">Course</label>
                        <select class="form-control form-control-sm" id="courseFilter" multiple>
                            <option value="">All Courses</option>
                            @foreach ($allCourses as $course)
                                <option value="{{ $course }}">{{ $course }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Department Filter -->
                    <div class="col-lg-1 col-md-4">
                        <label class="form-label fw-bold">Department</label>
                        <select class="form-control form-control-sm" id="departmentFilter" multiple>
                            <option value="">All Departments</option>
                            @foreach ($allDepartments as $department)
                                <option value="{{ $department }}">{{ $department }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Multiple Skills Filter -->
                    <div class="col-lg-1 col-md-8">
                        <label class="form-label fw-bold">Skills</label>
                        <select class="form-control form-control-sm" multiple style="height: 80px; font-size: 0.875rem;"
                            id="skillsFilter">
                            @foreach ($allSkills as $skill)
                                <option value="{{ $skill }}">{{ $skill }}</option>
                            @endforeach
                        </select>
                        <small class="text-muted">Hold Ctrl to select multiple</small>
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-lg-2 col-md-4 d-flex align-items-end">
                        <div class="w-100">
                            <button onclick="resetFilters()" class="btn btn-outline-secondary btn-sm w-100 mb-1">
                                Reset
                            </button>
                            <button onclick="shortlistStudents()" class="btn btn-success btn-sm w-100 mb-1"
                                id="shortlistBtn" disabled>
                                Shortlist Selected
                            </button>
                            <button onclick="exportToExcel()" class="btn btn-info btn-sm w-100">
                                Excel
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Active Filters Badge -->
        <div class="row mb-3" id="activeFilters" style="display: none;">
            <div class="col-12">
                <div class="d-flex flex-wrap gap-2" id="filterBadges"></div>
            </div>
        </div>

        <!-- Applications Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center py-2">
                <h5 class="card-title mb-0">
                    College Approved Applications
                    <span class="badge badge-primary" id="tableCount">{{ $applicationData->count() }}</span>
                    <span class="badge badge-success ml-2" id="selectedRowsCount">0 Selected</span>
                </h5>
                <small class="text-muted" id="filterStatus" style="display: none;">Filtered Results</small>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0" id="applicationsTable">
                        <thead class="bg-light">
                            <tr>
                                <th width="40">
                                    <input type="checkbox" id="selectAll" onchange="toggleSelectAll(this)">
                                </th>
                                <th width="40">#</th>
                                <th>Student</th>
                                <th>Academic</th>
                                <th width="100">CGPA/Year</th>
                                <th width="180">Academic %</th>
                                <th width="150">Skills</th>
                                <th width="100">Status</th>
                                <th width="80">Reappear</th>
                                <th width="100">College Approved</th>
                                <th width="120">Applied</th>
                                <th width="120">Shortlisted</th>
                                <th width="60">Resume</th>
                                <th width="60">Profile</th>
                                <th width="80">Responses</th>
                            </tr>
                        </thead>

                        <tbody id="tableBody">
                            @foreach ($applicationData as $index => $data)
                                @php
                                    $studentAcademic = $academicHistory[$data->student_id] ?? collect();
                                    $tenth = $studentAcademic->where('education_level', '10th')->first();
                                    $twelfth = $studentAcademic->where('education_level', '12th')->first();
                                    $graduation = $studentAcademic
                                        ->whereIn('education_level', ['bachelor', 'diploma'])
                                        ->first();

                                    // Calculate percentages
                                    $tenthPercentage = $tenth
                                        ? ($tenth->grade_type == 'gpa'
                                            ? $tenth->grade * 9.5
                                            : $tenth->grade)
                                        : 0;
                                    $twelfthPercentage = $twelfth
                                        ? ($twelfth->grade_type == 'gpa'
                                            ? $twelfth->grade * 9.5
                                            : $twelfth->grade)
                                        : 0;
                                    $graduationPercentage = $graduation
                                        ? ($graduation->grade_type == 'gpa'
                                            ? $graduation->grade * 9.5
                                            : $graduation->grade)
                                        : 0;

                                    $applicationResponses = json_decode($data->application_responses, true) ?? [];
                                @endphp
                                <tr class="application-row" data-status="{{ $data->application_status }}"
                                    data-cgpa="{{ $data->cgpa ?? 0 }}" data-year="{{ $data->passing_year ?? '' }}"
                                    data-college="{{ $data->college_name ?? '' }}"
                                    data-course="{{ $data->course_name ?? '' }}"
                                    data-department="{{ $data->department_name ?? '' }}"
                                    data-tenth="{{ $tenthPercentage ?? 0 }}"
                                    data-twelfth="{{ $twelfthPercentage ?? 0 }}"
                                    data-graduation="{{ $graduationPercentage ?? 0 }}"
                                    data-reappear="{{ $data->is_reappear ?? 0 }}"
                                    data-skills="{{ implode(',', $studentSkills[$data->student_id] ?? []) }}"
                                    data-student-id="{{ $data->student_id }}"
                                    data-college-by="{{ $data->college_by ?? 0 }}"
                                    data-company-by="{{ $data->company_by ?? 0 }}">
                                    <td class="text-center">
                                        <input type="checkbox" class="row-checkbox" onchange="updateSelection()"
                                            {{ $data->company_by ? 'disabled' : '' }}>
                                    </td>
                                    <td class="text-center">{{ $index + 1 }}</td>

                                    <!-- Student Info -->
                                    <td>
                                        <div class="fw-bold text-dark">{{ $data->student_name }}</div>
                                        <small class="text-muted d-block">{{ $data->roll_number }}</small>
                                        <small class="text-muted">{{ Str::limit($data->student_email, 20) }}</small>
                                    </td>

                                    <!-- Academic -->
                                    <td>
                                        <small class="d-block fw-medium">{{ Str::limit($data->college_name, 25) }}</small>
                                        <small class="text-muted">{{ $data->course_name }} -
                                            {{ $data->department_name }}</small>
                                    </td>

                                    <!-- CGPA/Year -->
                                    <td>
                                        @if ($data->cgpa)
                                            <span
                                                class="badge bg-{{ $data->cgpa >= $company->eligibility_cgpa ? 'success' : 'danger' }} d-block mb-1">
                                                CGPA: {{ $data->cgpa }}
                                            </span>
                                        @endif

                                        {{-- @dd($data->passing_year); --}}

                                        @if ($data->passing_year)
                                            <span class="badge bg-{{ $data->passing_year ? 'info' : 'warning' }} d-block">
                                                YR: {{ $data->passing_year }}
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Academic Percentages -->
                                    <td>
                                        @if ($tenthPercentage > 0)
                                            <small class="d-block">10th:
                                                <strong>{{ number_format($tenthPercentage, 2) }}%</strong></small>
                                        @endif
                                        @if ($twelfthPercentage > 0)
                                            <small class="d-block">12th:
                                                <strong>{{ number_format($twelfthPercentage, 2) }}%</strong></small>
                                        @endif
                                        @if ($graduationPercentage > 0)
                                            <small class="d-block">Grad:
                                                <strong>{{ number_format($graduationPercentage, 2) }}%</strong></small>
                                        @endif
                                    </td>

                                    <!-- Skills -->
                                    <td>
                                        @if (isset($studentSkills[$data->student_id]) && count($studentSkills[$data->student_id]) > 0)
                                            @foreach (array_slice($studentSkills[$data->student_id], 0, 2) as $skill)
                                                <span
                                                    class="badge bg-{{ in_array($skill, $company->required_skills ?? []) ? 'primary' : 'secondary' }} mb-1 d-inline-block"
                                                    style="font-size: 0.7rem;">
                                                    {{ Str::limit($skill, 15) }}
                                                </span>
                                            @endforeach
                                            @if (count($studentSkills[$data->student_id]) > 2)
                                                <small
                                                    class="text-muted d-block">+{{ count($studentSkills[$data->student_id]) - 2 }}
                                                    more</small>
                                            @endif
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <!-- Status -->
                                    <td>
                                        @php
                                            $statusColors = [
                                                'applied' => 'bg-secondary',
                                                'shortlisted' => 'bg-info',
                                                'rejected' => 'bg-danger',
                                                'selected' => 'bg-success',
                                            ];
                                        @endphp
                                        <span
                                            class="badge {{ $statusColors[$data->application_status] ?? 'bg-secondary' }}">
                                            {{ ucfirst($data->application_status) }}
                                        </span>
                                        @if ($data->company_by)
                                            <small class="text-success d-block mt-1">Company Shortlisted</small>
                                        @endif
                                    </td>

                                    <!-- Reappear Status -->
                                    <td class="text-center">
                                        @if ($data->is_reappear)
                                            <span class="badge bg-warning">Yes</span>
                                        @else
                                            <span class="badge bg-secondary">No</span>
                                        @endif
                                    </td>

                                    <!-- College Approved -->
                                    <td>
                                        @if ($data->college_by)
                                            <span class="badge bg-success">Approved</span>
                                            <small class="text-muted d-block">By College</small>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>

                                    <!-- Applied Date -->
                                    <td>
                                        <small
                                            class="d-block">{{ \Carbon\Carbon::parse($data->applied_at)->format('d M Y') }}</small>
                                        <small
                                            class="text-muted">{{ \Carbon\Carbon::parse($data->applied_at)->format('H:i') }}</small>
                                    </td>

                                    <!-- Shortlisted Date -->
                                    <td>
                                        @if ($data->shortlisted_at)
                                            <small
                                                class="d-block text-success">{{ \Carbon\Carbon::parse($data->shortlisted_at)->format('d M Y') }}</small>
                                            <small
                                                class="text-muted">{{ \Carbon\Carbon::parse($data->shortlisted_at)->format('H:i') }}</small>
                                        @else
                                            <span class="badge bg-warning">Pending</span>
                                        @endif
                                    </td>

                                    <!-- Resume -->
                                    <td class="text-center">
                                        @if ($data->resume_path)
                                            <div class="btn-group-vertical btn-group-sm" role="group"
                                                style="min-width: 60px;">
                                                <a href="{{ asset('storage/' . $data->resume_path) }}" target="_blank"
                                                    class="btn btn-outline-info btn-sm" title="View Resume">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ asset('storage/' . $data->resume_path) }}" download
                                                    class="btn btn-outline-primary btn-sm" title="Download Resume">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route('company.user.profile', $data->student_id) }}"
                                            class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>

                                    <!-- Application Responses -->
                                    <td class="text-center">
                                        @if (!empty($applicationResponses))
                                            <button class="btn btn-outline-warning btn-sm"
                                                onclick="showResponses({{ $index }})" title="View Responses">
                                                <i class="fas fa-comment-alt"></i>
                                            </button>
                                            <div id="responses-{{ $index }}" style="display: none;">
                                                <div class="card mt-2">
                                                    <div class="card-body p-2">
                                                        <h6 class="card-title">Application Responses</h6>
                                                        @foreach ($applicationResponses as $key => $value)
                                                            @if (is_string($value))
                                                                <p class="mb-1">
                                                                    <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong>
                                                                    {{ $value }}
                                                                </p>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Responses Modal -->
    <div class="modal fade" id="responsesModal" tabindex="-1" aria-labelledby="responsesModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="responsesModalLabel">Application Responses</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="responsesModalBody">
                    <!-- Responses content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .table-sm td,
        .table-sm th {
            padding: 0.5rem;
            font-size: 0.875rem;
        }

        .card-header {
            padding: 0.75rem 1rem;
        }

        .form-control-sm {
            height: calc(1.5em + 0.5rem + 2px);
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        .badge {
            font-size: 0.75em;
            padding: 0.35em 0.65em;
        }

        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }

        select[multiple] {
            background-image: none;
            padding-right: 0.75rem;
        }

        .row-disabled {
            opacity: 0.6;
            background-color: #f8f9fa;
        }

        .application-responses {
            max-height: 300px;
            overflow-y: auto;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Store all application data for filtering
        let allApplications = document.querySelectorAll('.application-row');
        let currentFilters = {
            status: '',
            reappear: '',
            min_cgpa: '',
            passing_year: [],
            course: [],
            department: [],
            tenth: '',
            twelfth: '',
            graduation: '',
            skills: []
        };

        // Apply filters function
        function applyFilters() {
            let visibleCount = 0;
            let shortlistedCount = 0;
            let pendingCount = 0;
            let selectedCount = 0;

            allApplications.forEach(row => {
                let showRow = true;

                // Status filter
                if (currentFilters.status && row.dataset.status !== currentFilters.status) {
                    showRow = false;
                }



                // Reappear filter - ONLY APPLIES TO REAPPEAR STUDENTS
                if (currentFilters.reappear !== '') {
                    const isReappear = row.dataset.reappear === '1';

                    // If student is NOT reappear, always show (don't filter out)
                    // If student IS reappear, apply the filter
                    if (isReappear && row.dataset.reappear !== currentFilters.reappear) {
                        showRow = false;
                    }
                    // Non-reappear students are always shown regardless of filter
                }

                // CGPA filter
                if (currentFilters.min_cgpa && parseFloat(row.dataset.cgpa) < parseFloat(currentFilters.min_cgpa)) {
                    showRow = false;
                }

                // Year filter - Multiple selection
                if (currentFilters.passing_year.length > 0 && !currentFilters.passing_year.includes(row.dataset
                        .year)) {
                    showRow = false;
                }

                // Course filter - Multiple selection
                if (currentFilters.course.length > 0 && !currentFilters.course.includes(row.dataset.course)) {
                    showRow = false;
                }

                // Department filter - Multiple selection
                if (currentFilters.department.length > 0 && !currentFilters.department.includes(row.dataset
                        .department)) {
                    showRow = false;
                }

                // Tenth percentage filter
                if (currentFilters.tenth && parseFloat(row.dataset.tenth) < parseFloat(currentFilters.tenth)) {
                    showRow = false;
                }

                // Twelfth percentage filter
                if (currentFilters.twelfth && parseFloat(row.dataset.twelfth) < parseFloat(currentFilters
                        .twelfth)) {
                    showRow = false;
                }

                // Graduation percentage filter
                if (currentFilters.graduation && parseFloat(row.dataset.graduation) < parseFloat(currentFilters
                        .graduation)) {
                    showRow = false;
                }

                // Skills filter - Multiple selection
                if (currentFilters.skills.length > 0) {
                    const rowSkills = row.dataset.skills.split(',');
                    const hasMatchingSkill = currentFilters.skills.some(skill => rowSkills.includes(skill));
                    if (!hasMatchingSkill) {
                        showRow = false;
                    }
                }

                if (showRow) {
                    row.style.display = '';
                    visibleCount++;

                    // Count by status
                    if (row.dataset.companyBy === '1') shortlistedCount++;
                    if (row.dataset.companyBy === '0') pendingCount++;
                    if (row.dataset.status === 'selected') selectedCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Update counters
            document.getElementById('tableCount').textContent = visibleCount;
            document.getElementById('totalCount').textContent = visibleCount;
            document.getElementById('shortlistedCount').textContent = shortlistedCount;
            document.getElementById('pendingCount').textContent = pendingCount;
            document.getElementById('selectedCount').textContent = selectedCount;

            // Update filter badges
            updateFilterBadges();
            updateSelection();
        }

        // Update filter badges
        function updateFilterBadges() {
            const badgesContainer = document.getElementById('filterBadges');
            const activeFiltersContainer = document.getElementById('activeFilters');
            badgesContainer.innerHTML = '';

            let hasActiveFilters = false;

            if (currentFilters.status) {
                badgesContainer.innerHTML += `<span class="badge badge-primary">Status: ${currentFilters.status}</span>`;
                hasActiveFilters = true;
            }



            if (currentFilters.reappear !== '') {
                badgesContainer.innerHTML +=
                    `<span class="badge badge-warning">Reappear: ${currentFilters.reappear === '1' ? 'Yes' : 'No'}</span>`;
                hasActiveFilters = true;
            }

            if (currentFilters.min_cgpa) {
                badgesContainer.innerHTML += `<span class="badge badge-success">CGPA ≥ ${currentFilters.min_cgpa}</span>`;
                hasActiveFilters = true;
            }

            if (currentFilters.passing_year.length > 0) {
                badgesContainer.innerHTML +=
                    `<span class="badge badge-warning">Years: ${currentFilters.passing_year.join(', ')}</span>`;
                hasActiveFilters = true;
            }

            if (currentFilters.course.length > 0) {
                badgesContainer.innerHTML +=
                    `<span class="badge badge-secondary">Courses: ${currentFilters.course.join(', ')}</span>`;
                hasActiveFilters = true;
            }

            if (currentFilters.department.length > 0) {
                badgesContainer.innerHTML +=
                    `<span class="badge badge-dark">Depts: ${currentFilters.department.join(', ')}</span>`;
                hasActiveFilters = true;
            }

            if (currentFilters.tenth) {
                badgesContainer.innerHTML += `<span class="badge badge-primary">10th ≥ ${currentFilters.tenth}%</span>`;
                hasActiveFilters = true;
            }

            if (currentFilters.twelfth) {
                badgesContainer.innerHTML += `<span class="badge badge-info">12th ≥ ${currentFilters.twelfth}%</span>`;
                hasActiveFilters = true;
            }

            if (currentFilters.graduation) {
                badgesContainer.innerHTML +=
                    `<span class="badge badge-success">Grad ≥ ${currentFilters.graduation}%</span>`;
                hasActiveFilters = true;
            }

            if (currentFilters.skills.length > 0) {
                badgesContainer.innerHTML +=
                    `<span class="badge badge-dark">Skills: ${currentFilters.skills.join(', ')}</span>`;
                hasActiveFilters = true;
            }

            if (hasActiveFilters) {
                activeFiltersContainer.style.display = 'block';
                document.getElementById('filterStatus').style.display = 'inline';
            } else {
                activeFiltersContainer.style.display = 'none';
                document.getElementById('filterStatus').style.display = 'none';
            }
        }

        // Reset all filters
        function resetFilters() {
            document.getElementById('statusFilter').value = '';
            document.getElementById('reappearFilter').value = '';
            document.getElementById('cgpaFilter').value = '';

            // Reset multiple select filters
            const yearSelect = document.getElementById('yearFilter');
            for (let option of yearSelect.options) {
                option.selected = false;
            }

            const courseSelect = document.getElementById('courseFilter');
            for (let option of courseSelect.options) {
                option.selected = false;
            }

            const departmentSelect = document.getElementById('departmentFilter');
            for (let option of departmentSelect.options) {
                option.selected = false;
            }

            const skillsSelect = document.getElementById('skillsFilter');
            for (let option of skillsSelect.options) {
                option.selected = false;
            }

            document.getElementById('tenthFilter').value = '';
            document.getElementById('twelfthFilter').value = '';
            document.getElementById('graduationFilter').value = '';

            currentFilters = {
                status: '',
                reappear: '',
                min_cgpa: '',
                passing_year: [],
                course: [],
                department: [],
                tenth: '',
                twelfth: '',
                graduation: '',
                skills: []
            };

            applyFilters();
        }

        // Selection management
        function toggleSelectAll(checkbox) {
            const checkboxes = document.querySelectorAll('.row-checkbox:not(:disabled)');
            checkboxes.forEach(cb => {
                if (cb.closest('.application-row').style.display !== 'none') {
                    cb.checked = checkbox.checked;
                }
            });
            updateSelection();
        }

        function updateSelection() {
            const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked');
            const selectedCount = selectedCheckboxes.length;

            document.getElementById('selectedRowsCount').textContent = selectedCount + ' Selected';
            document.getElementById('shortlistBtn').disabled = selectedCount === 0;
        }

        // Shortlist students
       function shortlistStudents() {
    const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked');
    const studentIds = Array.from(selectedCheckboxes).map(checkbox => {
        return checkbox.closest('.application-row').dataset.studentId;
    });

    // Count visible applications that are eligible for processing
    const visibleRows = Array.from(document.querySelectorAll('.application-row')).filter(row => {
        return row.style.display !== 'none' && 
               !row.querySelector('.row-checkbox').disabled &&
               row.dataset.status === 'applied'; // Only include 'applied' status
    });
    
    const totalVisible = visibleRows.length;
    const unselectedCount = totalVisible - studentIds.length;
    
    if (totalVisible === 0) {
        alert('No applications available to process.');
        return;
    }
    
    let confirmMessage = '';
    
    if (studentIds.length === 0) {
        confirmMessage = `No students selected. This will REJECT ALL ${totalVisible} visible student(s).\n\nAre you sure?`;
    } else {
        confirmMessage = `Shortlist ${studentIds.length} student(s) and REJECT ${unselectedCount} student(s)?\n\nChecked = Shortlisted\nUnchecked = Rejected`;
    }
    
    if (!confirm(confirmMessage)) {
        return;
    }

    // Create a form and submit it
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route('company.update.by') }}';

    // Add CSRF token
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);

    // Add student IDs (can be empty array)
    studentIds.forEach((id, index) => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = `student_ids[${index}]`;
        input.value = id;
        form.appendChild(input);
    });

    // Add placement drive ID
    const driveInput = document.createElement('input');
    driveInput.type = 'hidden';
    driveInput.name = 'placement_drive_id';
    driveInput.value = '{{ $company->id }}';
    form.appendChild(driveInput);

    // Add to document and submit
    document.body.appendChild(form);
    form.submit();
}
        // Show application responses in modal
        function showResponses(index) {
            const row = document.querySelectorAll('.application-row')[index];
            const studentName = row.querySelector('.fw-bold').textContent;

            const responsesDiv = document.getElementById(`responses-${index}`);

            if (responsesDiv) {
                const responsesContent = responsesDiv.innerHTML;
                document.getElementById('responsesModalLabel').textContent = `Application Responses - ${studentName}`;
                document.getElementById('responsesModalBody').innerHTML = responsesContent;
                const modal = new bootstrap.Modal(document.getElementById('responsesModal'));
                modal.show();
            }
        }

        // Export to Excel
        function exportToExcel() {
            const visibleRows = Array.from(allApplications).filter(row => row.style.display !== 'none');

            if (visibleRows.length === 0) {
                alert('No data to export!');
                return;
            }

            let csvContent =
                "Student Name,Roll Number,Email,College,Course,Department,CGPA,Passing Year,10th %,12th %,Grad %,Skills,Status,Reappear,College Approved,Company Shortlisted,Applied Date,Shortlisted Date\n";

            visibleRows.forEach(row => {
                const cells = row.cells;
                const studentName = cells[2].querySelector('.fw-bold').textContent.trim();
                const rollNumber = cells[2].querySelector('small').textContent.trim();
                const email = cells[2].querySelectorAll('small')[1].textContent.trim();
                const college = row.dataset.college;
                const course = row.dataset.course;
                const department = row.dataset.department;
                const cgpa = row.dataset.cgpa || '';
                const year = row.dataset.year || '';
                const tenth = row.dataset.tenth || '';
                const twelfth = row.dataset.twelfth || '';
                const graduation = row.dataset.graduation || '';
                const skills = row.dataset.skills;
                const status = row.dataset.status;
                const reappear = row.dataset.reappear === '1' ? 'Yes' : 'No';
                const collegeApproved = row.dataset.collegeBy === '1' ? 'Yes' : 'No';
                const companyShortlisted = row.dataset.companyBy === '1' ? 'Yes' : 'No';
                const appliedDate = cells[10].querySelector('small').textContent.trim();
                const shortlistedDate = cells[11].querySelector('small') ? cells[11].querySelector('small')
                    .textContent.trim() : 'Not Shortlisted';

                const escapeCSV = (str) => `"${String(str).replace(/"/g, '""')}"`;

                csvContent += [
                    escapeCSV(studentName),
                    escapeCSV(rollNumber),
                    escapeCSV(email),
                    escapeCSV(college),
                    escapeCSV(course),
                    escapeCSV(department),
                    escapeCSV(cgpa),
                    escapeCSV(year),
                    escapeCSV(tenth),
                    escapeCSV(twelfth),
                    escapeCSV(graduation),
                    escapeCSV(skills),
                    escapeCSV(status),
                    escapeCSV(reappear),
                    escapeCSV(collegeApproved),
                    escapeCSV(companyShortlisted),
                    escapeCSV(appliedDate),
                    escapeCSV(shortlistedDate)
                ].join(',') + '\n';
            });

            const blob = new Blob([csvContent], {
                type: 'text/csv;charset=utf-8;'
            });
            const link = document.createElement("a");
            const url = URL.createObjectURL(blob);

            link.setAttribute("href", url);
            link.setAttribute("download",
                "college_approved_applications_{{ $company->company_name }}_{{ date('Y-m-d') }}.csv");
            link.style.visibility = 'hidden';

            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            // Filter event listeners
            document.getElementById('statusFilter').addEventListener('change', function() {
                currentFilters.status = this.value;
                applyFilters();
            });



            document.getElementById('reappearFilter').addEventListener('change', function() {
                currentFilters.reappear = this.value;
                applyFilters();
            });

            document.getElementById('cgpaFilter').addEventListener('input', function() {
                currentFilters.min_cgpa = this.value;
                applyFilters();
            });

            document.getElementById('yearFilter').addEventListener('change', function() {
                currentFilters.passing_year = this.value;
                applyFilters();
            });

            document.getElementById('courseFilter').addEventListener('change', function() {
                currentFilters.course = this.value;
                applyFilters();
            });

            document.getElementById('departmentFilter').addEventListener('change', function() {
                currentFilters.department = this.value;
                applyFilters();
            });

            document.getElementById('tenthFilter').addEventListener('input', function() {
                currentFilters.tenth = this.value;
                applyFilters();
            });

            document.getElementById('twelfthFilter').addEventListener('input', function() {
                currentFilters.twelfth = this.value;
                applyFilters();
            });

            document.getElementById('graduationFilter').addEventListener('input', function() {
                currentFilters.graduation = this.value;
                applyFilters();
            });

            document.getElementById('skillsFilter').addEventListener('change', function() {
                currentFilters.skills = Array.from(this.selectedOptions).map(option => option.value);
                applyFilters();
            });

            // Initialize selection count
            updateSelection();
        });
    </script>
    <!-- Add Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection
