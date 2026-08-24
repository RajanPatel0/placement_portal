@extends('layouts.base')

@section('content')

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Application Stats</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap" rel="stylesheet" />
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#1173d4",
                        "background-light": "#f6f7f8",
                        "background-dark": "#101922",
                        "foreground-light": "#101922",
                        "foreground-dark": "#f6f7f8",
                        "card-light": "#ffffff",
                        "card-dark": "#1b2734",
                        "subtle-light": "#e5e7eb",
                        "subtle-dark": "#374151"
                    },
                    fontFamily: {
                        "display": ["Inter", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.5rem",
                        "lg": "0.75rem",
                        "xl": "1rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24
        }

        body {
            min-height: max(884px, 100dvh);
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-foreground-light dark:text-foreground-dark">
    <div class="max-w-md mx-auto">
        <header
            class="flex items-center justify-between p-4 sticky top-0 bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-sm">
            <button class="p-2" onclick="window.history.back()">
                <svg class="text-foreground-light dark:text-foreground-dark" fill="currentColor" height="24"
                    viewBox="0 0 16 16" width="24" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0z"
                        fill-rule="evenodd"></path>
                </svg>
            </button>
            <h1 class="text-lg font-bold">Application Stats</h1>
            <div class="w-8"></div>
        </header>

        <main class="p-4 space-y-8">
            {{-- Overall Status --}}
            <section>
                <h2 class="text-xl font-bold mb-4">Overall Status</h2>
                <div class="bg-card-light dark:bg-card-dark p-4 rounded-lg shadow-sm">
                    <div class="flex items-center justify-center space-x-8 h-48">
                        <div class="relative w-40 h-40">
                            <svg class="w-full h-full" viewBox="0 0 36 36">
                                <path class="text-subtle-light dark:text-subtle-dark"
                                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                    fill="none" stroke-width="3"></path>
                                <path class="text-primary" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831"
                                    fill="none" stroke-dasharray="15, 100" stroke-linecap="round" stroke-width="3">
                                </path>
                                <path class="text-yellow-500" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831"
                                    fill="none" stroke-dasharray="25, 100" stroke-dashoffset="-15"
                                    stroke-linecap="round" stroke-width="3"></path>
                                <path class="text-red-500" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831"
                                    fill="none" stroke-dasharray="50, 100" stroke-dashoffset="-40"
                                    stroke-linecap="round" stroke-width="3"></path>
                                <path class="text-green-500" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831"
                                    fill="none" stroke-dasharray="10, 100" stroke-dashoffset="-90"
                                    stroke-linecap="round" stroke-width="3"></path>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-3xl font-bold">{{ $stats->total_count ?? 0 }}</span>
                                <span class="text-sm text-foreground-light/70 dark:text-foreground-dark/70">Total</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6 grid grid-cols-2 gap-4 text-sm">
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 rounded-full bg-primary"></div>
                            <span>Applied</span>
                            <span class="font-semibold ml-auto">{{ $stats->applied_count ?? 0 }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 rounded-full bg-yellow-500"></div>
                            <span>Shortlisted</span>
                            <span class="font-semibold ml-auto">{{ $stats->shortlisted_count ?? 0 }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 rounded-full bg-red-500"></div>
                            <span>Rejected</span>
                            <span class="font-semibold ml-auto">{{ $stats->rejected_count ?? 0 }}</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-3 h-3 rounded-full bg-green-500"></div>
                            <span>Selected</span>
                            <span class="font-semibold ml-auto">{{ $stats->selected_count ?? 0 }}</span>
                        </div>
                    </div>
                </div>
            </section>

            {{-- Application Trends --}}
            {{-- Application Trends --}}
            <section>
                <h2 class="text-xl font-bold mb-4">Application Trends</h2>
                <div class="bg-card-light dark:bg-card-dark p-4 rounded-lg shadow-sm">
                    <p class="text-base font-medium mb-4">Applications Over Time</p>

                    <div class="h-48 relative">
                        <canvas id="applicationTrendChart" class="w-full h-full"></canvas>
                    </div>

                    <!--><div-->
                    <!--    class="flex justify-between text-xs text-foreground-light/70 dark:text-foreground-dark/70 mt-2">-->
                    <!--    @foreach ($trendLabels ?? [] as $month)-->
                    <!--        <span>{{ $month }}</span>-->
                    <!--    @endforeach-->
                    <!--</div-->
                </div>
            </section>

            {{-- ChartJS --}}
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                // ensure variables exist
                const labels = @json($trendLabels ?? []);
                const counts = @json($trendCounts ?? []);

                const ctx = document.getElementById('applicationTrendChart');
                if (ctx && labels.length) {
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: 'Applications',
                                data: counts,
                                borderColor: '#1173d4',
                                backgroundColor: 'rgba(17, 115, 212, 0.2)',
                                fill: true,
                                tension: 0.4,
                                borderWidth: 2,
                                pointRadius: 3,
                                pointBackgroundColor: '#1173d4'
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                x: {
                                    grid: {
                                        display: false
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 1
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
                } else if (ctx) {
                    // If no labels/data, show an empty chart with a single 0 value
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: ['No Data'],
                            datasets: [{
                                data: [0],
                                fill: false,
                                borderWidth: 0
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            }
                        }
                    });
                }
            </script>

        </main>

        <div class="h-16"></div>
    </div>
</body>

</html>
@endsection