@extends('dashboardLayouts.base')

@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Shortlisted Students</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <style>
            :root {
                --primary-color: #3498db;
                --secondary-color: #2c3e50;
                --success-color: #2ecc71;
                --danger-color: #e74c3c;
                --light-bg: #f8f9fa;
                --border-color: #e0e0e0;
            }

            body {
                background-color: #f5f7fa;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }

            .dashboard-header {
                background: linear-gradient(135deg, #f1e153, #1a2530);
                color: white;
                padding: 1.5rem 0;
                margin-bottom: 2rem;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .card {
                border: none;
                border-radius: 10px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
                transition: transform 0.3s ease;
                margin-bottom: 1.5rem;
            }

            .card:hover {
                transform: translateY(-5px);
            }

            .card-header {
                background-color: white;
                border-bottom: 1px solid var(--border-color);
                padding: 1.25rem 1.5rem;
                border-radius: 10px 10px 0 0 !important;
            }

            .table-responsive {
                border-radius: 0 0 10px 10px;
            }

            .table thead th {
                background-color: var(--light-bg);
                border-bottom: 2px solid var(--border-color);
                font-weight: 600;
                color: var(--secondary-color);
                padding: 1rem 0.75rem;
            }

            .table tbody td {
                padding: 1rem 0.75rem;
                vertical-align: middle;
            }

            .student-name {
                font-weight: 600;
                color: var(--secondary-color);
            }

            .status-badge {
                padding: 0.35rem 0.75rem;
                border-radius: 50px;
                font-size: 0.8rem;
                font-weight: 500;
            }

            .status-shortlisted {
                background-color: rgba(46, 204, 113, 0.15);
                color: var(--success-color);
            }

            .skills-container {
                display: flex;
                flex-wrap: wrap;
                gap: 0.4rem;
            }

            .skill-tag {
                background-color: rgba(52, 152, 219, 0.1);
                color: var(--primary-color);
                padding: 0.25rem 0.6rem;
                border-radius: 20px;
                font-size: 0.75rem;
                font-weight: 500;
            }

            .action-buttons .btn {
                padding: 0.35rem 0.75rem;
                font-size: 0.85rem;
                border-radius: 5px;
                margin: 2px;
            }

            .company-info {
                background: linear-gradient(135deg, #246924 0%, #dbd449 100%);
                color: white;
                border-radius: 10px;
                padding: 1.5rem;
                margin-bottom: 2rem;
            }

            .stats-card {
                text-align: center;
                padding: 1.5rem;
            }

            .stats-number {
                font-size: 2rem;
                font-weight: 700;
                margin-bottom: 0.5rem;
            }

            .stats-label {
                font-size: 0.9rem;
                color: #fff;
            }

            .empty-state {
                text-align: center;
                padding: 3rem 1rem;
                color: #6c757d;
            }

            .empty-state i {
                font-size: 4rem;
                margin-bottom: 1rem;
                color: #dee2e6;
            }

            .checkbox-select {
                width: 20px;
                height: 20px;
                cursor: pointer;
            }

            .bulk-actions {
                background-color: #f8f9fa;
                padding: 1rem;
                border-radius: 8px;
                margin-bottom: 1rem;
                display: none;
            }

            .bulk-actions.show {
                display: flex;
                align-items: center;
                gap: 10px;
            }

            /* Print Styles */
            @media print {
                body * {
                    visibility: hidden;
                }

                .print-section,
                .print-section * {
                    visibility: visible;
                }

                .print-section {
                    position: absolute;
                    left: 0;
                    top: 0;
                    width: 100%;
                }

                .no-print {
                    display: none !important;
                }

                .card {
                    box-shadow: none !important;
                    border: 1px solid #ddd !important;
                }

                .btn {
                    display: none !important;
                }

                .bulk-actions {
                    display: none !important;
                }

                .checkbox-select {
                    display: none !important;
                }
            }
        </style>
    </head>

    <body>

        <div class="container print-section pt-4">
            <!-- Company Info -->
            <div class="company-info">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="h4 mb-2">{{ $company->company_name ?? 'N/A' }}</h2>
                        <p class="mb-1">{{ $company->job_title ?? '' }} | Package: {{ $company->package_offered ?? '-' }}
                            LPA
                        </p>
                        <p class="mb-0"> Date:
                            {{ $company->created_at ? \Carbon\Carbon::parse($company->created_at)->format('d M Y') : '' }}
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <div class="stats-card text-white">
                            <div class="stats-number">{{ count($shortlistedStudents) }}</div>
                            <div class="stats-label">Shortlisted Students</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bulk Actions -->
            <div class="bulk-actions" id="bulkActions">
                <div class="d-flex align-items-center">
                    <span id="selectedCount" class="me-3 fw-bold">0 students selected</span>
                    <button class="btn btn-sm btn-danger" id="rejectSelectedBtn">
                        <i class="fas fa-times-circle me-1"></i> Reject Selected
                    </button>
                    <button class="btn btn-sm btn-outline-secondary" id="clearSelectionBtn">
                        <i class="fas fa-times me-1"></i> Clear Selection
                    </button>
                </div>
            </div>

            <!-- Students Table -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="mb-0">Shortlisted Students List</h5>
                        <small class="text-muted">Select students to perform bulk actions</small>
                    </div>
                    <div class="no-print">
                        <button class="btn btn-sm btn-outline-success me-1" id="exportExcelBtn">
                            <i class="fas fa-file-excel me-1"></i> Export Excel
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" id="printBtn">
                            <i class="fas fa-print me-1"></i> Print
                        </button>
                    </div>
                </div>

                <div class="table-responsive">
                    @if ($shortlistedStudents->isEmpty())
                        <div class="empty-state">
                            <i class="fas fa-user-slash"></i>
                            <p>No shortlisted students found for this drive.</p>
                        </div>
                    @else
                        <table class="table table-hover mb-0" id="studentsTable">
                            <thead>
                                <tr>
                                    <th width="50" class="no-print">
                                        <input type="checkbox" id="selectAll" class="checkbox-select">
                                    </th>
                                    <th>#</th>
                                    <th>Student Details</th>
                                    <th>Academic Info</th>
                                    <th>Skills</th>
                                    <th>Status</th>
                                    <th class="no-print">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($shortlistedStudents as $index => $student)
                                    <tr>
                                        <td class="no-print">
                                            <input type="checkbox" class="checkbox-select student-checkbox" 
                                                   value="{{ $student->student_id }}"
                                                   data-student-name="{{ $student->student_name }}">
                                        </td>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="student-name">{{ $student->student_name }}</div>
                                            <div class="text-muted small">{{ $student->student_email }}</div>
                                            <div class="text-muted small">Roll No: {{ $student->roll_number }}</div>
                                        </td>
                                        <td>
                                            <div>{{ $student->college_name ?? 'N/A' }}</div>
                                            <div class="text-muted small">{{ $student->course_name ?? '' }}
                                                {{ $student->department_name ? ' - ' . $student->department_name : '' }}
                                            </div>
                                            <div class="text-muted small">{{ $student->passing_year }} | CGPA:
                                                {{ $student->cgpa }}</div>
                                        </td>
                                        <td>
                                            <div class="skills-container">
                                                @if (isset($studentSkills[$student->student_id]))
                                                    @foreach ($studentSkills[$student->student_id] as $skill)
                                                        <span class="skill-tag">{{ $skill }}</span>
                                                    @endforeach
                                                @else
                                                    <span class="text-muted small">No skills added</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <span class="status-badge status-shortlisted"><i
                                                    class="fas fa-check-circle me-1"></i> Shortlisted</span>
                                            <div class="text-muted small mt-1">
                                                {{ \Carbon\Carbon::parse($student->shortlisted_at)->format('d M Y') }}
                                            </div>
                                        </td>
                                        <td class="no-print">
                                            <div class="action-buttons">
                                                <a href="{{ route('company.user.profile', $student->student_id) }}"
                                                    class="btn btn-sm btn-outline-info mb-1">
                                                    <i class="fas fa-user me-1"></i> Profile
                                                </a>
                                                @if ($student->resume_path)
                                                    <a href="{{ asset('storage/' . $student->resume_path) }}"
                                                        class="btn btn-sm btn-outline-success mb-1" target="_blank">
                                                        <i class="fas fa-eye me-1"></i> Resume
                                                    </a>
                                                @endif
                                                <button class="btn btn-sm btn-outline-danger mb-1 reject-single-btn" 
                                                        data-student-id="{{ $student->student_id }}"
                                                        data-student-name="{{ $student->student_name }}">
                                                    <i class="fas fa-times-circle me-1"></i> Reject
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>

        <!-- Rejection Modal -->
        <div class="modal fade" id="rejectionModal" tabindex="-1" aria-labelledby="rejectionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectionModalLabel">Reject Student(s)</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="rejectForm" method="POST" action="{{ route('company.reject.shortlisted') }}">
                        @csrf
                        <div class="modal-body">
                            <input type="hidden" name="placement_drive_id" value="{{ $company->id }}">
                            <input type="hidden" name="student_ids" id="studentIdsInput">
                            
                            <div class="mb-3">
                                <label for="rejection_reason" class="form-label">Reason for Rejection <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="rejection_reason" name="rejection_reason" 
                                          rows="3" placeholder="Please provide the reason for rejection..." 
                                          required></textarea>
                                <div class="form-text">This will be sent to the student(s).</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="feedback" class="form-label">Feedback (Optional)</label>
                                <textarea class="form-control" id="feedback" name="feedback" 
                                          rows="3" placeholder="Provide constructive feedback for improvement..."></textarea>
                                <div class="form-text">This will help the student(s) improve in future applications.</div>
                            </div>
                            
                            <div class="alert alert-warning">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <strong>Important:</strong> This action will mark the selected student(s) as rejected and send them an email notification. This action cannot be undone.
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-times-circle me-1"></i> Confirm Rejection
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize variables
                const bulkActions = document.getElementById('bulkActions');
                const selectAllCheckbox = document.getElementById('selectAll');
                const studentCheckboxes = document.querySelectorAll('.student-checkbox');
                const selectedCount = document.getElementById('selectedCount');
                const rejectSelectedBtn = document.getElementById('rejectSelectedBtn');
                const clearSelectionBtn = document.getElementById('clearSelectionBtn');
                const studentIdsInput = document.getElementById('studentIdsInput');
                const rejectionModal = new bootstrap.Modal(document.getElementById('rejectionModal'));
                const rejectForm = document.getElementById('rejectForm');
                const singleRejectButtons = document.querySelectorAll('.reject-single-btn');

                // Toggle bulk actions panel
                function toggleBulkActions() {
                    const checkedCount = document.querySelectorAll('.student-checkbox:checked').length;
                    if (checkedCount > 0) {
                        bulkActions.classList.add('show');
                        selectedCount.textContent = `${checkedCount} student(s) selected`;
                    } else {
                        bulkActions.classList.remove('show');
                    }
                }

                // Select all functionality
                selectAllCheckbox.addEventListener('change', function() {
                    studentCheckboxes.forEach(checkbox => {
                        checkbox.checked = this.checked;
                    });
                    toggleBulkActions();
                });

                // Individual checkbox change
                studentCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        // Update select all checkbox state
                        const allChecked = Array.from(studentCheckboxes).every(cb => cb.checked);
                        const someChecked = Array.from(studentCheckboxes).some(cb => cb.checked);
                        
                        selectAllCheckbox.checked = allChecked;
                        selectAllCheckbox.indeterminate = someChecked && !allChecked;
                        
                        toggleBulkActions();
                    });
                });

                // Clear selection
                clearSelectionBtn.addEventListener('click', function() {
                    studentCheckboxes.forEach(checkbox => {
                        checkbox.checked = false;
                    });
                    selectAllCheckbox.checked = false;
                    selectAllCheckbox.indeterminate = false;
                    toggleBulkActions();
                });

                // Bulk reject button click
                rejectSelectedBtn.addEventListener('click', function() {
                    const selectedCheckboxes = document.querySelectorAll('.student-checkbox:checked');
                    
                    if (selectedCheckboxes.length === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'No Selection',
                            text: 'Please select at least one student to reject.',
                            confirmButtonColor: '#3085d6',
                        });
                        return;
                    }

                    const studentIds = Array.from(selectedCheckboxes).map(cb => cb.value);
                    const studentNames = Array.from(selectedCheckboxes).map(cb => cb.dataset.studentName);
                    
                    studentIdsInput.value = JSON.stringify(studentIds);
                    
                    // Show modal with confirmation
                    Swal.fire({
                        title: 'Confirm Rejection',
                        html: `You are about to reject <strong>${studentIds.length} student(s)</strong>:<br><br>
                               <div style="max-height: 150px; overflow-y: auto; text-align: left; padding: 10px; background: #f8f9fa; border-radius: 5px;">
                                   ${studentNames.map(name => `• ${name}`).join('<br>')}
                               </div>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Yes, Reject Them',
                        cancelButtonText: 'Cancel',
                        width: '500px'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Show the rejection modal for reason input
                            rejectionModal.show();
                        }
                    });
                });

                // Single reject button click
                singleRejectButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const studentId = this.dataset.studentId;
                        const studentName = this.dataset.studentName;
                        
                        Swal.fire({
                            title: 'Reject Student',
                            html: `Are you sure you want to reject <strong>${studentName}</strong>?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#d33',
                            cancelButtonColor: '#3085d6',
                            confirmButtonText: 'Yes, Reject',
                            cancelButtonText: 'Cancel'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                studentIdsInput.value = JSON.stringify([studentId]);
                                rejectionModal.show();
                            }
                        });
                    });
                });

                // Form submission
                rejectForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    const formData = new FormData(this);
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.innerHTML;
                    
                    // Show loading
                    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Processing...';
                    submitBtn.disabled = true;
                    
                    // Send AJAX request
                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success!',
                                text: data.message,
                                confirmButtonColor: '#3085d6',
                            }).then(() => {
                                // Reload the page to reflect changes
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: data.message || 'An error occurred.',
                                confirmButtonColor: '#d33',
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: 'An error occurred while processing your request.',
                            confirmButtonColor: '#d33',
                        });
                    })
                    .finally(() => {
                        // Reset button
                        submitBtn.innerHTML = originalText;
                        submitBtn.disabled = false;
                        rejectionModal.hide();
                    });
                });

                // Export to Excel function
                document.getElementById('exportExcelBtn').addEventListener('click', exportToExcel);
                
                // Print function
                document.getElementById('printBtn').addEventListener('click', printReport);

                function exportToExcel() {
                    if ({{ count($shortlistedStudents) }} === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'No Data',
                            text: 'No data to export!',
                            confirmButtonColor: '#3085d6',
                        });
                        return;
                    }

                    const button = this;
                    const originalText = button.innerHTML;
                    button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Exporting...';
                    button.disabled = true;

                    try {
                        const excelData = [
                            ['#', 'Student Name', 'Email', 'Roll Number', 'College', 'Course', 'Department',
                                'Passing Year', 'CGPA', 'Skills', 'Status', 'Shortlisted Date'
                            ]
                        ];

                        @foreach ($shortlistedStudents as $index => $student)
                            @php
                                $skills = isset($studentSkills[$student->student_id]) ? $studentSkills[$student->student_id] : [];
                            @endphp
                            excelData.push([
                                {{ $index + 1 }},
                                '{{ addslashes($student->student_name) }}',
                                '{{ addslashes($student->student_email) }}',
                                '{{ addslashes($student->roll_number) }}',
                                '{{ addslashes($student->college_name ?? 'N/A') }}',
                                '{{ addslashes($student->course_name ?? '') }}',
                                '{{ addslashes($student->department_name ?? '') }}',
                                '{{ $student->passing_year }}',
                                '{{ $student->cgpa }}',
                                '{{ implode(', ', $skills) }}',
                                'Shortlisted',
                                '{{ \Carbon\Carbon::parse($student->shortlisted_at)->format('d M Y') }}'
                            ]);
                        @endforeach

                        const wb = XLSX.utils.book_new();
                        const ws = XLSX.utils.aoa_to_sheet(excelData);
                        XLSX.utils.book_append_sheet(wb, ws, 'Shortlisted Students');

                        const companyName = '{{ addslashes($company->company_name ?? 'Company') }}';
                        const fileName = `${companyName.replace(/\s+/g, '_')}_Shortlisted_Students_${new Date().toISOString().split('T')[0]}.xlsx`;
                        XLSX.writeFile(wb, fileName);

                    } catch (error) {
                        console.error('Export error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Export Error',
                            text: 'Error exporting to Excel: ' + error.message,
                            confirmButtonColor: '#d33',
                        });
                    } finally {
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }
                }

                function printReport() {
                    if ({{ count($shortlistedStudents) }} === 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'No Data',
                            text: 'No data to print!',
                            confirmButtonColor: '#3085d6',
                        });
                        return;
                    }

                    const button = this;
                    const originalText = button.innerHTML;
                    button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Printing...';
                    button.disabled = true;

                    try {
                        const companyName = '{{ addslashes($company->company_name ?? 'Company') }}';
                        const driveTitle = '{{ addslashes($company->job_title ?? '') }}';
                        const package = '{{ $company->package_offered ?? '-' }}';

                        let printContent = `
                            <!DOCTYPE html>
                            <html>
                            <head>
                                <title>Shortlisted Students - ${companyName}</title>
                                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
                                <style>
                                    body { 
                                        font-family: Arial, sans-serif; 
                                        margin: 20px; 
                                        background: white;
                                    }
                                    .print-header { 
                                        text-align: center; 
                                        margin-bottom: 30px; 
                                        border-bottom: 2px solid #333; 
                                        padding-bottom: 20px; 
                                    }
                                    .company-name { 
                                        font-size: 24px; 
                                        font-weight: bold; 
                                        color: #2c3e50; 
                                    }
                                    .drive-info { 
                                        font-size: 16px; 
                                        color: #666; 
                                        margin: 10px 0; 
                                    }
                                    .print-date { 
                                        font-size: 14px; 
                                        color: #999; 
                                    }
                                    .table { 
                                        width: 100%; 
                                        border-collapse: collapse; 
                                        margin-top: 20px;
                                        font-size: 12px;
                                    }
                                    .table th { 
                                        background-color: #f8f9fa; 
                                        border: 1px solid #dee2e6; 
                                        padding: 8px; 
                                        text-align: left; 
                                        font-weight: bold; 
                                    }
                                    .table td { 
                                        border: 1px solid #dee2e6; 
                                        padding: 8px; 
                                        vertical-align: top;
                                    }
                                    .student-name { 
                                        font-weight: bold; 
                                        font-size: 13px;
                                    }
                                    .student-details {
                                        font-size: 11px;
                                        color: #666;
                                    }
                                    .status-badge { 
                                        background-color: #d4edda; 
                                        color: #155724; 
                                        padding: 4px 8px; 
                                        border-radius: 4px; 
                                        font-size: 11px; 
                                        display: inline-block;
                                    }
                                    @media print {
                                        body { margin: 0; }
                                        .print-header { margin-top: 0; }
                                    }
                                </style>
                            </head>
                            <body>
                                <div class="print-header">
                                    <div class="company-name">${companyName}</div>
                                    <div class="drive-info">${driveTitle} | Package: ₹${package}</div>
                                    <div class="drive-info">Shortlisted Students Report</div>
                                </div>
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th width="40">#</th>
                                            <th width="200">Student Details</th>
                                            <th width="180">Academic Info</th>
                                            <th width="150">Skills</th>
                                            <th width="100">Status</th>
                                            <th width="100">Shortlisted Date</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                        `;

                        @foreach ($shortlistedStudents as $index => $student)
                            @php
                                $skills = isset($studentSkills[$student->student_id]) ? implode(', ', $studentSkills[$student->student_id]) : 'No skills';
                            @endphp
                            printContent += `
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <div class="student-name">{{ $student->student_name }}</div>
                                        <div class="student-details">{{ $student->student_email }}</div>
                                        <div class="student-details">Roll No: {{ $student->roll_number }}</div>
                                    </td>
                                    <td>
                                        <div>{{ $student->college_name ?? 'N/A' }}</div>
                                        <div class="student-details">{{ $student->course_name ?? '' }} {{ $student->department_name ? ' - ' . $student->department_name : '' }}</div>
                                        <div class="student-details">{{ $student->passing_year }} | CGPA: {{ $student->cgpa }}</div>
                                    </td>
                                    <td>
                                        <div class="student-details">{{ $skills }}</div>
                                    </td>
                                    <td>
                                        <span class="status-badge">Shortlisted</span>
                                    </td>
                                    <td>
                                        <div class="student-details">{{ \Carbon\Carbon::parse($student->shortlisted_at)->format('d M Y') }}</div>
                                    </td>
                                </tr>
                            `;
                        @endforeach

                        printContent += `
                                    </tbody>
                                </table>
                                <div style="margin-top: 30px; text-align: center; font-size: 11px; color: #999;">
                                    Generated by IKGPTU T&P System • ${new Date().toLocaleString()}
                                </div>
                            </body>
                            </html>
                        `;

                        const printWindow = window.open('', '_blank', 'width=1000,height=600');
                        printWindow.document.write(printContent);
                        printWindow.document.close();

                        printWindow.onload = function() {
                            printWindow.focus();
                            printWindow.print();
                            printWindow.onafterprint = function() {
                                printWindow.close();
                            };
                        };

                    } catch (error) {
                        console.error('Print error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Print Error',
                            text: 'Error printing: ' + error.message,
                            confirmButtonColor: '#d33',
                        });
                    } finally {
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }
                }
            });
        </script>
    </body>

    </html>
@endsection