@extends('dashboardLayouts.base')

@section('title', 'Register')

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
        <meta charset="utf-8" />
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <title>{{ $driveInfo->company_name }} - Drive Statistics</title>
        <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&amp;display=swap"
            rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
        <style>
            .material-symbols-outlined {
                font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
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
            <!-- Header -->
            <div
                class="flex flex-col gap-2 bg-background-light dark:bg-background-dark p-4 pb-2 sticky top-0 z-10 border-b border-border-light dark:border-border-dark">
                <div class="flex h-12 items-center justify-between">
                    <div class="flex items-center gap-3">

                        <div class="bg-center bg-no-repeat aspect-square bg-cover rounded-full size-10"
                            data-alt="User avatar of Alex"
                            style='background-image: url("https://lh3.googleusercontent.com/aida-public/AB6AXuAPnRwIMs51H4BBuWddveYKHqkv5EKEMLdJ0Z33BDOl77Tze6DMoEh3crCHuo6nAEud-TSwKz5wmj9g_uMyNR2oYZZkhaNkFuaV5bRQSYuBRBg_w28rMfBBI4ivIxQLpcTFj8aqNPXi0kF6UyjMjiLiFCSLnmKbafQmwa9FxIXFauHpNUlwKyevaRxgFAxU_9jti3cAe91r0WwVmohAqgiQadsc2zFauG9jVGFccbZFqho7cf_BqusiSP3aXgpPqN1EQ_uQDVNOxsfn");'>
                        </div>
                        <div>
                            <p class="text-text-light-headings dark:text-dark-headings text-lg font-bold leading-tight">
                                {{ $driveInfo->company_name }} - Drive Stats
                            </p>
                            <p class="text-text-light-body dark:text-dark-body text-sm">{{ $driveInfo->job_title }}</p>
                        </div>
                    </div>
                    <div class="flex w-12 items-center justify-end relative">
                        <a href="{{ route('admin.drive.stats') }}"
                            class="flex items-center gap-2 text-text-light-body dark:text-dark-body hover:text-primary">
                            <span class="material-symbols-outlined">arrow_back</span>
                            <span>Back</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 p-4">
                <div
                    class="flex flex-col gap-2 rounded-xl p-4 bg-card-light dark:bg-card-dark shadow-sm border border-border-light dark:border-border-dark">
                    <p class="text-text-light-body dark:text-dark-body text-sm font-medium leading-normal">Total Applied</p>
                    <p
                        class="text-text-light-headings dark:text-dark-headings tracking-tight text-3xl font-bold leading-tight">
                        {{ number_format($driveStats->total_applied) }}</p>
                    <div class="flex items-center gap-1">
                        @if ($appliedGrowth >= 0)
                            <span class="material-symbols-outlined text-green-500 text-base">arrow_upward</span>
                            <p class="text-green-500 text-sm font-medium leading-normal">{{ $appliedGrowth }}%</p>
                        @else
                            <span class="material-symbols-outlined text-red-500 text-base">arrow_downward</span>
                            <p class="text-red-500 text-sm font-medium leading-normal">{{ abs($appliedGrowth) }}%</p>
                        @endif
                    </div>
                </div>

                <a href="{{ route('admin.drive.stats.shortlisted', $driveInfo->id) }}">
                    <div
                        class="flex flex-col gap-2 rounded-xl p-4 bg-card-light dark:bg-card-dark shadow-sm border border-border-light dark:border-border-dark">
                        <p class="text-text-light-body dark:text-dark-body text-sm font-medium leading-normal">Shortlisted
                        </p>
                        <p
                            class="text-text-light-headings dark:text-dark-headings tracking-tight text-3xl font-bold leading-tight">
                            {{ number_format($driveStats->shortlisted) }}</p>
                        <div class="flex items-center gap-1">
                            @if ($shortlistedGrowth >= 0)
                                <span class="material-symbols-outlined text-green-500 text-base">arrow_upward</span>
                                <p class="text-green-500 text-sm font-medium leading-normal">{{ $shortlistedGrowth }}%</p>
                            @else
                                <span class="material-symbols-outlined text-red-500 text-base">arrow_downward</span>
                                <p class="text-red-500 text-sm font-medium leading-normal">{{ abs($shortlistedGrowth) }}%
                                </p>
                            @endif
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.drive.stats.selected', $driveInfo->id) }}">
                    <div
                        class="flex flex-col gap-2 rounded-xl p-4 bg-card-light dark:bg-card-dark shadow-sm border border-border-light dark:border-border-dark">
                        <p class="text-text-light-body dark:text-dark-body text-sm font-medium leading-normal">Selected</p>
                        <p
                            class="text-text-light-headings dark:text-dark-headings tracking-tight text-3xl font-bold leading-tight">
                            {{ number_format($driveStats->selected) }}</p>
                        <div class="flex items-center gap-1">
                            @if ($selectedGrowth >= 0)
                                <span class="material-symbols-outlined text-green-500 text-base">arrow_upward</span>
                                <p class="text-green-500 text-sm font-medium leading-normal">{{ $selectedGrowth }}%</p>
                            @else
                                <span class="material-symbols-outlined text-red-500 text-base">arrow_downward</span>
                                <p class="text-red-500 text-sm font-medium leading-normal">{{ abs($selectedGrowth) }}%</p>
                            @endif
                        </div>
                    </div>
                </a>
                <div
                    class="flex flex-col gap-2 rounded-xl p-4 bg-card-light dark:bg-card-dark shadow-sm border border-border-light dark:border-border-dark">
                    <p class="text-text-light-body dark:text-dark-body text-sm font-medium leading-normal">Rejected</p>
                    <p
                        class="text-text-light-headings dark:text-dark-headings tracking-tight text-3xl font-bold leading-tight">
                        {{ number_format($driveStats->rejected) }}</p>
                    <div class="flex items-center gap-1">
                        <span class="material-symbols-outlined text-gray-500 text-base">remove</span>
                        <p class="text-gray-500 text-sm font-medium leading-normal">-</p>
                    </div>
                </div>
            </div>

            <h2
                class="text-text-light-headings dark:text-dark-headings text-xl font-bold leading-tight tracking-tight px-4 pb-2 pt-4">
                Analytics</h2>

            <div class="flex flex-col lg:flex-row gap-4 px-4 pb-4">
                <!-- Drive Details Card -->
                <div
                    class="flex min-w-72 flex-1 flex-col gap-4 rounded-xl border border-border-light dark:border-border-dark p-5 bg-card-light dark:bg-card-dark shadow-sm">
                    <div class="flex items-center justify-between">
                        <p class="text-text-light-headings dark:text-dark-headings text-base font-semibold leading-normal">
                            Placement Drive Details</p>
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
                                    {{ $driveInfo->company_name }} - {{ $driveInfo->job_title }}</p>
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
                                    {{ \Carbon\Carbon::parse($driveInfo->drive_date)->format('jS M, Y') }}
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
                                    {{ $driveInfo->location ?? 'Not specified' }}</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 dark:bg-primary/20">
                                <span class="material-symbols-outlined text-primary text-2xl">work</span>
                            </div>
                            <div class="flex flex-col">
                                <p class="text-text-light-body dark:text-dark-body text-sm">Package</p>
                                <p class="text-text-light-headings dark:text-dark-headings font-medium">
                                    {{ $driveInfo->package_offered ? '₹' . number_format($driveInfo->package_offered, 2) . ' LPA' : 'Not disclosed' }}
                                </p>
                            </div>
                        </div>
                        <div class="flex items-start gap-3">
                            <div
                                class="flex h-10 w-10 items-center justify-center rounded-lg bg-primary/10 dark:bg-primary/20">
                                <span class="material-symbols-outlined text-primary text-2xl">group</span>
                            </div>
                            <div class="flex flex-col">
                                <p class="text-text-light-body dark:text-dark-body text-sm">Vacancies</p>
                                <p class="text-text-light-headings dark:text-dark-headings font-medium">
                                    {{ $driveInfo->vacancies ?? 'Not specified' }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Selection Funnel Card -->
                <div
                    class="flex min-w-72 flex-1 flex-col gap-4 rounded-xl border border-border-light dark:border-border-dark p-5 bg-card-light dark:bg-card-dark shadow-sm">
                    <p class="text-text-light-headings dark:text-dark-headings text-base font-semibold leading-normal">
                        Selection Funnel</p>
                    <div class="flex flex-col sm:flex-row items-center gap-6">
                        <div class="relative flex items-center justify-center size-40">
                            <svg class="size-full transform -rotate-90" viewBox="0 0 36 36">
                                <circle class="stroke-current text-gray-200 dark:text-gray-700" cx="18"
                                    cy="18" fill="none" r="16" stroke-width="3"></circle>
                                <circle class="stroke-current text-primary" cx="18" cy="18" fill="none"
                                    r="16" stroke-dasharray="100, 100" stroke-dashoffset="0" stroke-width="3"></circle>
                                <circle class="stroke-current text-secondary" cx="18" cy="18"
                                    fill="none" r="16" stroke-dasharray="{{ $shortlistRate }}, 100"
                                    stroke-dashoffset="0" stroke-width="3"></circle>
                                <circle class="stroke-current text-orange-400" cx="18" cy="18"
                                    fill="none" r="16" stroke-dasharray="{{ $selectionRate }}, 100"
                                    stroke-dashoffset="{{ -$shortlistRate }}" stroke-width="3"></circle>
                            </svg>
                            <div class="absolute flex flex-col items-center">
                                <span
                                    class="text-2xl font-bold text-text-light-headings dark:text-dark-headings">{{ $driveStats->selected }}</span>
                                <span class="text-sm text-text-light-body dark:text-dark-body">Selected</span>
                            </div>
                        </div>
                        <div class="flex flex-col gap-3">
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-primary"></div>
                                <span class="text-sm font-medium text-text-light-body dark:text-dark-body">Applied
                                    ({{ $driveStats->total_applied }})</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-secondary"></div>
                                <span class="text-sm font-medium text-text-light-body dark:text-dark-body">Shortlisted
                                    ({{ $driveStats->shortlisted }})</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="w-3 h-3 rounded-full bg-orange-400"></div>
                                <span class="text-sm font-medium text-text-light-body dark:text-dark-body">Selected
                                    ({{ $driveStats->selected }})</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Activity -->
            <h2
                class="text-text-light-headings dark:text-dark-headings text-xl font-bold leading-tight tracking-tight px-4 pb-3 pt-4">
                Recent Activity</h2>
            <div class="flex flex-col gap-3 px-4 pb-24">
                @forelse($recentActivities as $activity)
                    <div
                        class="flex items-center gap-4 rounded-lg p-3 bg-card-light dark:bg-card-dark hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <div
                            class="flex items-center justify-center size-10 rounded-full 
                    @if ($activity->activity_type == 'selected') bg-green-100 dark:bg-green-900/50
                    @elseif($activity->activity_type == 'shortlisted') bg-blue-100 dark:bg-blue-900/50
                    @else bg-orange-100 dark:bg-orange-900/50 @endif">
                            <span
                                class="material-symbols-outlined 
                        @if ($activity->activity_type == 'selected') text-green-600 dark:text-green-400
                        @elseif($activity->activity_type == 'shortlisted') text-blue-600 dark:text-blue-400
                        @else text-orange-600 dark:text-orange-400 @endif">
                                @if ($activity->activity_type == 'selected')
                                    task_alt
                                @elseif($activity->activity_type == 'shortlisted')
                                    how_to_reg
                                @else
                                    person_add
                                @endif
                            </span>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-text-light-headings dark:text-dark-headings">
                                {{ $activity->student_name }}
                                @if ($activity->activity_type == 'selected')
                                    accepted the offer
                                @elseif($activity->activity_type == 'shortlisted')
                                    was shortlisted
                                @else
                                    applied for the position
                                @endif
                            </p>
                            <p class="text-xs text-text-light-body dark:text-dark-body">
                                {{ \Carbon\Carbon::parse($activity->applied_at)->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                @empty
                    <div class="flex items-center justify-center rounded-lg p-6 bg-card-light dark:bg-card-dark">
                        <p class="text-text-light-body dark:text-dark-body">No recent activity</p>
                    </div>
                @endforelse
            </div>

            <!-- Floating Action Button -->
            <button
                class="fixed bottom-6 right-6 flex items-center justify-center w-14 h-14 bg-primary text-white rounded-full shadow-lg hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-primary/50">
                <span class="material-symbols-outlined text-3xl">add</span>
            </button>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Add any JavaScript functionality here
                console.log('Drive stats detail page loaded');
            });
        </script>
    </body>

    </html>
@endsection
