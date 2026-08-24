@extends('dashboardLayouts.base')

@section('title', 'Company Dashboard')

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

    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <title>Student Application Dashboard</title>
        <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&amp;display=swap"
            rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
        <style>
            .material-symbols-outlined {
                font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
            }

            .dashboard-card {
                transition: all 0.3s ease;
                cursor: pointer;
            }

            .dashboard-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            }

            .funnel-container {
                position: relative;
                width: 160px;
                height: 160px;
            }

            .funnel-svg {
                transform: rotate(-90deg);
            }

            .funnel-text {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                text-align: center;
            }
        </style>
        <script id="tailwind-config">
            tailwind.config = {
                darkMode: "class",
                theme: {
                    extend: {
                        colors: {
                            "primary": "#4A90E2",
                            "secondary": "#50E3C2",
                            "background-light": "#F8F9FA",
                            "background-dark": "#101622",
                            "text-light-headings": "#000000",
                            "text-light-body": "#333333",
                            "text-dark-headings": "#FFFFFF",
                            "text-dark-body": "#E0E0E0",
                            "card-light": "#FFFFFF",
                            "card-dark": "#1A2233",
                            "border-light": "#EAECEF",
                            "border-dark": "#2D3748"
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
    </head>

    <body class="font-display">
        <div
            class="relative min-h-screen w-full bg-background-light dark:bg-background-dark group/design-root overflow-x-hidden">
            <div
                class="flex flex-col gap-2 bg-background-light dark:bg-background-dark p-4 pb-2 sticky top-0 z-10 border-b border-border-light dark:border-border-dark">
                <div class="flex h-12 items-center justify-between">
                    <div class="flex items-center gap-3">
                        <!--<div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10" data-alt="User avatar of Alex" style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAPnRwIMs51H4BBuWddveYKHqkv5EKEMLdJ0Z33BDOl77Tze6DMoEh3crCHuo6nAEud-TSwKz5wmj9g_uMyNR2oYZZkhaNkFuaV5bRQSYuBRBg_w28rMfBBI4ivIxQLpcTFj8aqNPXi0kF6UyjMjiLiFCSLnmKbafQmwa9FxIXFauHpNUlwKyevaRxgFAxU_9jti3cAe91r0WwVmohAqgiQadsc2zFauG9jVGFccbZFqho7cf_BqusiSP3aXgpPqN1EQ_uQDVNOxsfn");'></div>-->
                        <p class="text-text-light-headings dark:text-dark-headings text-lg font-bold leading-tight">Welcome,
                            {{ Auth::user()->name ?? 'User' }}!</p>
                    </div>
                    <div class="flex w-12 items-center justify-end relative">
                        <!--<button class="flex cursor-pointer items-center justify-center rounded-full h-10 w-10 text-text-light-body dark:text-dark-body hover:bg-gray-200 dark:hover:bg-card-dark">-->
                        <!--    <span class="material-symbols-outlined text-2xl">notifications</span>-->
                        <!--</button>-->
                        <!--<div class="absolute top-1 right-1 h-3 w-3 rounded-full bg-red-500 border-2 border-background-light dark:border-background-dark"></div>-->
                    </div>
                </div>
            </div>

            <!-- Dashboard Cards Section -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 p-4">
                <!-- Pending Selection Card -->
                <a href="{{ route('company.index') }}"
                    class="dashboard-card flex flex-col gap-2 rounded-xl p-4 bg-card-light dark:bg-card-dark shadow-sm border border-border-light dark:border-border-dark text-decoration-none">
                    <p class="text-text-light-body dark:text-dark-body text-sm font-medium leading-normal">Mark as
                        Shortlisted</p>
                    <p
                        class="text-text-light-headings dark:text-dark-headings tracking-tight text-3xl font-bold leading-tight">
                        {{ $appliedCount }}</p>
                    <div class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-green-500 text-base">arrow_upward</span>
                        <p class="text-green-500 text-sm font-medium leading-normal">
                            @if ($appliedCount > 0)
                                {{ number_format(($shortlistedCount / $appliedCount) * 100, 1) }}%
                            @else
                                0%
                            @endif
                        </p>
                    </div>
                </a>

                <!-- Shortlisted Card -->
                <a href="{{ route('company.shortlisted.students') }}"
                    class="dashboard-card flex flex-col gap-2 rounded-xl p-4 bg-card-light dark:bg-card-dark shadow-sm border border-border-light dark:border-border-dark text-decoration-none">
                    <p class="text-text-light-body dark:text-dark-body text-sm font-medium leading-normal">Shortlisted</p>
                    <p
                        class="text-text-light-headings dark:text-dark-headings tracking-tight text-3xl font-bold leading-tight">
                        {{ $shortlistedCount }}</p>
                    <div class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-green-500 text-base">arrow_upward</span>
                        <p class="text-green-500 text-sm font-medium leading-normal">
                            @if ($appliedCount > 0)
                                {{ number_format(($shortlistedCount / $appliedCount) * 100, 1) }}%
                            @else
                                0%
                            @endif
                        </p>
                    </div>
                </a>

                <!-- Selected Card -->
                <a href="{{ route('company.final.selection') }}"
                    class="dashboard-card flex flex-col gap-2 rounded-xl p-4 bg-card-light dark:bg-card-dark shadow-sm border border-border-light dark:border-border-dark text-decoration-none">
                    <p class="text-text-light-body dark:text-dark-body text-sm font-medium leading-normal">Mark as Selected
                    </p>
                    <p
                        class="text-text-light-headings dark:text-dark-headings tracking-tight text-3xl font-bold leading-tight">
                        {{ $selectedCount }}</p>
                    <div class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-green-500 text-base">arrow_upward</span>
                        <p class="text-green-500 text-sm font-medium leading-normal">
                            @if ($appliedCount > 0)
                                {{ number_format(($selectedCount / $appliedCount) * 100, 1) }}%
                            @else
                                0%
                            @endif
                        </p>
                    </div>
                </a>

                <!-- Total Applications Card -->
                <a href="{{ route('company.selected.students') }}"
                    class="dashboard-card flex flex-col gap-2 rounded-xl p-4 bg-card-light dark:bg-card-dark shadow-sm border border-border-light dark:border-border-dark text-decoration-none">
                    <p class="text-text-light-body dark:text-dark-body text-sm font-medium leading-normal">Selected</p>
                    <p
                        class="text-text-light-headings dark:text-dark-headings tracking-tight text-3xl font-bold leading-tight">
                        {{ $selectedCount }}</p>
                    <div class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-green-500 text-base">arrow_upward</span>
                        <p class="text-green-500 text-sm font-medium leading-normal">100%</p>
                    </div>
                </a>
            </div>

            <!-- Analytics Section -->
            <h2
                class="text-text-light-headings dark:text-dark-headings text-xl font-bold leading-tight tracking-tight px-4 pb-2 pt-4">
                Analytics</h2>
            <div class="flex flex-col lg:flex-row gap-4 px-4 pb-4">
                <!-- Placement Drive Details -->
                @if (isset($company))
                    <!-- Placement Drive Details -->
                    @if (isset($company))
                        <div
                            class="flex min-w-72 flex-1 flex-col gap-4 rounded-xl border border-border-light dark:border-border-dark p-5 bg-card-light dark:bg-card-dark shadow-sm">
                            <div class="flex items-center justify-between">
                                <p
                                    class="text-text-light-headings dark:text-dark-headings text-base font-semibold leading-normal">
                                    Placement Drive Details</p>
                                <a href="{{ route('placement-drive.company.view') }}"
                                    class="text-primary hover:underline text-sm font-medium">View All</a>
                            </div>
                            <div class="flex flex-col gap-4">
                                <div class="flex items-start gap-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 dark:bg-primary/20">
                                        <span class="material-symbols-outlined text-primary text-2xl">campaign</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <p class="text-text-light-body dark:text-dark-body text-sm">Drive Name</p>
                                        <p class="text-text-light-headings dark:text-dark-headings font-medium">
                                            {{ $company->company_name ?? 'N/A' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 dark:bg-primary/20">
                                        <span class="material-symbols-outlined text-primary text-2xl">calendar_today</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <p class="text-text-light-body dark:text-dark-body text-sm">Date</p>
                                        <p class="text-text-light-headings dark:text-dark-headings font-medium">
                                            @if (isset($company->drive_date))
                                                {{ \Carbon\Carbon::parse($company->drive_date)->format('jS M, Y') }}
                                            @else
                                                Date not set
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <div
                                        class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 dark:bg-primary/20">
                                        <span class="material-symbols-outlined text-primary text-2xl">location_on</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <p class="text-text-light-body dark:text-dark-body text-sm">Venue</p>
                                        <p class="text-text-light-headings dark:text-dark-headings font-medium">
                                            {{ $company->location ?? 'Venue not specified' }}</p>
                                    </div>
                                </div>


                            </div>
                        </div>
                    @else
                        <!-- If no company/drive is set, show a placeholder with View All -->
                        <div
                            class="flex min-w-72 flex-1 flex-col gap-4 rounded-xl border border-border-light dark:border-border-dark p-5 bg-card-light dark:bg-card-dark shadow-sm">
                            <div class="flex items-center justify-between">
                                <p
                                    class="text-text-light-headings dark:text-dark-headings text-base font-semibold leading-normal">
                                    Placement Drive Details</p>
                                <a href="{{ route('company.placement_drives') }}"
                                    class="text-primary hover:underline text-sm font-medium">View All</a>
                            </div>
                            <div class="flex flex-col items-center justify-center py-8 text-center">
                                <div
                                    class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-100 dark:bg-gray-800 mb-4">
                                    <span class="material-symbols-outlined text-gray-400 text-3xl">campaign</span>
                                </div>
                                <p class="text-text-light-body dark:text-dark-body text-sm mb-2">No active placement drive
                                </p>
                                <p class="text-text-light-body dark:text-dark-body text-xs">Click "View All" to see all
                                    placement drives</p>
                            </div>
                        </div>
                    @endif
                @endif

                <!-- Selection Funnel - Fixed with all segments -->
                <!-- Selection Funnel - Fixed with proper segment positioning -->
                <div
                    class="flex min-w-72 flex-1 flex-col gap-4 rounded-xl border border-border-light dark:border-border-dark p-5 bg-card-light dark:bg-card-dark shadow-sm">
                    <p class="text-text-light-headings dark:text-dark-headings text-base font-semibold leading-normal">
                        Selection Funnel</p>
                    <div class="flex flex-col sm:flex-row items-center gap-6">
                        <div class="funnel-container">
                            <svg class="funnel-svg w-full h-full" viewBox="0 0 36 36">
                                <!-- Background circle -->
                                <circle cx="18" cy="18" r="16" fill="none"
                                    class="stroke-gray-200 dark:stroke-gray-700" stroke-width="3"></circle>

                                @php
                                    // Each segment gets 33.33% of the circle
                                    $segmentPercent = 33.33;
                                    $circumference = 100; // 100% for the circle

                                    // Calculate dash arrays - each segment takes 33.33% of the circle
                                    $appliedDash =
                                        $appliedCount > 0
                                            ? $segmentPercent . ' ' . $circumference
                                            : '0 ' . $circumference;
                                    $shortlistedDash =
                                        $shortlistedCount > 0
                                            ? $segmentPercent . ' ' . $circumference
                                            : '0 ' . $circumference;
                                    $selectedDash =
                                        $selectedCount > 0
                                            ? $segmentPercent . ' ' . $circumference
                                            : '0 ' . $circumference;

                                    // Calculate dash offsets to position segments around the circle
                                    // Applied starts at 12 o'clock (offset 0)
                                    $appliedOffset = 0;
                                    // Shortlisted starts after applied segment (offset -33.33)
                                    $shortlistedOffset = -$segmentPercent;
                                    // Selected starts after applied + shortlisted segments (offset -66.66)
                                    $selectedOffset = -($segmentPercent * 2);
                                @endphp

                                <!-- Applied Segment (starts at top) -->
                                @if ($appliedCount > 0)
                                    <circle cx="18" cy="18" r="16" fill="none" class="stroke-primary"
                                        stroke-width="3" stroke-dasharray="{{ $appliedDash }}"
                                        stroke-dashoffset="{{ $appliedOffset }}"></circle>
                                @endif

                                <!-- Shortlisted Segment (starts after applied) -->
                                @if ($shortlistedCount > 0)
                                    <circle cx="18" cy="18" r="16" fill="none" class="stroke-secondary"
                                        stroke-width="3" stroke-dasharray="{{ $shortlistedDash }}"
                                        stroke-dashoffset="{{ $shortlistedOffset }}"></circle>
                                @endif

                                <!-- Selected Segment (starts after applied + shortlisted) -->
                                @if ($selectedCount > 0)
                                    <circle cx="18" cy="18" r="16" fill="none"
                                        class="stroke-orange-400" stroke-width="3"
                                        stroke-dasharray="{{ $selectedDash }}"
                                        stroke-dashoffset="{{ $selectedOffset }}"></circle>
                                @endif
                            </svg>
                            <div class="funnel-text">
                                <span
                                    class="text-2xl font-bold text-text-light-headings dark:text-dark-headings">{{ $selectedCount }}</span>
                                <span class="text-sm text-text-light-body dark:text-dark-body block">Total</span>
                            </div>
                        </div>
                        <div class="flex flex-col gap-3">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-primary"></div>
                                <span class="text-sm font-medium text-text-light-body dark:text-dark-body">Applied
                                    ({{ $appliedCount }})</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-secondary"></div>
                                <span class="text-sm font-medium text-text-light-body dark:text-dark-body">Shortlisted
                                    ({{ $shortlistedCount }})</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-orange-400"></div>
                                <span class="text-sm font-medium text-text-light-body dark:text-dark-body">Selected
                                    ({{ $selectedCount }})</span>
                            </div>

                            <!-- Conversion Rates -->
                            <div class="mt-2 pt-2 border-t border-border-light dark:border-border-dark">
                                <div class="flex justify-between text-xs text-text-light-body dark:text-dark-body">
                                    <span>Applied to Shortlisted:</span>
                                    <span class="font-medium">
                                        @if ($appliedCount > 0)
                                            {{ number_format(($shortlistedCount / $appliedCount) * 100, 1) }}%
                                        @else
                                            0%
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between text-xs text-text-light-body dark:text-dark-body">
                                    <span>Shortlisted to Selected:</span>
                                    <span class="font-medium">
                                        @if ($shortlistedCount > 0)
                                            {{ number_format(($selectedCount / $shortlistedCount) * 100, 1) }}%
                                        @else
                                            0%
                                        @endif
                                    </span>
                                </div>
                                <div class="flex justify-between text-xs text-text-light-body dark:text-dark-body">
                                    <span>Overall Conversion:</span>
                                    <span class="font-medium">
                                        @if ($appliedCount > 0)
                                            {{ number_format(($selectedCount / $appliedCount) * 100, 1) }}%
                                        @else
                                            0%
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity Section -->
            <h2
                class="text-text-light-headings dark:text-dark-headings text-xl font-bold leading-tight tracking-tight px-4 pb-3 pt-4">
                Recent Activity</h2>
            <div class="flex flex-col gap-3 px-4 pb-24">
                @if ($appliedCount > 0)
                    <div
                        class="flex items-center gap-4 rounded-lg p-3 bg-card-light dark:bg-card-dark hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <div class="flex items-center justify-center size-10 rounded-full bg-blue-100 dark:bg-blue-900/50">
                            <span class="material-symbols-outlined text-primary">person_add</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-text-light-headings dark:text-dark-headings">You have
                                {{ $appliedCount }} total applications for this drive.</p>
                            <p class="text-xs text-text-light-body dark:text-dark-body">Current status</p>
                        </div>
                    </div>
                @endif

                @if ($shortlistedCount > 0)
                    <div
                        class="flex items-center gap-4 rounded-lg p-3 bg-card-light dark:bg-card-dark hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <div
                            class="flex items-center justify-center size-10 rounded-full bg-green-100 dark:bg-green-900/50">
                            <span class="material-symbols-outlined text-green-600 dark:text-green-400">task_alt</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-text-light-headings dark:text-dark-headings">
                                {{ $shortlistedCount }} candidates have been shortlisted.</p>
                            <p class="text-xs text-text-light-body dark:text-dark-body">Ready for final selection</p>
                        </div>
                    </div>
                @endif

                @if ($selectedCount > 0)
                    <div
                        class="flex items-center gap-4 rounded-lg p-3 bg-card-light dark:bg-card-dark hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <div
                            class="flex items-center justify-center size-10 rounded-full bg-orange-100 dark:bg-orange-900/50">
                            <span
                                class="material-symbols-outlined text-orange-600 dark:text-orange-400">emoji_events</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-text-light-headings dark:text-dark-headings">
                                {{ $selectedCount }} candidates have been selected.</p>
                            <p class="text-xs text-text-light-body dark:text-dark-body">Final selections made</p>
                        </div>
                    </div>
                @endif

                @if ($selectionPendingCount > 0)
                    <div
                        class="flex items-center gap-4 rounded-lg p-3 bg-card-light dark:bg-card-dark hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <div
                            class="flex items-center justify-center size-10 rounded-full bg-yellow-100 dark:bg-yellow-900/50">
                            <span class="material-symbols-outlined text-yellow-600 dark:text-yellow-400">schedule</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-text-light-headings dark:text-dark-headings">
                                {{ $selectionPendingCount }} applications pending review.</p>
                            <p class="text-xs text-text-light-body dark:text-dark-body">Awaiting your action</p>
                        </div>
                    </div>
                @endif

                @if ($appliedCount === 0)
                    <div
                        class="flex items-center gap-4 rounded-lg p-3 bg-card-light dark:bg-card-dark hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <div class="flex items-center justify-center size-10 rounded-full bg-gray-100 dark:bg-gray-900/50">
                            <span class="material-symbols-outlined text-gray-600 dark:text-gray-400">info</span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-text-light-headings dark:text-dark-headings">No applications
                                received yet.</p>
                            <p class="text-xs text-text-light-body dark:text-dark-body">Applications will appear here when
                                students apply</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <script>
            // Function to close notifications
            function closeNotification(id) {
                const notification = document.getElementById(id);
                if (notification) {
                    notification.style.display = 'none';
                }
            }

            // Auto-hide notifications after 5 seconds
            document.addEventListener('DOMContentLoaded', function() {
                setTimeout(function() {
                    const notifications = document.querySelectorAll('.notification');
                    notifications.forEach(function(notification) {
                        notification.style.display = 'none';
                    });
                }, 5000);
            });
        </script>
    </body>

    </html>

@endsection
