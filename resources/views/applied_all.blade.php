@extends('layouts.base')

@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Placement Drives</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect" />
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&amp;display=swap" rel="stylesheet" />
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
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>

</head>


<div class="flex items-center justify-between p-4 border-b border-subtle-light/20 dark:border-subtle-dark/20">
    <button onclick="window.history.back()" class="p-2 rounded-full hover:bg-primary/10 active:bg-primary/20">
        <span class="material-symbols-outlined">
            arrow_back_ios_new
        </span>
    </button>
    <h1 class="text-xl font-bold">Placement Drives</h1>
    <div class="w-8"></div>
</div>
<main class="flex-1 overflow-y-auto pb-5 mb-4">
    <ul class="divide-y divide-subtle-light/20 dark:divide-subtle-dark/20">
                   @if($applications->isEmpty())
       
          <li class="p-4 text-center text-gray-500 dark:text-gray-400">
                       No any Application for drive.
                         </li>
                  @else
        @foreach($applications as $application)
        <a href="{{ route('application.detail', $application->id) }}" style="text-decoration: none;">
            <li class="flex items-center gap-4 p-4 hover:bg-primary/5 dark:hover:bg-primary/10 cursor-pointer">
                <div class="relative">
                    <img alt="Company logo" class="w-14 h-14 rounded-lg object-cover"
                        src="https://lh3.googleusercontent.com/aida-public/AB6AXuBgdRnoU0-z0Z5VVx7gMO_S1z8u4hZhQbePydFXAE2Mp_EOlYn_lP_Dv6W9fl8KqhbpGwJIY72A4HlW3l62VRIghjG_7a-FzaQoxN4t2TwbuvM1MHdbKGw7KHXTcQByjmdK642ZAyffmJxG9BaDAkyi_wFFGims7PJryf5NIhIZ8tV9DmeORswzM5WF1zYaHDoFs5SP-gVNLPniAJqzXSn3x4NhyBOzm3gUfz6zkY0rveymp8r0nQDGEaFfkN-cqj8DL2GBlcY1f38" />
                    <span class="absolute -top-1 -right-1 flex h-5 w-5 items-center justify-center rounded-full 
                            @if($application->application_status === 'applied') bg-blue-500
                            @elseif($application->application_status === 'shortlisted') bg-yellow-500
                            @elseif($application->application_status === 'selected') bg-green-500
                            @else bg-red-500 @endif
                            text-white text-xs font-bold">
                        @if($application->application_status === 'applied')
                        <span class="material-symbols-outlined text-sm">schedule</span>
                        @elseif($application->application_status === 'shortlisted')
                        <span class="material-symbols-outlined text-sm">hourglass_top</span>
                        @elseif($application->application_status === 'selected')
                        <span class="material-symbols-outlined text-sm">check</span>
                        @else
                        <span class="material-symbols-outlined text-sm">close</span>
                        @endif
                    </span>
                </div>
                <div class="flex-1">
                    <p class="font-semibold text-foreground-light dark:text-foreground-dark">
                        Placement Drive : {{ ucfirst($application->company_name) }}
                    </p>
                    <p class="text-sm text-subtle-light dark:text-subtle-dark">
                        Applied: {{ \Carbon\Carbon::parse($application->applied_at)->format('d M Y') }}
                        @if($application->shortlisted_at)
                        | Shortlisted: {{ \Carbon\Carbon::parse($application->shortlisted_at)->format('d M Y') }}
                        @endif
                        @if($application->selected_at)
                        | Selected: {{ \Carbon\Carbon::parse($application->selected_at)->format('d M Y') }}
                        @endif
                    </p>
                    @if($application->remarks)
                    <p class="text-xs text-subtle-light dark:text-subtle-dark mt-1">
                        Remarks: {{ $application->remarks }}
                    </p>
                    @endif
                </div>
                <div class="text-right">
                    @if($application->application_status === 'applied')
                    <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Applied</p>
                    @elseif($application->application_status === 'shortlisted')
                    <p class="text-sm font-medium text-yellow-600 dark:text-yellow-400">Shortlisted</p>
                    @elseif($application->application_status === 'selected')
                    <p class="text-sm font-medium text-green-600 dark:text-green-400">Selected</p>
                    @else
                    <p class="text-sm font-medium text-red-600 dark:text-red-400">Rejected</p>
                    @endif

                </div>
            </li>
           
        </a>
        @endforeach
         @endif

    </ul>
</main>



@endsection