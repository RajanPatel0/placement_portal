@extends('dashboardLayouts.base')

@section('title', 'Placement Officer Dashboard')

@section('content')
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#137fec",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922",
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
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

        /* Custom styles for stat cards */
        .stat-card {
            transition: all 0.3s ease;
            cursor: pointer;
            text-decoration: none !important;
            display: block;
            color: inherit !important;
            outline: none;
            border: none;
        }

        .stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .stat-card:focus {
            outline: none;
            box-shadow: 0 0 0 2px rgba(19, 127, 236, 0.3);
        }

        /* Chart containers */
        .chart-container {
            background: white;
            border-radius: 12px;
            padding: 24px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }

        .dark .chart-container {
            background: #1f2937;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.3);
        }

        /* Grid layout */
        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 24px;
        }

        /* Progress rings */
        .progress-ring {
            width: 120px;
            height: 120px;
        }

        /* Remove link styling for all cards */
        a.stat-card,
        a.stat-card:hover,
        a.stat-card:focus,
        a.stat-card:active,
        a.stat-card:visited {
            text-decoration: none !important;
            color: inherit !important;
            outline: none;
            border: none;
        }

        /* Modern card styling */
        .modern-card {
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .dark .modern-card {
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* Animation for numbers */
        @keyframes countUp {
            from {
                transform: translateY(10px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .count-up {
            animation: countUp 0.8s ease-out;
        }
    </style>

    <!-- Main Content -->
    <main class="flex-grow pb-24 px-4 lg:px-6">
        <!-- Welcome Header -->
        <div class="mb-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-50">
                        Welcome back, {{ Auth::user()->name }}!
                    </h1>
                    @if ($college)
                        <p class="text-slate-600 dark:text-slate-300 mt-2">
                            <span class="material-symbols-outlined align-middle mr-1 text-lg">school</span>
                            College: <span class="font-medium">{{ $college->name }}</span>
                        </p>
                    @endif
                </div>
                {{-- <div class="flex items-center gap-3">
                    <span
                        class="px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                        Placement Officer
                    </span>
                    <span class="text-sm text-slate-500 dark:text-slate-400">
                        Last login: {{ date('M d, Y') }}
                    </span>
                </div> --}}
            </div>
        </div>

        <!-- Stats Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Students Card -->
            <a href="{{ route('placement.officer.students') }}"
                class="stat-card modern-card rounded-2xl p-6 bg-gradient-to-br from-slate-50 to-white dark:from-slate-800 dark:to-slate-900">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <div class="p-2 rounded-lg bg-slate-100 dark:bg-slate-700">
                                <span class="material-symbols-outlined text-slate-600 dark:text-slate-300">
                                    groups
                                </span>
                            </div>
                            <span class="text-slate-500 dark:text-slate-400 text-sm font-medium">Total Students</span>
                        </div>
                        <p class="text-3xl font-bold text-slate-900 dark:text-slate-50 count-up">
                            {{ $totalStudents ?? 0 }}
                        </p>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-slate-600 dark:text-slate-300">
                                <span class="material-symbols-outlined align-middle text-sm mr-1">trending_up</span>
                                All registered students
                            </span>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Placement Drives Card -->
            <a href="{{ route('placement.officer.placement.drives') }}"
                class="stat-card modern-card rounded-2xl p-6 bg-gradient-to-br from-blue-50 to-white dark:from-blue-900/20 dark:to-slate-900">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <div class="p-2 rounded-lg bg-blue-100 dark:bg-blue-800">
                                <span class="material-symbols-outlined text-blue-600 dark:text-blue-300">
                                    work
                                </span>
                            </div>
                            <span class="text-blue-600 dark:text-blue-300 text-sm font-medium">Placement Drives</span>
                        </div>
                        <p class="text-3xl font-bold text-blue-900 dark:text-blue-50 count-up">
                            {{ $totalDrives ?? 0 }}
                        </p>
                        <div class="mt-4 flex items-center text-sm">
                            <span class="text-blue-600/70 dark:text-blue-300/70">
                                <span class="material-symbols-outlined align-middle text-sm mr-1">event</span>
                                Active drives
                            </span>
                        </div>
                    </div>
                </div>
            </a>

            <!-- Selected Students Card -->
            <a href="" id="selectedStudentsLink"
                class="stat-card modern-card rounded-2xl p-6 bg-gradient-to-br from-green-50 to-white dark:from-green-900/20 dark:to-slate-900">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <div class="p-2 rounded-lg bg-green-100 dark:bg-green-800">
                                <span class="material-symbols-outlined text-green-600 dark:text-green-300">
                                    check_circle
                                </span>
                            </div>
                            <span class="text-green-600 dark:text-green-300 text-sm font-medium">Selected Students</span>
                        </div>
                        <p class="text-3xl font-bold text-green-900 dark:text-green-50 count-up">
                            {{ $selectedStudents ?? 0 }}
                        </p>
                        <div class="mt-4 flex items-center text-sm">
                            @if ($totalStudents > 0)
                                <span class="text-green-600/70 dark:text-green-300/70">
                                    {{ number_format(($selectedStudents / $totalStudents) * 100, 1) }}% of total
                                </span>
                            @endif
                        </div>
                    </div>
                    @if ($totalStudents > 0)
                        <div class="relative progress-ring">
                            <canvas id="selectedProgress" width="120" height="120"></canvas>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-sm font-bold text-green-700 dark:text-green-300">
                                    {{ number_format(($selectedStudents / $totalStudents) * 100, 0) }}%
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </a>

            <!-- Shortlisted Students Card -->
            <a href="" id="shortlistedStudentsLink"
                class="stat-card modern-card rounded-2xl p-6 bg-gradient-to-br from-amber-50 to-white dark:from-amber-900/20 dark:to-slate-900">
                <div class="flex items-start justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <div class="p-2 rounded-lg bg-amber-100 dark:bg-amber-800">
                                <span class="material-symbols-outlined text-amber-600 dark:text-amber-300">
                                    star
                                </span>
                            </div>
                            <span class="text-amber-600 dark:text-amber-300 text-sm font-medium">Shortlisted Students</span>
                        </div>
                        <p class="text-3xl font-bold text-amber-900 dark:text-amber-50 count-up">
                            {{ $shortlistedStudents ?? 0 }}
                        </p>
                        <div class="mt-4 flex items-center text-sm">
                            @if ($totalStudents > 0)
                                <span class="text-amber-600/70 dark:text-amber-300/70">
                                    {{ number_format(($shortlistedStudents / $totalStudents) * 100, 1) }}% of total
                                </span>
                            @endif
                        </div>
                    </div>
                    @if ($totalStudents > 0)
                        <div class="relative progress-ring">
                            <canvas id="shortlistedProgress" width="120" height="120"></canvas>
                            <div class="absolute inset-0 flex items-center justify-center">
                                <span class="text-sm font-bold text-amber-700 dark:text-amber-300">
                                    {{ number_format(($shortlistedStudents / $totalStudents) * 100, 0) }}%
                                </span>
                            </div>
                        </div>
                    @endif
                </div>
            </a>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Pie Chart: Student Distribution -->
            <div class="chart-container">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-50">Student Distribution</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Placement status overview</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-slate-500">Total: {{ $totalStudents }}</span>
                    </div>
                </div>
                <div class="relative h-64">
                    <canvas id="studentDistributionChart"></canvas>
                </div>
                <div class="mt-6 grid grid-cols-2 gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                        <div>
                            <span class="text-sm font-medium text-slate-900 dark:text-slate-50">Unplaced</span>
                            <p class="text-2xl font-bold text-slate-900 dark:text-slate-50">
                                {{ $totalStudents - $selectedStudents - $shortlistedStudents }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-green-500"></div>
                        <div>
                            <span class="text-sm font-medium text-slate-900 dark:text-slate-50">Selected</span>
                            <p class="text-2xl font-bold text-slate-900 dark:text-slate-50">{{ $selectedStudents }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-amber-500"></div>
                        <div>
                            <span class="text-sm font-medium text-slate-900 dark:text-slate-50">Shortlisted</span>
                            <p class="text-2xl font-bold text-slate-900 dark:text-slate-50">{{ $shortlistedStudents }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-3 h-3 rounded-full bg-slate-300 dark:bg-slate-600"></div>
                        <div>
                            <span class="text-sm font-medium text-slate-900 dark:text-slate-50">Total</span>
                            <p class="text-2xl font-bold text-slate-900 dark:text-slate-50">{{ $totalStudents }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bar Chart: Placement Metrics -->
            <div class="chart-container">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-50">Placement Metrics</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Comparison of key metrics</p>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-xs text-slate-500">All time</span>
                    </div>
                </div>
                <div class="relative h-64">
                    <canvas id="placementMetricsChart"></canvas>
                </div>
                <div class="mt-6 grid grid-cols-4 gap-4 text-center">
                    <div>
                        <div class="text-sm text-slate-600 dark:text-slate-400 mb-1">Selection Rate</div>
                        <div class="text-xl font-bold text-green-600 dark:text-green-400">
                            @if ($totalStudents > 0)
                                {{ number_format(($selectedStudents / $totalStudents) * 100, 1) }}%
                            @else
                                0%
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-slate-600 dark:text-slate-400 mb-1">Shortlist Rate</div>
                        <div class="text-xl font-bold text-amber-600 dark:text-amber-400">
                            @if ($totalStudents > 0)
                                {{ number_format(($shortlistedStudents / $totalStudents) * 100, 1) }}%
                            @else
                                0%
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-slate-600 dark:text-slate-400 mb-1">Drives/Student</div>
                        <div class="text-xl font-bold text-blue-600 dark:text-blue-400">
                            @if ($totalStudents > 0)
                                {{ number_format($totalDrives / $totalStudents, 2) }}
                            @else
                                0
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="text-sm text-slate-600 dark:text-slate-400 mb-1">Engagement</div>
                        <div class="text-xl font-bold text-purple-600 dark:text-purple-400">
                            @if ($totalStudents > 0)
                                {{ number_format((($selectedStudents + $shortlistedStudents) / $totalStudents) * 100, 1) }}%
                            @else
                                0%
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Visualizations -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            <!-- Radial Progress: Overall Placement -->
            <div class="chart-container">
                <div class="text-center mb-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-50">Overall Placement Progress</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400">Towards placement goals</p>
                </div>
                <div class="relative h-48 flex items-center justify-center">
                    <canvas id="overallProgressChart" width="200" height="200"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-3xl font-bold text-slate-900 dark:text-slate-50">
                            @if ($totalStudents > 0)
                                {{ number_format(($selectedStudents / $totalStudents) * 100, 0) }}%
                            @else
                                0%
                            @endif
                        </span>
                        <span class="text-sm text-slate-600 dark:text-slate-400">Placement Rate</span>
                    </div>
                </div>
                <div class="mt-6 text-center">
                    <p class="text-sm text-slate-600 dark:text-slate-400">
                        {{ $selectedStudents }} students placed out of {{ $totalStudents }}
                    </p>
                </div>
            </div>

            <!-- Mini Chart: Drive Distribution -->
            <div class="chart-container">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-50">Activity Overview</h3>
                        <p class="text-sm text-slate-600 dark:text-slate-400">Recent placement activity</p>
                    </div>
                </div>
                <div class="space-y-6">
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-slate-700 dark:text-slate-300">Placement Drives</span>
                            <span class="text-sm font-bold text-blue-600 dark:text-blue-400">{{ $totalDrives }}</span>
                        </div>
                        <div class="h-2 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-500 rounded-full"
                                style="width: {{ min(100, ($totalDrives / 20) * 100) }}%"></div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-slate-700 dark:text-slate-300">Selected Students</span>
                            <span
                                class="text-sm font-bold text-green-600 dark:text-green-400">{{ $selectedStudents }}</span>
                        </div>
                        <div class="h-2 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                            <div class="h-full bg-green-500 rounded-full"
                                style="width: {{ $totalStudents > 0 ? ($selectedStudents / $totalStudents) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-sm text-slate-700 dark:text-slate-300">Shortlisted Students</span>
                            <span
                                class="text-sm font-bold text-amber-600 dark:text-amber-400">{{ $shortlistedStudents }}</span>
                        </div>
                        <div class="h-2 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden">
                            <div class="h-full bg-amber-500 rounded-full"
                                style="width: {{ $totalStudents > 0 ? ($shortlistedStudents / $totalStudents) * 100 : 0 }}%">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="chart-container">
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-slate-900 dark:text-slate-50">Quick Statistics</h3>
                    <p class="text-sm text-slate-600 dark:text-slate-400">Key metrics at a glance</p>
                </div>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-800 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg bg-blue-100 dark:bg-blue-900">
                                <span class="material-symbols-outlined text-blue-600 dark:text-blue-300 text-sm">
                                    trending_up
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-900 dark:text-slate-50">Avg. Placement Rate</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">College average</p>
                            </div>
                        </div>
                        <span class="text-lg font-bold text-slate-900 dark:text-slate-50">
                            @if ($totalStudents > 0)
                                {{ number_format(($selectedStudents / $totalStudents) * 100, 1) }}%
                            @else
                                0%
                            @endif
                        </span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-800 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg bg-green-100 dark:bg-green-900">
                                <span class="material-symbols-outlined text-green-600 dark:text-green-300 text-sm">
                                    workspace_premium
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-900 dark:text-slate-50">Top Performers</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Selected students</p>
                            </div>
                        </div>
                        <span class="text-lg font-bold text-slate-900 dark:text-slate-50">{{ $selectedStudents }}</span>
                    </div>
                    <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-800 rounded-lg">
                        <div class="flex items-center gap-3">
                            <div class="p-2 rounded-lg bg-amber-100 dark:bg-amber-900">
                                <span class="material-symbols-outlined text-amber-600 dark:text-amber-300 text-sm">
                                    star
                                </span>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-slate-900 dark:text-slate-50">Potential Placements</p>
                                <p class="text-xs text-slate-500 dark:text-slate-400">Shortlisted students</p>
                            </div>
                        </div>
                        <span
                            class="text-lg font-bold text-slate-900 dark:text-slate-50">{{ $shortlistedStudents }}</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize progress rings
            initializeProgressRings();

            // Initialize charts
            initializeCharts();

            // Add hover effects
            addHoverEffects();
        });

        function initializeProgressRings() {
            // Selected Students Progress Ring
            const selectedProgress = document.getElementById('selectedProgress');
            if (selectedProgress) {
                const ctx = selectedProgress.getContext('2d');
                const selectedPercent = {{ $totalStudents > 0 ? ($selectedStudents / $totalStudents) * 100 : 0 }};

                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [selectedPercent, 100 - selectedPercent],
                            backgroundColor: [
                                '#10b981',
                                '#e5e7eb'
                            ],
                            borderWidth: 0,
                            cutout: '85%'
                        }]
                    },
                    options: {
                        responsive: false,
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

            // Shortlisted Students Progress Ring
            const shortlistedProgress = document.getElementById('shortlistedProgress');
            if (shortlistedProgress) {
                const ctx = shortlistedProgress.getContext('2d');
                const shortlistedPercent = {{ $totalStudents > 0 ? ($shortlistedStudents / $totalStudents) * 100 : 0 }};

                new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        datasets: [{
                            data: [shortlistedPercent, 100 - shortlistedPercent],
                            backgroundColor: [
                                '#f59e0b',
                                '#e5e7eb'
                            ],
                            borderWidth: 0,
                            cutout: '85%'
                        }]
                    },
                    options: {
                        responsive: false,
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
        }

        function initializeCharts() {
            // Student Distribution Chart (Pie)
            const studentDistCtx = document.getElementById('studentDistributionChart').getContext('2d');
            const unplacedStudents = {{ $totalStudents - $selectedStudents - $shortlistedStudents }};

            new Chart(studentDistCtx, {
                type: 'pie',
                data: {
                    labels: ['Unplaced', 'Selected', 'Shortlisted'],
                    datasets: [{
                        data: [unplacedStudents, {{ $selectedStudents }}, {{ $shortlistedStudents }}],
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(245, 158, 11, 0.8)'
                        ],
                        borderColor: [
                            'rgb(59, 130, 246)',
                            'rgb(16, 185, 129)',
                            'rgb(245, 158, 11)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            labels: {
                                color: function(context) {
                                    return getComputedStyle(document.documentElement).getPropertyValue(
                                        '--tw-slate-900') || '#1f2937';
                                }
                            }
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const label = context.label || '';
                                    const value = context.raw || 0;
                                    const total = {{ $totalStudents }};
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return `${label}: ${value} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });

            // Placement Metrics Chart (Bar)
            const placementMetricsCtx = document.getElementById('placementMetricsChart').getContext('2d');

            new Chart(placementMetricsCtx, {
                type: 'bar',
                data: {
                    labels: ['Total Students', 'Placement Drives', 'Selected', 'Shortlisted'],
                    datasets: [{
                        label: 'Count',
                        data: [
                            {{ $totalStudents }},
                            {{ $totalDrives }},
                            {{ $selectedStudents }},
                            {{ $shortlistedStudents }}
                        ],
                        backgroundColor: [
                            'rgba(100, 116, 139, 0.8)',
                            'rgba(59, 130, 246, 0.8)',
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(245, 158, 11, 0.8)'
                        ],
                        borderColor: [
                            'rgb(100, 116, 139)',
                            'rgb(59, 130, 246)',
                            'rgb(16, 185, 129)',
                            'rgb(245, 158, 11)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.1)'
                            },
                            ticks: {
                                color: function(context) {
                                    return getComputedStyle(document.documentElement).getPropertyValue(
                                        '--tw-slate-600') || '#4b5563';
                                }
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                color: function(context) {
                                    return getComputedStyle(document.documentElement).getPropertyValue(
                                        '--tw-slate-600') || '#4b5563';
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });

            // Overall Progress Chart (Radial)
            const overallProgressCtx = document.getElementById('overallProgressChart').getContext('2d');
            const placementRate = {{ $totalStudents > 0 ? ($selectedStudents / $totalStudents) * 100 : 0 }};

            new Chart(overallProgressCtx, {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: [placementRate, 100 - placementRate],
                        backgroundColor: [
                            'rgba(16, 185, 129, 0.8)',
                            'rgba(229, 231, 235, 0.5)'
                        ],
                        borderWidth: 0,
                        circumference: 180,
                        rotation: 270
                    }]
                },
                options: {
                    responsive: false,
                    cutout: '75%',
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

        function addHoverEffects() {
            // Add click handlers for cards
            const selectedLink = document.getElementById('selectedStudentsLink');
            const shortlistedLink = document.getElementById('shortlistedStudentsLink');

            if (selectedLink) {
                selectedLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    // You can implement filtering logic here
                    console.log('View selected students');
                    // window.location.href = '/placement-officer/students?status=selected';
                });
            }

            if (shortlistedLink) {
                shortlistedLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    // You can implement filtering logic here
                    console.log('View shortlisted students');
                    // window.location.href = '/placement-officer/students?status=shortlisted';
                });
            }
        }

        // Handle dark mode changes
        const darkModeMediaQuery = window.matchMedia('(prefers-color-scheme: dark)');
        darkModeMediaQuery.addEventListener('change', function(e) {
            // Reinitialize charts when theme changes
            initializeCharts();
        });
    </script>
@endsection
