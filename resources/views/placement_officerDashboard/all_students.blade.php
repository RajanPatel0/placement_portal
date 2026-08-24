@extends('dashboardLayouts.base')

@section('title', 'Students Management')

@section('content')
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <!-- Include Chart.js for statistics -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#137fec",
                        "secondary": "#8b5cf6",
                        "success": "#10b981",
                        "warning": "#f59e0b",
                        "danger": "#ef4444",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.5rem",
                        "lg": "0.75rem",
                        "xl": "1rem",
                        "2xl": "1.5rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>

    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }

        .active-nav .material-symbols-outlined {
            font-variation-settings: 'FILL' 1;
        }

        /* Modern Card Styling */
        .modern-card {
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .dark .modern-card {
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .modern-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        /* Glass Effect */
        .glass-effect {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .dark .glass-effect {
            background: rgba(0, 0, 0, 0.2);
        }

        /* Status Badges */
        .status-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
        }

        /* Avatar Initials */
        .avatar-initials {
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            font-weight: 600;
            font-size: 0.875rem;
        }

        /* Table Row Animation */
        .table-row {
            transition: all 0.2s ease;
        }

        .table-row:hover {
            background: linear-gradient(90deg, rgba(19, 127, 236, 0.05) 0%, rgba(19, 127, 236, 0.02) 100%);
        }

        .dark .table-row:hover {
            background: linear-gradient(90deg, rgba(19, 127, 236, 0.1) 0%, rgba(19, 127, 236, 0.05) 100%);
        }

        /* FIXED SCROLLBAR - Apply only to table wrapper */
        .table-wrapper {
            overflow-x: auto;
            position: relative;
        }

        /* Custom scrollbar for table wrapper only */
        .table-wrapper::-webkit-scrollbar {
            height: 8px;
            width: 8px;
        }

        .table-wrapper::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.05);
            border-radius: 4px;
        }

        .table-wrapper::-webkit-scrollbar-thumb {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 4px;
        }

        .table-wrapper::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 0, 0, 0.3);
        }

        .dark .table-wrapper::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }

        .dark .table-wrapper::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
        }

        .dark .table-wrapper::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.3);
        }

        /* Animation Classes */
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        body {
            min-height: 100vh;
            /* Remove overflow-x: hidden to prevent layout shifts */
        }

        /* Ensure table has proper width */
        table {
            min-width: 1000px;
            /* Ensure table is wider than container for horizontal scroll */
        }

        /* Fixed column widths to prevent layout shifting */
        th.col-student,
        td:first-child {
            min-width: 250px;
            max-width: 250px;
        }

        th.col-course,
        td:nth-child(2) {
            min-width: 250px;
            max-width: 250px;
        }

        th.col-academic,
        td:nth-child(3) {
            min-width: 200px;
            max-width: 200px;
        }

        th.col-status,
        td:nth-child(4) {
            min-width: 150px;
            max-width: 150px;
        }

        th.col-actions,
        td:nth-child(5) {
            min-width: 150px;
            max-width: 150px;
        }
    </style>

    <!-- Main Content -->
    <main class="flex-grow pb-24 px-4 lg:px-6 fade-in">
        <!-- Header Section -->
        <div class="mb-8 pt-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>

                    @if ($college)
                        <div class="flex items-center gap-2 mt-3">
                            <span class="material-symbols-outlined text-slate-400 text-sm">location_on</span>
                            <span class="text-sm text-slate-600 dark:text-slate-400">
                                College: <span
                                    class="font-medium text-slate-900 dark:text-slate-50">{{ $college->name }}</span>
                            </span>
                        </div>
                    @endif
                </div>

            </div>
        </div>

        <!-- Stats Overview Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Students Card -->
            <div
                class="modern-card rounded-2xl p-6 bg-gradient-to-br from-slate-50 to-white dark:from-slate-800 dark:to-slate-900">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 rounded-xl bg-blue-100 dark:bg-blue-900/30 shadow-sm">
                                <span class="material-symbols-outlined text-blue-600 dark:text-blue-400">
                                    groups
                                </span>
                            </div>
                            <span class="text-blue-600 dark:text-blue-400 text-sm font-medium">Total Students</span>
                        </div>
                        <p class="text-4xl font-bold text-slate-900 dark:text-slate-50 mb-2">
                            {{ $totalStudents }}
                        </p>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Registered in college</p>
                    </div>
                </div>
            </div>

            <!-- Placed Students Card -->
            <div
                class="modern-card rounded-2xl p-6 bg-gradient-to-br from-green-50 to-white dark:from-green-900/20 dark:to-slate-900">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 rounded-xl bg-green-100 dark:bg-green-900/30 shadow-sm">
                                <span class="material-symbols-outlined text-green-600 dark:text-green-400">
                                    check_circle
                                </span>
                            </div>
                            <span class="text-green-600 dark:text-green-400 text-sm font-medium">Placed Students</span>
                        </div>
                        <p class="text-4xl font-bold text-slate-900 dark:text-slate-50 mb-2">
                            {{ $placedStudents }}
                        </p>
                        <p class="text-sm text-slate-600 dark:text-slate-400">
                            @if ($totalStudents > 0)
                                {{ number_format(($placedStudents / $totalStudents) * 100, 1) }}% placement rate
                            @else
                                0% placement rate
                            @endif
                        </p>
                    </div>
                    <div class="relative w-16 h-16">
                        <canvas id="placedChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Shortlisted Students Card -->
            <div
                class="modern-card rounded-2xl p-6 bg-gradient-to-br from-amber-50 to-white dark:from-amber-900/20 dark:to-slate-900">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 rounded-xl bg-amber-100 dark:bg-amber-900/30 shadow-sm">
                                <span class="material-symbols-outlined text-amber-600 dark:text-amber-400">
                                    star
                                </span>
                            </div>
                            <span class="text-amber-600 dark:text-amber-400 text-sm font-medium">Shortlisted Students</span>
                        </div>
                        <p class="text-4xl font-bold text-slate-900 dark:text-slate-50 mb-2">
                            {{ $shortlistedStudents }}
                        </p>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Potential placements</p>
                    </div>
                    <div class="relative w-16 h-16">
                        <canvas id="shortlistedChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Average CGPA Card -->
            <div
                class="modern-card rounded-2xl p-6 bg-gradient-to-br from-purple-50 to-white dark:from-purple-900/20 dark:to-slate-900">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <div class="p-3 rounded-xl bg-purple-100 dark:bg-purple-900/30 shadow-sm">
                                <span class="material-symbols-outlined text-purple-600 dark:text-purple-400">
                                    star
                                </span>
                            </div>
                            <span class="text-purple-600 dark:text-purple-400 text-sm font-medium">Average CGPA</span>
                        </div>
                        <p class="text-4xl font-bold text-slate-900 dark:text-slate-50 mb-2">
                            @php
                                $avgCGPA = $students->avg('cgpa') ?? 0;
                                echo number_format($avgCGPA, 2);
                            @endphp
                        </p>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Overall academic performance</p>
                    </div>
                    <div class="text-right">
                        @php
                            $cgpaColor =
                                $avgCGPA >= 8 ? 'text-green-600' : ($avgCGPA >= 7 ? 'text-amber-600' : 'text-red-600');
                        @endphp
                        <span class="material-symbols-outlined {{ $cgpaColor }} text-3xl">
                            grade
                        </span>
                    </div>
                </div>
            </div>
        </div>




        <!-- Students Table Section -->
        <!-- Main Content -->
        <main class="flex-grow pb-24">





            <!-- Students Table -->
            <div class="p-4">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-xl font-bold text-slate-900 dark:text-slate-50">Students</h2>

                    <div class="flex items-center gap-2">
                        <!-- 🔥 UPDATED SEARCH INPUT -->
                        <input type="text" id="studentSearch" placeholder="Search students..."
                            class="px-3 py-1.5 text-sm border border-slate-300 dark:border-slate-600 rounded-lg 
                               bg-white dark:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-primary">

                        {{-- <a href=""
                        class="px-4 py-1.5 bg-primary text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                        View All
                    </a> --}}
                    </div>
                </div>

                <div
                    class="rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-800">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                            <thead class="bg-slate-50 dark:bg-slate-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Course</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Department</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">CGPA</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase">Actions</th>
                                </tr>
                            </thead>

                            <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                                @forelse($students as $student)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-900/50">
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div
                                                    class="h-8 w-8 rounded-full bg-primary/10 flex items-center justify-center text-primary font-medium">
                                                    {{ substr($student->user_name, 0, 1) }}
                                                </div>
                                                <div class="ml-3">
                                                    <div class="font-medium">{{ $student->user_name }}</div>
                                                    <div class="text-sm text-slate-500">
                                                        {{ $student->email ?? 'No email' }}
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 text-sm">{{ $student->course_name ?? 'Not specified' }}</td>
                                        <td class="px-6 py-4 text-sm">{{ $student->department_name ?? 'Not specified' }}
                                        </td>

                                        <td class="px-6 py-4 text-sm">
                                            <span class="px-2 py-1 text-xs font-medium rounded-full">
                                                {{ $student->cgpa ?? 'N/A' }}
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-sm">

                                            <a href="{{ route('PlacementOfficerUserProfile.user.profile', $student->id) }}" class="text-primary hover:underline">View Profile</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-slate-500">
                                            <div class="flex flex-col items-center justify-center">
                                                <span class="material-symbols-outlined text-4xl mb-2">school</span>
                                                <p class="text-lg font-medium mb-1">No students found</p>
                                                <p class="text-sm">No students are currently registered in your college.
                                                </p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>
        </main>

        <!-- 🔥 SEARCH SCRIPT -->
        <script>
            document.getElementById("studentSearch").addEventListener("keyup", function() {
                let value = this.value.toLowerCase().trim();
                let rows = document.querySelectorAll("table tbody tr");

                rows.forEach(row => {
                    let text = row.innerText.toLowerCase();
                    row.style.display = text.includes(value) ? "" : "none";
                });
            });
        </script>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Department Distribution Chart -->
            <div class="modern-card rounded-2xl p-6">
                <h4 class="text-lg font-semibold text-slate-900 dark:text-slate-50 mb-4">Department Distribution</h4>
                <div class="h-64">
                    <canvas id="departmentChart"></canvas>
                </div>
            </div>

            <!-- CGPA Distribution Chart -->
            <div class="modern-card rounded-2xl p-6">
                <h4 class="text-lg font-semibold text-slate-900 dark:text-slate-50 mb-4">CGPA Distribution</h4>
                <div class="h-64">
                    <canvas id="cgpaDistributionChart"></canvas>
                </div>
            </div>
        </div>
    </main>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize charts
            initializeCharts();

            // Initialize search and filter functionality
            initializeSearchAndFilters();

            // Initialize pagination
            initializePagination();

            // Initialize interactive elements
            initializeInteractiveElements();
        });

        function initializeCharts() {
            // Placed Students Chart
            const placedChart = document.getElementById('placedChart');
            if (placedChart) {
                const placedPercent = {{ $totalStudents > 0 ? ($placedStudents / $totalStudents) * 100 : 0 }};

                new Chart(placedChart, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [placedPercent, 100 - placedPercent],
                            backgroundColor: [
                                '#10b981',
                                'rgba(229, 231, 235, 0.5)'
                            ],
                            borderWidth: 0,
                            cutout: '75%'
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                enabled: false
                            }
                        }
                    }
                });
            }

            // Shortlisted Students Chart
            const shortlistedChart = document.getElementById('shortlistedChart');
            if (shortlistedChart) {
                const shortlistedPercent = {{ $totalStudents > 0 ? ($shortlistedStudents / $totalStudents) * 100 : 0 }};

                new Chart(shortlistedChart, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [shortlistedPercent, 100 - shortlistedPercent],
                            backgroundColor: [
                                '#f59e0b',
                                'rgba(229, 231, 235, 0.5)'
                            ],
                            borderWidth: 0,
                            cutout: '75%'
                        }]
                    },
                    options: {
                        responsive: true,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                enabled: false
                            }
                        }
                    }
                });
            }

            // Department Distribution Chart
            const departmentChart = document.getElementById('departmentChart');
            if (departmentChart) {
                const deptData = {!! json_encode($departmentStats) !!};
                const labels = Object.keys(deptData);
                const data = Object.values(deptData);

                new Chart(departmentChart, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Students',
                            data: data,
                            backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            borderColor: 'rgb(59, 130, 246)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            }

            // CGPA Distribution Chart
            const cgpaChart = document.getElementById('cgpaDistributionChart');
            if (cgpaChart) {
                const cgpaData = {!! json_encode($cgpaStats) !!};
                const labels = ['<6', '6-7', '7-8', '8-9', '9+'];
                const data = labels.map(label => cgpaData[label] || 0);

                new Chart(cgpaChart, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: data,
                            backgroundColor: [
                                'rgba(239, 68, 68, 0.8)',
                                'rgba(245, 158, 11, 0.8)',
                                'rgba(59, 130, 246, 0.8)',
                                'rgba(16, 185, 129, 0.8)',
                                'rgba(139, 92, 246, 0.8)'
                            ]
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'right'
                            }
                        }
                    }
                });
            }
        }







        // Handle dark mode changes
        const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        darkModeMediaQuery.addEventListener('change', function(e) {
            // Reinitialize charts for theme change
            initializeCharts();
        });
    </script>
@endsection
