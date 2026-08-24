@extends('dashboardLayouts.base')

@section('title', $company->company_name . ' - Rejected Students')

@section('content')
    <div class="container-fluid pt-4">

        <!-- Notifications -->
        <div class="notification-container">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

        <!-- Company Header -->
        <div class="row mb-3">
            <div class="col-12">
                <div class="card bg-light">
                    <div class="card-body py-3">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="mb-1">{{ $company->company_name }} - Rejected Students</h4>
                                <p class="mb-0 text-muted">
                                    {{ $company->job_title ?? 'N/A' }} •
                                    Package: {{ $company->package_offered ? '₹' . $company->package_offered . ' LPA' : 'N/A' }}
                                </p>
                            </div>
                            <div class="col-md-4 text-right">
                                <a href="{{ route('company.final.selection') }}" class="btn btn-outline-secondary btn-sm">
                                    <i class="fas fa-arrow-left"></i> Back to Final Selection
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics -->
        <div class="row mb-4">
            <div class="col-md-3 mb-3">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <div class="h3 mb-1 text-primary">{{ $stats['total_rejected'] }}</div>
                        <small class="text-muted">Rejected Students</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <div class="h3 mb-1 text-success">{{ $stats['total_selected'] }}</div>
                        <small class="text-muted">Selected Students</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        <div class="h3 mb-1 text-warning">{{ $stats['total_shortlisted'] }}</div>
                        <small class="text-muted">Still Shortlisted</small>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <div class="card bg-light">
                    <div class="card-body text-center">
                        @php
                            $total = $stats['total_rejected'] + $stats['total_selected'] + $stats['total_shortlisted'];
                            $rejectionRate = $total > 0 ? round(($stats['total_rejected'] / $total) * 100, 1) : 0;
                        @endphp
                        <div class="h3 mb-1 text-danger">{{ $rejectionRate }}%</div>
                        <small class="text-muted">Rejection Rate</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Rejected Students Table -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="fas fa-user-times text-danger"></i> Rejected Students List
                    <span class="badge bg-danger">{{ $rejectedStudents->count() }}</span>
                </h5>
                <div>
                    <button onclick="exportRejectedStudents()" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-download"></i> Export CSV
                    </button>
                </div>
            </div>

            <div class="card-body p-0">
                @if ($rejectedStudents->isEmpty())
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <h5>No Rejected Students</h5>
                        <p class="text-muted">All shortlisted students are still pending or have been selected.</p>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th width="50">#</th>
                                    <th>Student Details</th>
                                    <th>Academic Info</th>
                                    <th>Skills</th>
                                    <th width="200">Rejection Details</th>
                             
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rejectedStudents as $index => $student)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        
                                        <!-- Student Info -->
                                        <td>
                                            <div class="fw-bold">{{ $student->student_name }}</div>
                                            <div class="small text-muted">{{ $student->student_email }}</div>
                                            <div class="small">Roll: {{ $student->roll_number }}</div>
                                        </td>
                                        
                                        <!-- Academic Info -->
                                        <td>
                                            <div class="small">{{ $student->college_name ?? 'N/A' }}</div>
                                            <div class="small text-muted">{{ $student->course_name ?? '' }} 
                                                {{ $student->department_name ? ' - ' . $student->department_name : '' }}</div>
                                            <div class="small">Year: {{ $student->passing_year }} | CGPA: {{ $student->cgpa }}</div>
                                        </td>
                                        
                                        <!-- Skills -->
                                        <td>
                                            @if (isset($studentSkills[$student->student_id]) && count($studentSkills[$student->student_id]) > 0)
                                                <div class="d-flex flex-wrap gap-1">
                                                    @foreach (array_slice($studentSkills[$student->student_id], 0, 3) as $skill)
                                                        <span class="badge bg-primary" style="font-size: 0.7rem;">
                                                            {{ Str::limit($skill, 15) }}
                                                        </span>
                                                    @endforeach
                                                    @if (count($studentSkills[$student->student_id]) > 3)
                                                        <span class="badge bg-secondary" style="font-size: 0.7rem;">
                                                            +{{ count($studentSkills[$student->student_id]) - 3 }} more
                                                        </span>
                                                    @endif
                                                </div>
                                            @else
                                                <span class="text-muted small">No skills added</span>
                                            @endif
                                        </td>
                                        
                                        <!-- Rejection Details -->
                                        <td>
                                            @php
                                                $details = $rejectionDetails[$student->student_id] ?? [];
                                            @endphp
                                            <div class="small">
                                                <strong>Reason:</strong> {{ $details['reason'] ?? 'Not specified' }}
                                            </div>
                                            @if (!empty($details['feedback']))
                                                <div class="small text-muted mt-1">
                                                    <strong>Feedback:</strong> {{ Str::limit($details['feedback'], 50) }}
                                                </div>
                                            @endif
                                            <div class="small text-muted mt-1">
                                                <strong>Stage:</strong> {{ $details['stage'] ?? 'Final Selection' }}
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
    </div>

    <script>
        function exportRejectedStudents() {
            if ({{ $rejectedStudents->count() }} === 0) {
                alert('No rejected students to export');
                return;
            }

            let csvContent = "Roll Number,Name,Email,College,Course,Department,CGPA,Passing Year,Rejection Reason,Rejection Feedback,Rejected Date\n";

            @foreach ($rejectedStudents as $student)
                @php
                    $details = $rejectionDetails[$student->student_id] ?? [];
                @endphp
                const escapeCSV = (str) => `"${String(str || '').replace(/"/g, '""')}"`;
                
                csvContent += [
                    escapeCSV('{{ $student->roll_number }}'),
                    escapeCSV('{{ $student->student_name }}'),
                    escapeCSV('{{ $student->student_email }}'),
                    escapeCSV('{{ $student->college_name }}'),
                    escapeCSV('{{ $student->course_name }}'),
                    escapeCSV('{{ $student->department_name }}'),
                    escapeCSV('{{ $student->cgpa }}'),
                    escapeCSV('{{ $student->passing_year }}'),
                    escapeCSV($details['reason'] ?? ''),
                    escapeCSV($details['feedback'] ?? ''),
                
                ].join(',') + '\n';
            @endforeach

            const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
            const link = document.createElement("a");
            const url = URL.createObjectURL(blob);

            link.setAttribute("href", url);
            link.setAttribute("download", "rejected_students_{{ $company->company_name }}_{{ date('Y-m-d') }}.csv");
            link.style.visibility = 'hidden';

            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>
@endsection