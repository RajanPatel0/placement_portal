@extends('dashboardLayouts.base')

@section('title', $company->company_name . ' - Applications')

@section('content')
    <div class="container-fluid pt-4">

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
                                <!-- Drive Eligibility Info -->
                                <div class="mt-2">
                                    @php
                                        $driveCourses = json_decode($company->course_id, true) ?? [];
                                        $driveDepartments = json_decode($company->department_id, true) ?? [];
                                        $drivePassingYears =
                                            json_decode($company->eligibility_passing_year, true) ?? [];

                                        // Ensure arrays
                                        $driveCourses = is_array($driveCourses) ? $driveCourses : [];
                                        $driveDepartments = is_array($driveDepartments) ? $driveDepartments : [];
                                        $drivePassingYears = is_array($drivePassingYears) ? $drivePassingYears : [];

                                        $courseNames = DB::table('courses')
                                            ->whereIn('id', $driveCourses)
                                            ->pluck('name')
                                            ->toArray();

                                        $departmentNames = DB::table('departments')
                                            ->whereIn('id', $driveDepartments)
                                            ->pluck('name')
                                            ->toArray();

                                        // Format passing years for display
                                        $passingYearsDisplay = [];
                                        if (is_array($drivePassingYears)) {
                                            $passingYearsDisplay = $drivePassingYears;
                                        } elseif (!empty($drivePassingYears)) {
                                            $passingYearsDisplay = [$drivePassingYears];
                                        }
                                    @endphp

                                    <small class="text-muted">
                                        <strong>Eligibility:</strong>

                                        @if (!empty($company->eligibility_cgpa))
                                            CGPA: {{ $company->eligibility_cgpa }} |
                                        @endif

                                        @if (!empty($courseNames))
                                            Courses: {{ implode(', ', $courseNames) }} |
                                        @endif

                                        @if (!empty($departmentNames))
                                            Departments: {{ implode(', ', $departmentNames) }} |
                                        @endif

                                        @if (!empty($passingYearsDisplay))
                                            Years: {{ implode(', ', $passingYearsDisplay) }}
                                        @endif

                                        @if ($company->tenth_percentage)
                                            | 10th: {{ $company->tenth_percentage }}%
                                        @endif

                                        @if ($company->twelfth_percentage)
                                            | 12th: {{ $company->twelfth_percentage }}%
                                        @endif

                                        @if ($company->graduation_percentage)
                                            | Grad: {{ $company->graduation_percentage }}%
                                        @endif

                                        | Reappear: {{ $company->is_reappear ? 'Allowed' : 'Not Allowed' }}
                                    </small>
                                </div>

                            </div>
                            <div class="col-md-4 text-right">
                                <a href="{{ route('admin.companies') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Back
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
                        <small class="text-muted">Total</small>
                    </div>
                    <div class="text-center">
                        <div class="h4 mb-0 text-success" id="selectedCount">
                            {{ $applicationData->where('application_status', 'selected')->count() }}
                        </div>
                        <small class="text-muted">Selected</small>
                    </div>
                    <div class="text-center">
                        <div class="h4 mb-0 text-warning" id="shortlistedCount">
                            {{ $applicationData->where('application_status', 'shortlisted')->count() }}
                        </div>
                        <small class="text-muted">Shortlisted</small>
                    </div>
                    <div class="text-center">
                        <div class="h4 mb-0 text-info" id="appliedCount">
                            {{ $applicationData->where('application_status', 'applied')->count() }}
                        </div>
                        <small class="text-muted">Applied</small>
                    </div>

                </div>
            </div>
        </div>

        <!-- Fast Filters -->
        <div class="card mb-3">
            <div class="card-body">
                <div class="row g-3">
                    <!-- Status Filter -->
                    <div class="col-lg-2 col-md-4">
                        <label class="form-label fw-bold">Status</label>
                        <select class="form-control form-control-sm" id="statusFilter">
                            <option value="">All Status</option>
                            <option value="applied">Applied</option>
                            <option value="shortlisted">Shortlisted</option>
                            <option value="selected">Selected</option>

                        </select>
                    </div>



                    <!-- Reappear Filter -->
                    <div class="col-lg-2 col-md-4">
                        <label class="form-label fw-bold">Reappear</label>
                        <select class="form-control form-control-sm" id="reappearFilter">
                            <option value="">All</option>
                            <option value="1">Yes</option>
                            <option value="0">No</option>
                        </select>
                    </div>

                    <!-- CGPA Filter -->
                    <div class="col-lg-2 col-md-4">
                        <label class="form-label fw-bold">CGPA ≥</label>
                        <input type="number" step="0.01" class="form-control form-control-sm" placeholder="0.00"
                            id="cgpaFilter">
                    </div>





                    <!-- Academic Percentage Filters -->
                    <div class="col-lg-4 col-md-8">
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
                                <input type="number" step="0.01" class="form-control form-control-sm" placeholder="Grad"
                                    id="graduationFilter" title="Graduation Percentage">
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-lg-2 col-md-4 d-flex align-items-end">
                        <div class="w-100">
                            <button onclick="resetFilters()" class="btn btn-outline-secondary btn-sm w-100 mb-1">
                                Reset
                            </button>
                            <button onclick="updateCollegeBy()" class="btn btn-warning btn-sm w-100 mb-1"
                                id="updateCollegeBtn" disabled>
                                Approve Selected
                            </button>
                            <button onclick="exportToExcel()" class="btn btn-success btn-sm w-100">
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
                    Applications
                    <span class="badge badge-primary" id="tableCount">{{ $applicationData->count() }}</span>
                    <span class="badge badge-success ml-2" id="selectedRowsCount">0 Selected</span>
                    <span class="badge badge-info ml-2" id="eligibleRowsCount">0 Eligible</span>
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
                                <th width="120">CGPA/Year</th>
                                <th width="120">Academic %</th>
                                <th width="100">Status</th>
                                <th width="80">Reappear</th>
                                <th width="120">Applied</th>
                                <th width="100">College Approved</th>
                                <th width="60">Resume</th>
                                <th width="60">Profile</th>
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
                                            ? $tenth->grade * 10
                                            : $tenth->grade)
                                        : 0;
                                    $twelfthPercentage = $twelfth
                                        ? ($twelfth->grade_type == 'gpa'
                                            ? $twelfth->grade * 10
                                            : $twelfth->grade)
                                        : 0;
                                    $graduationPercentage = $graduation
                                        ? ($graduation->grade_type == 'gpa'
                                            ? $graduation->grade * 10
                                            : $graduation->grade)
                                        : 0;

                                @endphp
                                <tr class="application-row" data-status="{{ $data->application_status }}"
                                    data-cgpa="{{ $data->cgpa ?? 0 }}" data-year="{{ $data->passing_year ?? '' }}"
                                    data-course="{{ $data->course_name ?? '' }}"
                                    data-department="{{ $data->department_name ?? '' }}"
                                    data-tenth="{{ $tenthPercentage }}" data-twelfth="{{ $twelfthPercentage }}"
                                    data-graduation="{{ $graduationPercentage }}"
                                    data-reappear="{{ $data->is_reappear ?? 0 }}"
                                    data-student-id="{{ $data->student_id }}"
                                    data-college-by="{{ $data->college_by ?? 0 }}">
                                    <td class="text-center">
                                        <input type="checkbox" class="row-checkbox" onchange="updateSelection()"
                                            {{ $data->college_by ? 'disabled' : '' }}>
                                    </td>
                                    <td class="text-center">{{ $index + 1 }}</td>

                                    <!-- Student Info -->
                                    <td>
                                        <div class="fw-bold text-dark">{{ $data->student_name }}</div>
                                        <small class="text-muted d-block">{{ $data->roll_number }}</small>
                                        <small class="text-muted">{{ Str::limit($data->student_email) }}</small>
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
                                            <span class="badge bg-success d-block mb-1">CGPA: {{ $data->cgpa }}</span>
                                        @endif
                                        @if ($data->passing_year)
                                            <span class="badge bg-info d-block">YR: {{ $data->passing_year }}</span>
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
                                    </td>



                                    <!-- Reappear Status -->
                                    <td class="text-center">
                                        @if ($data->is_reappear)
                                            <span class="badge bg-warning">Yes</span>
                                        @else
                                            <span class="badge bg-secondary">No</span>
                                        @endif
                                    </td>

                                    <!-- Applied Date -->
                                    <td>
                                        <small
                                            class="d-block">{{ \Carbon\Carbon::parse($data->applied_at)->format('d M Y') }}</small>
                                        <small
                                            class="text-muted">{{ \Carbon\Carbon::parse($data->applied_at)->format('H:i') }}</small>
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

                                    <!-- Resume -->
                                 <td class="text-center">
                                        @if ($data->resume_path)
                                            <div class="btn-group-vertical btn-group-sm" role="group"
                                                style="min-width: 60px;">
                                                <a href="{{ asset('/public/' . $data->resume_path)}}" target="_blank"
                                                    class="btn btn-outline-info btn-sm" title="View Resume">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ asset('/public/' . $data->resume_path) }}" download
                                                    class="btn btn-outline-primary btn-sm" title="Download Resume">
                                                    <i class="fas fa-download"></i>
                                                </a>
                                            </div>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <td class="text-center">
                                        <a href="{{ route('admin.user.profile', $data->student_id) }}"
                                            class="btn btn-outline-info btn-sm">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

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

        .row-disabled {
            opacity: 0.6;
            background-color: #f8f9fa;
        }
    </style>

    <script>
        // Store all application data for filtering
        let allApplications = document.querySelectorAll('.application-row');
        let currentFilters = {
            status: '',
            reappear: '',
            min_cgpa: '',
            tenth: '',
            twelfth: '',
            graduation: ''
        };

        // Apply filters function
        function applyFilters() {
            let visibleCount = 0;
            let selectedCount = 0;
            let shortlistedCount = 0;
            let appliedCount = 0;
            let eligibleCount = 0;

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

                if (showRow) {
                    row.style.display = '';
                    visibleCount++;

                    // Count by status
                    if (row.dataset.status === 'selected') selectedCount++;
                    if (row.dataset.status === 'shortlisted') shortlistedCount++;
                    if (row.dataset.status === 'applied') appliedCount++;
                    if (row.dataset.eligible === '1') eligibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });

            // Update counters
            document.getElementById('tableCount').textContent = visibleCount;
            document.getElementById('totalCount').textContent = visibleCount;
            document.getElementById('selectedCount').textContent = selectedCount;
            document.getElementById('shortlistedCount').textContent = shortlistedCount;
            document.getElementById('appliedCount').textContent = appliedCount;

            // Update eligible count if the element exists
            const eligibleElement = document.getElementById('eligibleRowsCount');
            if (eligibleElement) {
                eligibleElement.textContent = eligibleCount + ' Eligible';
            }

            // Update filter badges
            updateFilterBadges();
            updateSelection();
        }

        // Update college_by status - Fixed to use correct route
        function updateCollegeBy() {
            const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked');
            const studentIds = Array.from(selectedCheckboxes).map(checkbox => {
                return checkbox.closest('.application-row').dataset.studentId;
            });

            if (studentIds.length === 0) {
                alert('Please select at least one student');
                return;
            }

            if (!confirm(`Are you sure you want to approve ${studentIds.length} student(s) for college placement?`)) {
                return;
            }

            // Show loading
            const btn = document.getElementById('updateCollegeBtn');
            const originalText = btn.innerHTML;
            btn.innerHTML = 'Updating...';
            btn.disabled = true;

            // Make API call - Use the correct route name
            fetch('{{ route('admin.update.college.by') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        student_ids: studentIds,
                        placement_drive_id: {{ $company->id }}
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + (data.message || 'Unknown error occurred'));
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }
                })
                .catch(error => {
                    alert('Error: ' + error.message);
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
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
            document.getElementById('updateCollegeBtn').disabled = selectedCount === 0;
        }

        // Update college_by status
        function updateCollegeBy() {
            const selectedCheckboxes = document.querySelectorAll('.row-checkbox:checked');
            const studentIds = Array.from(selectedCheckboxes).map(checkbox => {
                return checkbox.closest('.application-row').dataset.studentId;
            });

            if (studentIds.length === 0) {
                alert('Please select at least one student');
                return;
            }

            if (!confirm(`Are you sure you want to approve ${studentIds.length} student(s) for college placement?`)) {
                return;
            }

            // Show loading
            const btn = document.getElementById('updateCollegeBtn');
            const originalText = btn.innerHTML;
            btn.innerHTML = 'Updating...';
            btn.disabled = true;

            // Make API call
            fetch('{{ route('admin.update.college.by') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        student_ids: studentIds,
                        placement_drive_id: {{ $company->id }}
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload();
                    } else {
                        alert('Error: ' + data.message);
                        btn.innerHTML = originalText;
                        btn.disabled = false;
                    }
                })
                .catch(error => {
                    alert('Error: ' + error);
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                });
        }

        // Export to Excel
        // Export to Excel
       // Export to Excel
        function exportToExcel() {
            const visibleRows = Array.from(allApplications).filter(row => row.style.display !== 'none');
        
            if (visibleRows.length === 0) {
                alert('No data to export!');
                return;
            }
        
            let csvContent = "Student Name,Roll Number,Email,College,Course,Department,CGPA,Passing Year,10th %,12th %,Grad %,Status,Reappear,College Approved,Applied Date\n";
        
            visibleRows.forEach(row => {
                const cells = row.cells;
                const studentName = cells[2].querySelector('.fw-bold').textContent.trim();
                const rollNumber = cells[2].querySelectorAll('small')[0].textContent.trim();
                const email = cells[2].querySelectorAll('small')[1].textContent.trim();
                
                // Academic info - Fixed college, course, department extraction
                const collegeElement = cells[3].querySelector('.fw-medium');
                const college = collegeElement ? collegeElement.textContent.trim() : '';
                
                // Get course and department from data attributes instead of parsing text
                const course = row.dataset.course || '';
                const department = row.dataset.department || '';
                
                // Alternative: If data attributes are empty, try to extract from text
                let extractedCourse = course;
                let extractedDepartment = department;
                
                if (!course || !department) {
                    const courseDept = cells[3].querySelectorAll('small')[0];
                    if (courseDept) {
                        const courseDeptText = courseDept.textContent.trim();
                        const parts = courseDeptText.split(' - ');
                        if (parts.length === 2) {
                            extractedCourse = parts[0].trim();
                            extractedDepartment = parts[1].trim();
                        } else if (parts.length === 1) {
                            extractedCourse = parts[0].trim();
                        }
                    }
                }
                
                // CGPA/Year
                const cgpaElement = cells[4].querySelector('.bg-success');
                const cgpa = cgpaElement ? cgpaElement.textContent.replace('CGPA:', '').trim() : '';
                const yearElement = cells[4].querySelector('.bg-info');
                const year = yearElement ? yearElement.textContent.replace('YR:', '').trim() : '';
                
                // Academic percentages
                const tenth = row.dataset.tenth || '';
                const twelfth = row.dataset.twelfth || '';
                const graduation = row.dataset.graduation || '';
                
                // Status
                const statusElement = cells[6].querySelector('.badge');
                const status = statusElement ? statusElement.textContent.trim() : '';
                
                // Reappear
                const reappearElement = cells[7].querySelector('.badge');
                const reappear = reappearElement ? (reappearElement.textContent.trim() === 'Yes' ? 'Yes' : 'No') : 'No';
                
                // Applied Date
                const appliedDateElements = cells[8].querySelectorAll('small');
                const appliedDate = appliedDateElements[0] ? appliedDateElements[0].textContent.trim() : '';
                const appliedTime = appliedDateElements[1] ? appliedDateElements[1].textContent.trim() : '';
                const fullAppliedDate = appliedDate && appliedTime ? `${appliedDate} ${appliedTime}` : appliedDate;
                
                // College Approved
                const collegeApprovedElement = cells[9].querySelector('.badge');
                const collegeApproved = collegeApprovedElement ? (collegeApprovedElement.textContent.trim() === 'Approved' ? 'Yes' : 'No') : 'No';
        
                const escapeCSV = (str) => `"${String(str).replace(/"/g, '""')}"`;
        
                csvContent += [
                    escapeCSV(studentName),
                    escapeCSV(rollNumber),
                    escapeCSV(email),
                    escapeCSV(college),
                    escapeCSV(extractedCourse),
                    escapeCSV(extractedDepartment),
                    escapeCSV(cgpa),
                    escapeCSV(year),
                    escapeCSV(tenth),
                    escapeCSV(twelfth),
                    escapeCSV(graduation),
                    escapeCSV(status),
                    escapeCSV(reappear),
                    escapeCSV(collegeApproved),
                    escapeCSV(fullAppliedDate)
                ].join(',') + '\n';
            });
        
            const blob = new Blob([csvContent], {
                type: 'text/csv;charset=utf-8;'
            });
            const link = document.createElement("a");
            const url = URL.createObjectURL(blob);
        
            link.setAttribute("href", url);
            link.setAttribute("download", "applications_" + new Date().toISOString().split('T')[0] + ".csv");
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

            // Initialize selection count and eligibility count
            updateSelection();
            applyFilters();
        });
    </script>
    <!-- Add Font Awesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection
