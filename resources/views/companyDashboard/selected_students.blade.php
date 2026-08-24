@extends('dashboardLayouts.base')

@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Selected Students</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
        <script src="https://cdn.jsdelivr.net/npm/xlsx@0.18.5/dist/xlsx.full.min.js"></script>
        <style>
            :root {
                --primary-color: #3498db;
                --secondary-color: #2c3e50;
                --success-color: #27ae60;
                --selected-color: #2ecc71;
                --light-bg: #f8f9fa;
                --border-color: #e0e0e0;
                --gold-color: #f1c40f;
            }

            body {
                background-color: #f5f7fa;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }

            .dashboard-header {
                background: linear-gradient(135deg, #27ae60, #1a2530);
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

            .status-selected {
                background-color: rgba(46, 204, 113, 0.15);
                color: var(--selected-color);
            }

            .status-offer-sent {
                background-color: rgba(241, 196, 15, 0.15);
                color: var(--gold-color);
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
                background: linear-gradient(135deg, #27ae60 0%, #1a2530 100%);
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

            .student-card {
                border-left: 4px solid var(--selected-color);
            }

            .congrats-badge {
                position: absolute;
                top:20px;
                right: 15px;
                background: linear-gradient(135deg, #f1c40f, #e67e22);
                color: white;
                padding: 5px 15px;
                border-radius: 20px;
                font-size: 0.8rem;
                font-weight: 600;
                box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
            }

            .offer-letter-btn {
                background: linear-gradient(135deg, #f1c40f, #e67e22);
                border: none;
                color: white;
                font-weight: 600;
            }

            .offer-letter-btn:hover {
                background: linear-gradient(135deg, #e67e22, #f1c40f);
                color: white;
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
                            <div class="stats-number">{{ count($selectedStudents) }}</div>
                            <div class="stats-label">Selected Students</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Students Table -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center position-relative">
                    <h5 class="mb-0">Selected Students List</h5>
                    <div class="no-print">
                        <button class="btn btn-sm btn-outline-success me-1" id="exportExcelBtn">
                            <i class="fas fa-file-excel me-1"></i> Export Excel
                        </button>
                        <button class="btn btn-sm btn-outline-secondary" id="printBtn">
                            <i class="fas fa-print me-1"></i> Print
                        </button>
                    </div>
                    <span class="congrats-badge no-print">
                        <i class="fas fa-trophy me-1"></i> Congratulations!
                    </span>
                </div>

                <div class="table-responsive">
                    @if ($selectedStudents->isEmpty())
                        <div class="empty-state">
                            <i class="fas fa-user-check"></i>
                            <p>No selected students found for this drive.</p>
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
                                @foreach ($selectedStudents as $index => $student)
                                    <tr class="student-card">
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
                                            <span class="status-badge status-selected"><i
                                                    class="fas fa-check-circle me-1"></i> Selected</span>
                                            <div class="text-muted small mt-1">
                                                {{ \Carbon\Carbon::parse($student->selected_at)->format('d M Y') }}
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


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Export to Excel function
                document.getElementById('exportExcelBtn').addEventListener('click', function() {
                    if ({{ count($selectedStudents) }} === 0) {
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
                                'Passing Year', 'CGPA', 'Skills', 'Status', 'Selected Date'
                            ]
                        ];

                        // Add student data
                        @foreach ($selectedStudents as $index => $student)
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
                                'Selected',
                                '{{ \Carbon\Carbon::parse($student->selected_at)->format('d M Y') }}'
                            ]);
                        @endforeach

                        // Create workbook and worksheet
                        const wb = XLSX.utils.book_new();
                        const ws = XLSX.utils.aoa_to_sheet(excelData);

                        // Add worksheet to workbook
                        XLSX.utils.book_append_sheet(wb, ws, 'Selected Students');

                        // Generate Excel file and download
                        const companyName = '{{ addslashes($company->company_name ?? 'Company') }}';
                        const fileName =
                            `${companyName.replace(/\s+/g, '_')}_Selected_Students_${new Date().toISOString().split('T')[0]}.xlsx`;
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
                document.getElementById('printBtn').addEventListener('click', function() {
                    if ({{ count($selectedStudents) }} === 0) {
                        alert('No data to print!');
                        return;
                    }

                    // Show loading
                    const originalText = this.innerHTML;
                    this.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Printing...';
                    this.disabled = true;

                    try {
                        // Create print content
                        const companyName = '{{ addslashes($company->company_name ?? 'Company') }}';
                        const driveTitle = '{{ addslashes($company->job_title ?? '') }}';
                        const package = '{{ $company->package_offered ?? '-' }}';

                        let printContent = `
                        <!DOCTYPE html>
                        <html>
                        <head>
                            <title>Selected Students - ${companyName}</title>
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
                                <div class="drive-info">${driveTitle} | Package: ${package} LPA</div>
                                <div class="drive-info">Selected Students Report</div>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th width="40">#</th>
                                        <th width="200">Student Details</th>
                                        <th width="180">Academic Info</th>
                                        <th width="150">Skills</th>
                                        <th width="100">Status</th>
                                        <th width="100">Selected Date</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;

                        // Add rows
                        @foreach ($selectedStudents as $index => $student)
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
                                    <span class="status-badge">Selected</span>
                                </td>
                                <td>
                                    <div class="student-details">{{ \Carbon\Carbon::parse($student->selected_at)->format('d M Y') }}</div>
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

                        // Open print window
                        const printWindow = window.open('', '_blank', 'width=1000,height=600');
                        printWindow.document.write(printContent);
                        printWindow.document.close();

                        // Wait for content to load then print
                        printWindow.onload = function() {
                            printWindow.focus();
                            printWindow.print();
                            printWindow.onafterprint = function() {
                                printWindow.close();
                            };
                        };

                    } catch (error) {
                        console.error('Print error:', error);
                        alert('Error printing: ' + error.message);
                    } finally {
                        // Reset button
                        this.innerHTML = originalText;
                        this.disabled = false;
                    }
                });




            });
        </script>
    </body>

    </html>
@endsection
