@extends('layouts.base')

@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Application Details</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <script id="tailwind-config">
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
                    "subtle-light": "#6b7280",
                    "subtle-dark": "#9ca3af",
                    "card-light": "#ffffff",
                    "card-dark": "#1a2530",
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
    body {
        font-family: 'Inter', sans-serif;
    }

    .timeline-item:not(:last-child)::before {
        content: "";
        position: absolute;
        left: 20px;
        top: 40px;
        width: 2px;
        height: calc(100% - 24px);
        background-color: #e5e7eb;
    }

    .dark .timeline-item:not(:last-child)::before {
        background-color: #4b5563;
    }
    </style>
    <style>
    body {
        min-height: max(884px, 100dvh);
    }
    </style>
</head>


<div class="flex items-center justify-between p-4 border-b border-subtle-light/20 dark:border-subtle-dark/20">
    <button onclick="window.history.back()" class="p-2 rounded-full hover:bg-primary/10 active:bg-primary/20">
        <span class="material-symbols-outlined">
            arrow_back_ios_new
        </span>
    </button>
    <h1 class="text-xl font-bold">Application Details</h1>
    <div class="w-8"></div>
</div>
<main class="flex-1 overflow-y-auto  space-y-6 pb-12">
    <!-- Application Header Card -->
    <div class="bg-card-light dark:bg-card-dark p-5  shadow-sm">
        <div class="flex items-start gap-4">
            <div class="flex-1 space-y-2">
                <div class="flex justify-between items-start">
                    <div>
                        <p class="text-sm text-subtle-light dark:text-subtle-dark">Application No.
                            {{ $application->id }}
                        </p>
                        <p class="text-lg font-bold text-primary">Placement Drive
                            : {{ $application->company_name }}</p>
                    </div>
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold 
                                @if($application->application_status === 'applied') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300
                                @elseif($application->application_status === 'shortlisted') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300
                                @elseif($application->application_status === 'selected') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300
                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 @endif whitespace-nowrap">
                        {{ ucfirst($application->application_status) }}
                    </span>
                </div>
                <p class="text-sm text-subtle-light dark:text-subtle-dark">Placement Drive No.
                    {{ $application->placement_drive_id }}
                </p>
                @php
                $user = DB::table('users')->where('id', Auth::id())->first();
                @endphp
                <p class="text-sm text-subtle-light dark:text-subtle-dark">Student :
                    {{ $user->name }}
                </p>
            </div>
        </div>
    </div>

    <!-- Application Timeline -->
    <div class="bg-card-light dark:bg-card-dark p-5  shadow-sm space-y-4">
        <h3
            class="text-lg font-semibold border-b pb-2 border-subtle-light/20 dark:border-subtle-dark/20 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">timeline</span>
            Application Timeline
        </h3>
        <div class="space-y-4 pt-2">
            <!-- Applied Step -->
            <div class="flex items-start">
                <div class="flex flex-col items-center mr-4">
                    <div class="flex items-center justify-center w-8 h-8 bg-blue-100 dark:bg-blue-900 rounded-full">
                        <span
                            class="material-symbols-outlined text-blue-600 dark:text-blue-300 text-base">description</span>
                    </div>
                    @if($application->shortlisted_at || $application->selected_at)
                    <div class="w-px h-6 bg-subtle-light/50 dark:bg-subtle-dark/50"></div>
                    @endif
                </div>
                <div class="flex-1">
                    <p class="font-semibold">Applied</p>
                    <p class="text-sm text-subtle-light dark:text-subtle-dark">
                        {{ \Carbon\Carbon::parse($application->applied_at)->format('d M Y, h:i A') }}
                    </p>
                </div>
            </div>

            <!-- Shortlisted Step (Conditional) -->
            @if($application->shortlisted_at)
            <div class="flex items-start">
                <div class="flex flex-col items-center mr-4">
                    <div class="flex items-center justify-center w-8 h-8 bg-purple-100 dark:bg-purple-900 rounded-full">
                        <span
                            class="material-symbols-outlined text-purple-600 dark:text-purple-300 text-base">playlist_add_check</span>
                    </div>
                    @if($application->selected_at)
                    <div class="w-px h-6 bg-subtle-light/50 dark:bg-subtle-dark/50"></div>
                    @endif
                </div>
                <div class="flex-1">
                    <p class="font-semibold">Shortlisted</p>
                    <p class="text-sm text-subtle-light dark:text-subtle-dark">
                        {{ \Carbon\Carbon::parse($application->shortlisted_at)->format('d M Y, h:i A') }}
                    </p>
                </div>
            </div>
            @endif

            <!-- Selected Step (Conditional) -->
            @if($application->selected_at)
            <div class="flex items-start">
                <div class="flex flex-col items-center mr-4">
                    <div class="flex items-center justify-center w-8 h-8 bg-green-100 dark:bg-green-900 rounded-full">
                        <span
                            class="material-symbols-outlined text-green-600 dark:text-green-300 text-base">check_circle</span>
                    </div>
                </div>
                <div class="flex-1">
                    <p class="font-semibold">Selected</p>
                    <p class="text-sm text-subtle-light dark:text-subtle-dark">
                        {{ \Carbon\Carbon::parse($application->selected_at)->format('d M Y, h:i A') }}
                    </p>
                </div>
            </div>
            @endif

            <!-- Rejected Step (Conditional) -->
            @if($application->application_status === 'rejected')
            <div class="flex items-start">
                <div class="flex flex-col items-center mr-4">
                    <div class="flex items-center justify-center w-8 h-8 bg-red-100 dark:bg-red-900 rounded-full">
                        <span class="material-symbols-outlined text-red-600 dark:text-red-300 text-base">cancel</span>
                    </div>
                </div>
                <div class="flex-1">
                    <p class="font-semibold">Rejected</p>
                    <p class="text-sm text-subtle-light dark:text-subtle-dark">
                        {{ \Carbon\Carbon::parse($application->updated_at)->format('d M Y, h:i A') }}
                    </p>
                </div>
            </div>
            @endif
        </div>
    </div>

    <!-- Application Responses (Conditional) -->
    @if($application->application_responses)
    <div class="bg-card-light dark:bg-card-dark p-5  shadow-sm space-y-4">
        <h3
            class="text-lg font-semibold border-b pb-2 border-subtle-light/20 dark:border-subtle-dark/20 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">question_answer</span>
            Application Responses
        </h3>
        @php
        $responses = json_decode($application->application_responses, true);
        @endphp
        @if($responses && is_array($responses))
        @foreach($responses as $question => $answer)
        <div>
            <p class="font-semibold text-subtle-light dark:text-subtle-dark">{{ $question }}</p>
            <p class="mt-1">{{ $answer }}</p>
        </div>
        @endforeach
        @else
        <p class="text-subtle-light dark:text-subtle-dark">No application responses available.</p>
        @endif
    </div>
    @endif

    <!-- Remarks (Conditional) -->
    @if($application->remarks)
    <div class="bg-card-light dark:bg-card-dark p-5 shadow-sm space-y-2">
        <h3
            class="text-lg font-semibold border-b pb-2 border-subtle-light/20 dark:border-subtle-dark/20 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">edit_note</span>
            Remarks
        </h3>
        <p class="pt-2 text-subtle-light dark:text-subtle-dark">{{ $application->remarks }}</p>
    </div>
    @endif

    <!-- Record Timestamps -->
    <div class="bg-card-light dark:bg-card-dark p-5  shadow-sm space-y-3">
        <h3
            class="text-lg font-semibold border-b pb-2 border-subtle-light/20 dark:border-subtle-dark/20 flex items-center gap-2">
            <span class="material-symbols-outlined text-primary">history</span>
            Record Timestamps
        </h3>
        <div class="pt-2">
            <div class="flex justify-between text-sm">
                <p class="font-medium text-subtle-light dark:text-subtle-dark">From submitted At:</p>
                <p>{{ \Carbon\Carbon::parse($application->created_at)->format('d M Y, h:i A') }}</p>
            </div>
            <div class="flex justify-between text-sm">
                <p class="font-medium text-subtle-light dark:text-subtle-dark">Form Updated At:</p>
                <p>{{ \Carbon\Carbon::parse($application->updated_at)->format('d M Y, h:i A') }}</p>
            </div>
        </div>
    </div>
</main>

@endsection