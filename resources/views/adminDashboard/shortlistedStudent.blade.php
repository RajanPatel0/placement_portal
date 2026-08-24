@extends('dashboardLayouts.base')

@section('content')
    <!-- This section will trigger the modal -->
    <div class="notification-container">
        @if (session('success'))
            <div class="notification success" id="successNotification">
                <button type="button" class="close-btn" onclick="closeNotification('successNotification')">&times;</button>
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="notification error" id="errorNotification">
                <button type="button" class="close-btn" onclick="closeNotification('errorNotification')">&times;</button>
                <p>{{ session('error') }}</p>
            </div>
        @endif

        @if ($errors->any())
            <div class="notification error" id="errorsNotification">
                <button type="button" class="close-btn" onclick="closeNotification('errorsNotification')">&times;</button>
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


    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Shortlisted Students</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
        <style>
            :root {
                --primary-color: #3498db;
                --secondary-color: #2c3e50;
                --success-color: #2ecc71;
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

            /* Modal Animation */
            .modal.fade .modal-dialog {
                transform: translate(0, -50px);
                transition: transform 0.3s ease-out;
            }

            .modal.show .modal-dialog {
                transform: translate(0, 0);
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

            <!-- Students Table -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Shortlisted Students List</h5>
                    <div class="d-flex align-items-center gap-2 no-print">
                        <button class="btn btn-sm btn-outline-success" id="exportExcelBtn">
                            <i class="fas fa-file-excel me-1"></i> Export Excel
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" id="printBtn">
                            <i class="fas fa-print me-1"></i> Print
                        </button>

                        @if (!$shortlistedStudents->isEmpty())
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                data-bs-target="#notificationModal">
                                <i class="fas fa-bell me-1"></i> Send Notification
                            </button>
                        @endif
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
                                                <a href="{{ route('admin.user.profile', $student->student_id) }}"
                                                    class="btn btn-sm btn-outline-info mb-1">
                                                    <i class="fas fa-user me-1"></i> Profile
                                                </a>
                                                @if ($student->resume_path)
                                                    <a href="{{ asset('storage/' . $student->resume_path) }}"
                                                        class="btn btn-sm btn-outline-success mb-1" target="_blank">
                                                        <i class="fas fa-eye me-1"></i> Resume
                                                    </a>
                                                @else
                                                    <span class="text-muted small">No Resume</span>
                                                @endif
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

        <!-- Notification Modal -->
        <!-- Notification Modal -->
        <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="notificationModalLabel">
                            <i class="fas fa-bell me-2"></i>Send Notification to Students
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <form action="{{ route('admin.send.custom.notification') }}" method="POST"
                        enctype="multipart/form-data" id="notificationForm">
                        @csrf
                        <input type="hidden" name="drive_id" value="{{ $company->id }}">

                        <!-- Fix: Add hidden inputs for each user_id -->
                        @foreach ($shortlistedStudents as $student)
                            <input type="hidden" name="user_ids[]" value="{{ $student->student_id }}">
                        @endforeach

                        <div class="modal-body">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                This notification will be sent to <strong>{{ count($shortlistedStudents) }}</strong>
                                shortlisted students.
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label fw-semibold">Message <span
                                        class="text-danger">*</span></label>
                                <textarea class="form-control" id="message" name="message" rows="6"
                                    placeholder="Enter your notification message..." required></textarea>
                                <div class="form-text">
                                    <small>You can use these placeholders: <code>{student_name}</code>,
                                        <code>{company_name}</code>, <code>{job_title}</code></small>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="link" class="form-label fw-semibold">Link (Optional)</label>
                                        <input type="url" class="form-control" id="link" name="link"
                                            placeholder="https://example.com/important-info">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="attachment" class="form-label fw-semibold">Attachment
                                            (Optional)</label>
                                        <input type="file" class="form-control" id="attachment" name="attachment"
                                            accept=".pdf,.doc,.docx,.jpg,.jpeg,.png">
                                        <div class="form-text">
                                            <small>Max file size: 5MB. Supported formats: PDF, DOC, DOCX, JPG, PNG</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                <i class="fas fa-times me-1"></i> Cancel
                            </button>
                            <button type="submit" class="btn btn-primary" id="sendNotificationBtn">
                                <i class="fas fa-paper-plane me-1"></i> Send Notification
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize Bootstrap modal
                const notificationModal = new bootstrap.Modal(document.getElementById('notificationModal'));

                // Form submission handler
                const notificationForm = document.getElementById('notificationForm');
                const sendNotificationBtn = document.getElementById('sendNotificationBtn');

                if (notificationForm) {
                    notificationForm.addEventListener('submit', function(e) {
                        // Show loading state
                        const originalText = sendNotificationBtn.innerHTML;
                        sendNotificationBtn.innerHTML =
                            '<i class="fas fa-spinner fa-spin me-1"></i> Sending...';
                        sendNotificationBtn.disabled = true;

                        // Form will submit normally, the button state is just for UX
                    });
                }

                // Export to Excel function
                document.getElementById('exportExcelBtn')?.addEventListener('click', function() {
                    if ({{ count($shortlistedStudents) }} === 0) {
                        alert('No data to export!');
                        return;
                    }

                    // Show loading
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Exporting...';
                    this.disabled = true;

                    try {
                        // Prepare data for Excel
                        const excelData = [
                            ['#', 'Student Name', 'Email', 'Roll Number', 'College', 'Course', 'Department',
                                'Passing Year', 'CGPA', 'Skills', 'Status', 'Shortlisted Date'
                            ]
                        ];

                        // Add student data
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

                        // Create workbook and worksheet
                        const wb = XLSX.utils.book_new();
                        const ws = XLSX.utils.aoa_to_sheet(excelData);

                        // Add worksheet to workbook
                        XLSX.utils.book_append_sheet(wb, ws, 'Shortlisted Students');

                        // Generate Excel file and download
                        const companyName = '{{ addslashes($company->company_name ?? 'Company') }}';
                        const fileName =
                            `${companyName.replace(/\s+/g, '_')}_Shortlisted_Students_${new Date().toISOString().split('T')[0]}.xlsx`;
                        XLSX.writeFile(wb, fileName);

                    } catch (error) {
                        console.error('Export error:', error);
                        alert('Error exporting to Excel: ' + error.message);
                    } finally {
                        // Reset button
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }
                });

                // Print function
                document.getElementById('printBtn')?.addEventListener('click', function() {
                    if ({{ count($shortlistedStudents) }} === 0) {
                        alert('No data to print!');
                        return;
                    }

                    // Show loading
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Printing...';
                    this.disabled = true;

                    try {
                        // Your existing print code here...
                        window.print();
                    } catch (error) {
                        console.error('Print error:', error);
                        alert('Error printing: ' + error.message);
                    } finally {
                        // Reset button
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }
                });

                // Reset modal form when hidden
                document.getElementById('notificationModal').addEventListener('hidden.bs.modal', function() {
                    const form = document.getElementById('notificationForm');
                    if (form) {
                        form.reset();
                    }
                    // Reset button state
                    if (sendNotificationBtn) {
                        sendNotificationBtn.innerHTML =
                            '<i class="fas fa-paper-plane me-1"></i> Send Notification';
                        sendNotificationBtn.disabled = false;
                    }
                });
            });
        </script>
    </body>

    </html>
@endsection
