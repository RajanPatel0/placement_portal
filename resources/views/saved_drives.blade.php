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
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&amp;display=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    <script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#4F46E5",
                        "primary-light": "#A5B4FC",
                        "accent": "#F59E0B",
                        "background-light": "#F7F8FC",
                        "background-dark": "#0B1120",
                        "foreground-light": "#0F172A",
                        "foreground-dark": "#F8FAFC",
                        "subtle-light": "#64748B",
                        "subtle-dark": "#94A3B8",
                        "card-light": "#FFFFFF",
                        "card-dark": "#1E293B"
                    },
                    fontFamily: {
                        "display": ["Poppins", "sans-serif"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.75rem",
                        "lg": "1rem",
                        "xl": "1.25rem",
                        "2xl": "1.5rem",
                        "full": "9999px"
                    },
                    boxShadow: {
                        'DEFAULT': '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05)',
                        'md': '0 10px 15px -3px rgba(79, 70, 229, 0.07), 0 4px 6px -4px rgba(79, 70, 229, 0.07)',
                        'lg': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1)',
                    }
                },
            },
        }
    </script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            min-height: max(884px, 100dvh);
        }

        .drive-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .drive-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.1), 0 4px 6px -4px rgba(79, 70, 229, 0.1);
        }
    </style>
    <style>
        body {
            min-height: max(884px, 100dvh);
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark font-display text-foreground-light dark:text-foreground-dark">
    <div class="flex flex-col min-h-screen">
        <header class="sticky top-0 pb-2 z-20 bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-lg">
            <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-800">
                <h1 class="text-2xl font-bold">Placement Drives</h1>

            </div>
        </header>
        @php
        $status = strtolower(trim($drive->status ?? ''));
        $statusClasses = match ($status) {
        'ongoing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300',
        'upcoming' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300',
        'closed' => 'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-300',
        'selected' => 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300',
        'rejected' => 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300',
        'completed' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300',
        default => 'bg-slate-100 text-slate-800 dark:bg-slate-900/50 dark:text-slate-300',
        };
        @endphp



        <main class="flex-1 overflow-y-auto p-1">
            <div class="space-y-5">
                   @if($savedDrives->isEmpty())
       
          <li class="p-4 text-center text-gray-500 dark:text-gray-400">
                       No any Saved drive.
                    </li>
        @else
                @foreach($savedDrives as $drive)
                <a class="block drive-card" href="{{ route('drive.detail', $drive->id) }}"
                    style="text-decoration: none;">

                    <div
                        class="bg-card-light dark:bg-card-dark rounded-2xl shadow-md p-3 mb-4 grid grid-cols-3 gap-4 items-center">
                        <div class="col-span-2">
                            <h2 class="text-lg font-bold text-foreground-light dark:text-foreground-dark truncate">
                                {{ $drive->company_name }}
                            </h2>
                            <p class="text-md font-semibold text-primary dark:text-primary-light">
                                {{ $drive->job_title }}
                            </p>
                            <p class="text-sm text-subtle-light dark:text-subtle-dark mt-2">
                                ₹{{ $drive->package_offered }} LPA
                            </p>
                        </div>
                        <div class="flex flex-col items-end justify-center">
                            <img alt="Tech Innovators Inc. logo" class="w-12 h-12 rounded-lg object-cover mb-2"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuDNp7B3_YkqdT_wf1VaRI1Yp5xi5oUj4Y5TIizUE_dbJwDLRUvPfH0EGV1nk9XnElb1gRR6P3PbKtne0Zqrh3MTjDirS0tdA6kS0b_S6vACzFYnILVOh0aMH_4bTGJ44X1NTjwCMgi5-ZjczKqMBkEgHSV_ROormz7kr4gml2xdbg0LtgqS4L6Vv5iKtfttPrjwIs9IJaLLbcs4q4hed1D_orq5wLAvsN_18bAGrEZdTNRyLTofvYwoyDVhirzhwFeC-ckbLM6rYHk" />
                            <span
                                class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                                {{ $drive->status }}
                            </span>
                        </div>
                    </div>
                </a>

                @endforeach
                @endif

            </div>
        </main>

    </div>

</body>

</html>

@endsection