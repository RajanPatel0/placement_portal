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
        <title>Student Application Dashboard</title>
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
                            "primary": "#6366F1",
                            "primary-light": "#818CF8",
                            "secondary": "#EC4899",
                            "accent": "#10B981",
                            "background-light": "#F8F9FA",
                            "background-dark": "#101622",
                            "text-light-headings": "#111827",
                            "text-light-body": "#4B5563",
                            "text-dark-headings": "#F9FAFB",
                            "text-dark-body": "#D1D5DB",
                            "card-light": "#FFFFFF",
                            "card-dark": "#1C2436",
                            "border-light": "#E5E7EB",
                            "border-dark": "#374151"
                        },
                        fontFamily: {
                            "display": ["Inter", "sans-serif"]
                        },
                        borderRadius: {
                            "DEFAULT": "0.5rem",
                            "lg": "0.75rem",
                            "xl": "1rem",
                            "2xl": "1.25rem",
                            "full": "9999px"
                        },
                        boxShadow: {
                            'soft': '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05)',
                            'medium': '0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)',
                        }
                    },
                },
            }
        </script>
    </head>

    <body class="font-display">
        <div
            class="relative min-h-screen w-full bg-background-light dark:bg-background-dark group/design-root overflow-x-hidden">


            <!-- Main Content -->
            <div class="flex flex-col px-4 pt-6 pb-24">
                <!-- Overall Performance Section -->
                <div class="flex flex-col gap-6 mb-8">
                    <h2
                        class="text-text-light-headings dark:text-dark-headings text-2xl font-bold leading-tight tracking-tight">
                        Overall Performance
                    </h2>
                    <div class="grid grid-cols-3 gap-4">
                        <!-- Application Funnel -->
                        <div
                            class="flex flex-col gap-4 rounded-2xl p-4 bg-card-light dark:bg-card-dark shadow-medium border border-border-light/50 dark:border-border-dark/50">
                            <p class="font-semibold text-text-light-headings dark:text-dark-headings text-sm">Application
                                Funnel</p>
                            <div class="flex items-end gap-3 h-32">
                                <div class="flex flex-col items-center gap-1.5 h-full justify-end w-1/3">
                                    <div class="text-xs font-bold text-primary dark:text-primary-light">
                                        {{ number_format($totalApplications) }}</div>
                                    <div class="w-full h-full bg-primary/10 dark:bg-primary/20 rounded-t-md flex items-end">
                                        <div class="w-full bg-gradient-to-t from-primary to-primary-light rounded-t-md"
                                            style="height: 100%"></div>
                                    </div>
                                    <div
                                        class="text-[10px] text-center font-medium text-text-light-body dark:text-dark-body">
                                        Applied</div>
                                </div>
                                <div class="flex flex-col items-center gap-1.5 h-full justify-end w-1/3">
                                    <div class="text-xs font-bold text-primary dark:text-primary-light">
                                        {{ number_format($shortlistedCount) }}</div>
                                    <div class="w-full h-full bg-primary/10 dark:bg-primary/20 rounded-t-md flex items-end">
                                        <div class="w-full bg-gradient-to-t from-primary to-primary-light rounded-t-md"
                                            style="height: {{ $totalApplications > 0 ? ($shortlistedCount / $totalApplications) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                    <div
                                        class="text-[10px] text-center font-medium text-text-light-body dark:text-dark-body">
                                        Shortlisted</div>
                                </div>
                                <div class="flex flex-col items-center gap-1.5 h-full justify-end w-1/3">
                                    <div class="text-xs font-bold text-primary dark:text-primary-light">
                                        {{ number_format($selectedCount) }}</div>
                                    <div class="w-full h-full bg-primary/10 dark:bg-primary/20 rounded-t-md flex items-end">
                                        <div class="w-full bg-gradient-to-t from-primary to-primary-light rounded-t-md"
                                            style="height: {{ $totalApplications > 0 ? ($selectedCount / $totalApplications) * 100 : 0 }}%">
                                        </div>
                                    </div>
                                    <div
                                        class="text-[10px] text-center font-medium text-text-light-body dark:text-dark-body">
                                        Selected</div>
                                </div>
                            </div>
                        </div>

                        <!-- Shortlist Rate -->
                        <div
                            class="flex flex-col gap-4 rounded-2xl p-4 bg-card-light dark:bg-card-dark shadow-medium border border-border-light/50 dark:border-border-dark/50">
                            <p class="font-semibold text-text-light-headings dark:text-dark-headings text-sm">Shortlist Rate
                            </p>
                            <div class="flex items-center justify-center flex-grow">
                                <div class="relative w-24 h-24">
                                    <svg class="w-full h-full" viewBox="0 0 36 36">
                                        <path class="text-gray-200 dark:text-gray-700"
                                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                            fill="none" stroke="currentColor" stroke-width="3.5"></path>
                                        <path class="text-accent" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831"
                                            fill="none" stroke="currentColor"
                                            stroke-dasharray="{{ $shortlistRate }}, 100" stroke-linecap="round"
                                            stroke-width="3.5"></path>
                                    </svg>
                                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                                        <span
                                            class="text-2xl font-bold text-text-light-headings dark:text-dark-headings">{{ $shortlistRate }}%</span>
                                        <span
                                            class="text-[10px] text-text-light-body dark:text-dark-body mt-0.5">Shortlisted</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Selection Rate -->
                        <div
                            class="flex flex-col gap-4 rounded-2xl p-4 bg-card-light dark:bg-card-dark shadow-medium border border-border-light/50 dark:border-border-dark/50">
                            <p class="font-semibold text-text-light-headings dark:text-dark-headings text-sm">Selection Rate
                            </p>
                            <div class="flex items-center justify-center flex-grow">
                                <div class="relative w-24 h-24">
                                    <svg class="w-full h-full" viewBox="0 0 36 36">
                                        <path class="text-gray-200 dark:text-gray-700"
                                            d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"
                                            fill="none" stroke="currentColor" stroke-width="3.5"></path>
                                        <path class="text-accent" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831"
                                            fill="none" stroke="currentColor"
                                            stroke-dasharray="{{ $selectionRate }}, 100" stroke-linecap="round"
                                            stroke-width="3.5"></path>
                                    </svg>
                                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                                        <span
                                            class="text-2xl font-bold text-text-light-headings dark:text-dark-headings">{{ $selectionRate }}%</span>
                                        <span
                                            class="text-[10px] text-text-light-body dark:text-dark-body mt-0.5">Selected</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Companies Overview Section -->
                <div class="flex items-center justify-between pb-4">
                    <h2
                        class="text-text-light-headings dark:text-dark-headings text-2xl font-bold leading-tight tracking-tight">
                        Companies Overview
                    </h2>
                    <button
                        class="flex cursor-pointer items-center justify-center rounded-full h-9 w-9 text-text-light-body dark:text-dark-body hover:bg-gray-200 dark:hover:bg-card-dark">
                        <span class="material-symbols-outlined text-xl">filter_list</span>
                    </button>
                </div>

                <div class="flex flex-col gap-4">
                    @foreach ($companyStats as $company)
                        <div
                            class="flex flex-col gap-4 rounded-2xl p-4 bg-card-light dark:bg-card-dark shadow-soft border border-border-light/50 dark:border-border-dark/50">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="h-10 w-10 rounded-lg bg-primary/10 flex items-center justify-center">
                                        <span class="material-symbols-outlined text-primary">business</span>
                                    </div>
                                    <div class="flex flex-col">
                                        <p class="font-semibold text-text-light-headings dark:text-dark-headings">
                                            {{ $company->company_name }}</p>
                                        <p class="text-xs text-text-light-body dark:text-dark-body">
                                            {{ $company->job_title }}</p>
                                    </div>
                                </div>
                                <a href="{{ route('admin.drive.stats.detail', $company->id) }}">
                                    <span
                                        class="material-symbols-outlined text-text-light-body dark:text-dark-body">chevron_right</span>
                                </a>

                            </div>
                            <div class="grid grid-cols-3 gap-3 text-center">
                                <div
                                    class="flex flex-col items-center justify-center gap-1 rounded-lg bg-background-light dark:bg-background-dark p-2">
                                    <p class="text-lg font-bold text-text-light-headings dark:text-dark-headings">
                                        {{ number_format($company->total_applied) }}</p>
                                    <p class="text-xs font-medium text-text-light-body dark:text-dark-body">Applied</p>
                                </div>
                                <div
                                    class="flex flex-col items-center justify-center gap-1 rounded-lg bg-background-light dark:bg-background-dark p-2">
                                    <p class="text-lg font-bold text-text-light-headings dark:text-dark-headings">
                                        {{ number_format($company->shortlisted) }}</p>
                                    <p class="text-xs font-medium text-text-light-body dark:text-dark-body">Shortlisted</p>
                                </div>
                                <div
                                    class="flex flex-col items-center justify-center gap-1 rounded-lg bg-background-light dark:bg-background-dark p-2">
                                    <p class="text-lg font-bold text-text-light-headings dark:text-dark-headings">
                                        {{ number_format($company->selected) }}</p>
                                    <p class="text-xs font-medium text-text-light-body dark:text-dark-body">Selected</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>


        </div>

        <!-- JavaScript for dynamic functionality -->
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Add click handlers for company cards
                document.querySelectorAll('.company-card').forEach(card => {
                    card.addEventListener('click', function() {
                        const companyId = this.dataset.companyId;
                        // Implement company details view
                        console.log('View details for company:', companyId);
                    });
                });

                // Notification button handler
                document.querySelector('button .material-symbols-outlined').closest('button').addEventListener('click',
                    function() {
                        // Implement notifications view
                        console.log('Show notifications');
                    });

                // Filter button handler
                document.querySelector('button .material-symbols-outlined.text-xl').closest('button').addEventListener(
                    'click',
                    function() {
                        // Implement filter functionality
                        console.log('Show filters');
                    });
            });
        </script>
    </body>

    </html>
@endsection
