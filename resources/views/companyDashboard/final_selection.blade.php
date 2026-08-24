@extends('dashboardLayouts.base')

@section('title', $company->company_name . ' - Final Selection')

@section('content')
    <div class="container-fluid pt-4">

        <!-- This section will trigger the modal -->
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
                /* Padding from the top */
                right: .5px;
                /* Padding from the right */
                background: transparent;
                border: none;
                font-size: 1.2rem;
                font-weight: bold;
                color: #fff;
                cursor: pointer;
            }
        </style>
        <script>
            // Auto-hide the notification after 5 seconds
            setTimeout(() => {
                document.querySelectorAll('.notification').forEach(notification => {
                    notification.style.opacity = '0';
                    setTimeout(() => {
                        notification.style.display = 'none';
                    }, 1000); // Allow fade-out animation to complete
                });
            }, 10000);

            // Manually close the notification
            function closeNotification(id) {
                const element = document.getElementById(id);
                if (element) {
                    element.style.opacity = '0';
                    setTimeout(() => {
                        element.style.display = 'none';
                    }, 1000); // Allow fade-out animation to complete
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
                                <h4 class="mb-1">{{ $company->company_name }} - Final Selection</h4>
                                <p class="mb-0 text-muted">
                                    {{ $company->job_title ?? 'N/A' }} •
                                    Package:
                                    {{ $company->package_offered ? '₹' . $company->package_offered . ' LPA' : 'N/A' }}
                                </p>
                                <small class="text-success">
                                    <i class="fas fa-info-circle"></i> Final selection from shortlisted candidates
                                </small>
                            </div>
                            <div class="col-md-4 text-right">
                                <a href="{{ route('company.index', $company->id) }}"
                                    class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Back to Applications
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
                        <div class="h4 mb-0 text-primary">{{ $shortlistedStudents->count() }}</div>
                        <small class="text-muted">Shortlisted</small>
                    </div>
                    <div class="text-center">
                        <div class="h4 mb-0 text-success" id="selectedCount">
                            {{ $shortlistedStudents->where('application_status', 'selected')->count() }}
                        </div>
                        <small class="text-muted">Final Selected</small>
                    </div>
                    <div class="text-center">
                        <div class="h4 mb-0 text-warning" id="pendingCount">
                            {{ $shortlistedStudents->where('application_status', 'shortlisted')->count() }}
                        </div>
                        <small class="text-muted">Pending Selection</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Selection Options -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-check"></i> Final Selection
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Option 1: Textarea Input -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label fw-bold">Enter Roll Numbers or Email IDs</label>
                            <textarea class="form-control" id="studentData" rows="4"
                                placeholder="Enter one roll number or email ID per line. Example:
12345
john.doe@example.com
67890
jane.smith@email.com"></textarea>
                            <small class="form-text text-muted">
                                Enter one identifier per line. You can mix roll numbers and email IDs.
                            </small>
                        </div>
                    </div>

                    <!-- Option 2: Checkbox Selection Controls -->
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label fw-bold">Or Select Students from List</label>
                            <div class="d-flex align-items-center mb-2">
                                <button onclick="toggleSelectAll()" class="btn btn-outline-primary btn-sm me-2" id="selectAllBtn">
                                    <i class="fas fa-check-square"></i> Select All
                                </button>
                                <button onclick="clearSelection()" class="btn btn-outline-secondary btn-sm me-2">
                                    <i class="fas fa-times"></i> Clear All
                                </button>
                                <span class="badge bg-info" id="selectedCountBadge">0 students selected</span>
                            </div>
                            <small class="form-text text-muted">
                                Use checkboxes in the table below to select students
                            </small>
                        </div>
                    </div>
                </div>

                <!-- Single Action Button -->
                <div class="row mt-3">
                    <div class="col-12">
                        <div class="d-grid">
                            <button onclick="markAsSelected()" class="btn btn-success btn-lg" id="markSelectedBtn">
                                <i class="fas fa-check-double"></i> Mark as Selected
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Alert -->
        <div id="resultsAlert" style="display: none;"></div>

        <!-- Shortlisted Students Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center py-2">
                <h5 class="card-title mb-0">
                    Shortlisted Students
                    <span class="badge badge-primary">{{ $shortlistedStudents->count() }}</span>
                </h5>
                <div>
                    <button onclick="exportSelectedStudents()" class="btn btn-info btn-sm">
                        <i class="fas fa-download"></i> Export Selected
                    </button>
                </div>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th width="40">
                                    <input type="checkbox" id="masterCheckbox" onchange="toggleMasterCheckbox(this)">
                                </th>
                                <th width="40">#</th>
                                <th>Student</th>
                                <th>Roll Number</th>
                                <th>Email</th>
                                <th>Academic</th>
                                <th width="100">CGPA/Year</th>
                                <th width="150">Skills</th>
                                <th width="120">Status</th>
                                <th width="120">Shortlisted</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shortlistedStudents as $index => $student)
                                <tr class="{{ $student->application_status === 'selected' ? 'table-success' : '' }}"
                                    data-student-id="{{ $student->student_id }}"
                                    data-roll-number="{{ $student->roll_number }}"
                                    data-email="{{ $student->student_email }}">
                                    <td class="text-center">
                                        @if ($student->application_status !== 'selected')
                                            <input type="checkbox" class="student-checkbox" 
                                                onchange="updateSelectionCount()"
                                                value="{{ $student->student_id }}"
                                                data-roll-number="{{ $student->roll_number }}"
                                                data-email="{{ $student->student_email }}">
                                        @else
                                            <i class="fas fa-check text-success"></i>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ $index + 1 }}</td>

                                    <!-- Student Info -->
                                    <td>
                                        <div class="fw-bold text-dark">{{ $student->student_name }}</div>
                                    </td>

                                    <!-- Roll Number -->
                                    <td>
                                        <code>{{ $student->roll_number }}</code>
                                    </td>

                                    <!-- Email -->
                                    <td>
                                        <small>{{ $student->student_email }}</small>
                                    </td>

                                    <!-- Academic -->
                                    <td>
                                        <small
                                            class="d-block fw-medium">{{ Str::limit($student->college_name, 20) }}</small>
                                        <small class="text-muted">{{ $student->course_name }} -
                                            {{ $student->department_name }}</small>
                                    </td>

                                    <!-- CGPA/Year -->
                                    <td>
                                        @if ($student->cgpa)
                                            <span class="badge bg-success d-block mb-1">CGPA: {{ $student->cgpa }}</span>
                                        @endif
                                        @if ($student->passing_year)
                                            <span class="badge bg-info d-block">YR: {{ $student->passing_year }}</span>
                                        @endif
                                    </td>

                                    <!-- Skills -->
                                    <td>
                                        @if (isset($studentSkills[$student->student_id]) && count($studentSkills[$student->student_id]) > 0)
                                            @foreach (array_slice($studentSkills[$student->student_id], 0, 2) as $skill)
                                                <span class="badge bg-primary mb-1 d-inline-block"
                                                    style="font-size: 0.7rem;">
                                                    {{ Str::limit($skill, 15) }}
                                                </span>
                                            @endforeach
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>

                                    <!-- Status -->
                                    <td>
                                        @if ($student->application_status === 'selected')
                                            <span class="badge bg-success">
                                                <i class="fas fa-check"></i> Selected
                                            </span>
                                        @else
                                            <span class="badge bg-info">Shortlisted</span>
                                        @endif
                                    </td>

                                    <!-- Shortlisted Date -->
                                    <td>
                                        @if ($student->shortlisted_at)
                                            <small
                                                class="d-block">{{ \Carbon\Carbon::parse($student->shortlisted_at)->format('d M Y') }}</small>
                                            <small
                                                class="text-muted">{{ \Carbon\Carbon::parse($student->shortlisted_at)->format('H:i') }}</small>
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

    <script>
        // Selection management functions
        function toggleMasterCheckbox(checkbox) {
            const checkboxes = document.querySelectorAll('.student-checkbox');
            checkboxes.forEach(cb => {
                cb.checked = checkbox.checked;
            });
            updateSelectionCount();
        }

        function toggleSelectAll() {
            const masterCheckbox = document.getElementById('masterCheckbox');
            const checkboxes = document.querySelectorAll('.student-checkbox');
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            checkboxes.forEach(cb => {
                cb.checked = !allChecked;
            });
            masterCheckbox.checked = !allChecked;
            updateSelectionCount();
        }

        function clearSelection() {
            const checkboxes = document.querySelectorAll('.student-checkbox');
            const masterCheckbox = document.getElementById('masterCheckbox');
            
            checkboxes.forEach(cb => {
                cb.checked = false;
            });
            masterCheckbox.checked = false;
            updateSelectionCount();
        }

        function updateSelectionCount() {
            const selectedCheckboxes = document.querySelectorAll('.student-checkbox:checked');
            const selectedCount = selectedCheckboxes.length;
            
            document.getElementById('selectedCountBadge').textContent = selectedCount + ' students selected';
            
            // Update master checkbox state
            const totalCheckboxes = document.querySelectorAll('.student-checkbox').length;
            const masterCheckbox = document.getElementById('masterCheckbox');
            masterCheckbox.checked = selectedCount === totalCheckboxes && totalCheckboxes > 0;
            masterCheckbox.indeterminate = selectedCount > 0 && selectedCount < totalCheckboxes;
        }

        // Main function to mark students as selected
       // Main function to mark students as selected
function markAsSelected() {
    let studentData = '';

    // First check if any checkboxes are selected
    const selectedCheckboxes = document.querySelectorAll('.student-checkbox:checked');
    
    if (selectedCheckboxes.length > 0) {
        // Use checkbox selection
        selectedCheckboxes.forEach(checkbox => {
            // Prefer roll number, fall back to email
            const identifier = checkbox.dataset.rollNumber || checkbox.dataset.email;
            if (identifier) {
                studentData += identifier + '\n';
            }
        });
    } else {
        // Use textarea input (允许为空)
        studentData = document.getElementById('studentData').value.trim();
    }
    
    const totalShortlisted = {{ $shortlistedStudents->count() }};
    const selectedCount = selectedCheckboxes.length > 0 ? selectedCheckboxes.length : 
                         studentData ? studentData.split('\n').filter(line => line.trim()).length : 0;
    
    let confirmMessage;
    
    if (selectedCount === 0) {
        // 没有选择任何学生：拒绝所有
        confirmMessage = `No students selected. This will REJECT ALL ${totalShortlisted} shortlisted students. Are you sure?`;
    } else {
        // 有选择学生：选中指定学生，拒绝其余
        confirmMessage = `Mark ${selectedCount} student(s) as selected and REJECT the remaining ${totalShortlisted - selectedCount} student(s). Continue?`;
    }
    
    if (!confirm(confirmMessage)) {
        return;
    }

    submitForm(studentData);
}

// Common form submission function
function submitForm(studentData) {
    // Create a form and submit it
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route('company.select.students') }}';

    // Add CSRF token
    const csrfToken = document.createElement('input');
    csrfToken.type = 'hidden';
    csrfToken.name = '_token';
    csrfToken.value = '{{ csrf_token() }}';
    form.appendChild(csrfToken);

    // Add student data (可以是空的)
    const studentInput = document.createElement('input');
    studentInput.type = 'hidden';
    studentInput.name = 'student_data';
    studentInput.value = studentData;
    form.appendChild(studentInput);

    // Add placement drive ID
    const driveInput = document.createElement('input');
    driveInput.type = 'hidden';
    driveInput.name = 'placement_drive_id';
    driveInput.value = '{{ $company->id }}';
    form.appendChild(driveInput);

    // Add to document and submit
    document.body.appendChild(form);
    form.submit();

    // Show loading state while form submits
    const btn = document.getElementById('markSelectedBtn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
    btn.disabled = true;

    // Re-enable button after 5 seconds in case submission fails
    setTimeout(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    }, 5000);
}
        function showResultsAlert(type, message) {
            const alertDiv = document.getElementById('resultsAlert');
            alertDiv.innerHTML = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        `;
            alertDiv.style.display = 'block';
        }

        function exportSelectedStudents() {
            const selectedStudents = @json($shortlistedStudents->where('application_status', 'selected'));

            if (selectedStudents.length === 0) {
                alert('No selected students to export');
                return;
            }

            let csvContent = "Roll Number,Name,Email,College,Course,Department,CGPA,Passing Year,Package\n";

            selectedStudents.forEach(student => {
                const escapeCSV = (str) => `"${String(str).replace(/"/g, '""')}"`;

                csvContent += [
                    escapeCSV(student.roll_number),
                    escapeCSV(student.student_name),
                    escapeCSV(student.student_email),
                    escapeCSV(student.college_name),
                    escapeCSV(student.course_name),
                    escapeCSV(student.department_name),
                    escapeCSV(student.cgpa),
                    escapeCSV(student.passing_year),
                    escapeCSV(student.package_offered + ' LPA')
                ].join(',') + '\n';
            });

            const blob = new Blob([csvContent], {
                type: 'text/csv;charset=utf-8;'
            });
            const link = document.createElement("a");
            const url = URL.createObjectURL(blob);

            link.setAttribute("href", url);
            link.setAttribute("download", "selected_students_{{ $company->company_name }}_{{ date('Y-m-d') }}.csv");
            link.style.visibility = 'hidden';

            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Initialize selection count on page load
        document.addEventListener('DOMContentLoaded', function() {
            updateSelectionCount();
        });
    </script>
@endsection