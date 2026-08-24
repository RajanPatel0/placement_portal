@extends('layouts.base')

@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <title>Placement Drive Details</title>
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
                            "background-light": "#F8FAFC",
                            "background-dark": "#0B1120",
                            "foreground-light": "#0F172A",
                            "foreground-dark": "#F8FAFC",
                            "subtle-light": "#64748B",
                            "subtle-dark": "#94A3B8",
                            "card-light": "#FFFFFF",
                            "card-dark": "#1E293B",
                            "success": "#10B981",
                            "warning": "#F59E0B",
                            "info": "#3B82F6"
                        },
                        fontFamily: {
                            "display": ["Poppins", "sans-serif"]
                        },

                        boxShadow: {
                            'DEFAULT': '0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -2px rgba(0, 0, 0, 0.05)',
                            'md': '0 10px 15px -3px rgba(0, 0, 0, 0.07), 0 4px 6px -4px rgba(0, 0, 0, 0.07)',
                            'lg': '0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1)',
                            'xl': '0 25px 50px -12px rgba(0, 0, 0, 0.15)',
                        },
                        animation: {
                            'fade-in': 'fadeIn 0.5s ease-in-out',
                            'slide-up': 'slideUp 0.5s ease-out',
                            'pulse-slow': 'pulse 3s infinite',
                        },
                        keyframes: {
                            fadeIn: {
                                '0%': {
                                    opacity: '0'
                                },
                                '100%': {
                                    opacity: '1'
                                },
                            },
                            slideUp: {
                                '0%': {
                                    transform: 'translateY(20px)',
                                    opacity: '0'
                                },
                                '100%': {
                                    transform: 'translateY(0)',
                                    opacity: '1'
                                },
                            }
                        }
                    },
                },
            }
        </script>
        <style>
            body {
                font-family: 'Poppins', sans-serif;
                background: #f5f6f1;

            }

            .gradient-bg {
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            }

            .card-hover {
                transition: all 0.3s ease;
            }

            .card-hover:hover {
                transform: translateY(-5px);
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            }

            .skill-tag {
                transition: all 0.2s ease;
            }

            .skill-tag:hover {
                transform: scale(1.05);
            }

            .status-indicator {
                position: relative;
                overflow: hidden;
            }

            .status-indicator::after {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
                transform: translateX(-100%);
                animation: shimmer 2s infinite;
            }

            @keyframes shimmer {
                100% {
                    transform: translateX(100%);
                }
            }

            .progress-bar {
                height: 6px;
                border-radius: 3px;
                overflow: hidden;
                background-color: #e2e8f0;
            }

            .progress-fill {
                height: 100%;
                border-radius: 3px;
                background: linear-gradient(90deg, #4F46E5, #7C73E6);
                transition: width 0.5s ease;
            }
        </style>
    </head>

    <body class="">
        <!-- This section will trigger the modal -->
        <div class="notification-container">
            @if (session('success'))
                <div class="notification success" id="successNotification">
                    <button type="button" class="close-btn"
                        onclick="closeNotification('successNotification')">&times;</button>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            @if (session('error'))
                <div class="notification error" id="errorNotification">
                    <button type="button" class="close-btn"
                        onclick="closeNotification('errorNotification')">&times;</button>
                    <p>{{ session('error') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="notification error" id="errorsNotification">
                    <button type="button" class="close-btn"
                        onclick="closeNotification('errorsNotification')">&times;</button>
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
        <div class="containt pb-20" style="max-width: 850px; margin:auto;">
            <!-- Header with gradient background -->


            <div class="flex flex-col items-center justify-center py-8">
                <h1 class="text-4xl md:text-5xl font-extrabold bg-gradient-to-r from-primary to-accent text-transparent bg-clip-text drop-shadow-lg mb-2 animate-fade-in"
                    style="font-family: 'Poppins', Verdana, Geneva, Tahoma, sans-serif;">
                    Drive Details
                </h1>
                <div
                    class="h-1 w-24 bg-gradient-to-r from-primary via-accent to-primary-light rounded-full mb-2 animate-slide-up">
                </div>

            </div>


            <!-- Main content with improved spacing and animations -->
            <main class="flex-1 overflow-y-auto  space-y-6 animate-fade-in">
                <!-- Company card with enhanced visual hierarchy -->
                <section class="bg-card-light dark:bg-card-dark   shadow-lg p-6 card-hover animate-slide-up">
                    <div class="flex items-center gap-4">
                        <div class="relative">
                            <img alt="Tech Innovators Inc. logo"
                                class="w-16 h-16   object-cover border-2 border-white shadow-md"
                                src="https://lh3.googleusercontent.com/aida-public/AB6AXuDNp7B3_YkqdT_wf1VaRI1Yp5xi5oUj4Y5TIizUE_dbJwDLRUvPfH0EGV1nk9XnElb1gRR6P3PbKtne0Zqrh3MTjDirS0tdA6kS0b_S6vACzFYnILVOh0aMH_4bTGJ44X1NTjwCMgi5-ZjczKqMBkEgHSV_ROormz7kr4gml2xdbg0LtgqS4L6Vv5iKtfttPrjwIs9IJaLLbcs4q4hed1D_orq5wLAvsN_18bAGrEZdTNRyLTofvYwoyDVhirzhwFeC-ckbLM6rYHk" />
                            <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-success rounded-full border-2 border-white">
                            </div>

                        </div>
                        <div class="flex-1">
                            <div class="flex items-start justify-between">
                                <h2 class="text-2xl font-bold text-foreground-light dark:text-foreground-dark">
                                    {{ $drive->company_name }}
                                </h2>

                                @php

                                    $isSaved = DB::table('saved_drives')
                                        ->where('user_id', Auth::id())
                                        ->where('placement_drive_id', $drive->id)
                                        ->where('is_favorite', true)
                                        ->exists();

                                @endphp

                                <form method="POST" action="{{ route('drive.toggle-save') }}" class="inline">
                                    @csrf
                                    <input type="hidden" name="drive_id" value="{{ $drive->id }}">

                                    <button type="submit"
                                        class="p-2  hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                                        <span class="material-symbols-outlined text-primary  ">
                                            {{ $isSaved ? 'bookmark' : 'favorite' }}
                                        </span>
                                    </button>
                                </form>
                            </div>


                            <p class="text-md text-subtle-light dark:text-subtle-dark">{{ $drive->job_title }}</p>
                            <div class="flex items-center mt-1">
                                <span
                                    class="material-symbols-outlined text-sm text-subtle-light dark:text-subtle-dark mr-1">
                                    location_on
                                </span>
                                <span class="text-sm text-subtle-light dark:text-subtle-dark">{{ $drive->location }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4 flex justify-between items-center">
                        <span
                            class="inline-flex items-center px-3.5 py-1.5 rounded-full text-sm font-medium bg-success/20 text-success dark:bg-success/30 dark:text-green-300 status-indicator">
                            <span class="material-symbols-outlined text-base mr-1.5">check_circle</span>
                            {{ $drive->status }}
                        </span>
   <div class="flex items-center">
    <button onclick="openShareModal()" 
            class="flex items-center text-subtle-light dark:text-subtle-dark hover:text-primary dark:hover:text-primary-light transition-colors">
        <span class="material-symbols-outlined mr-1 text-sm">
            share
        </span>
        <span class="text-sm font-medium">Share</span>
    </button>
</div>
                    </div>
                </section>




<!-- Share Modal -->
<div id="shareModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white dark:bg-card-dark rounded-xl shadow-2xl max-w-md w-full animate-slide-up">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-bold text-foreground-light dark:text-foreground-dark">
                    Share Drive Details
                </h3>
                <button onclick="closeShareModal()" 
                        class="text-subtle-light hover:text-foreground-light dark:text-subtle-dark dark:hover:text-foreground-dark">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>

            <!-- Share Link with Copy Button -->
           <!-- Share Link with Copy Button -->
<div class="mb-6">
    <label class="block text-sm font-medium text-foreground-light dark:text-foreground-dark mb-2">
        Shareable Link
    </label>
    <div class="flex flex-col sm:flex-row gap-2">
        <div class="relative flex-1">
            <input type="text" 
                   id="shareableLink" 
                   value="{{ route('drive.detail', $drive->id) }}"
                   readonly
                   class="w-full px-3 py-2 pr-20 sm:pr-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-800 text-foreground-light dark:text-foreground-dark text-sm truncate">
            <!-- Mobile copy button inside input -->
            <button onclick="copyToClipboard()"
                    id="copyBtnMobile"
                    class="absolute right-2 top-1/2 transform -translate-y-1/2 sm:hidden px-3 py-1 bg-primary text-white rounded text-xs font-medium">
                Copy
            </button>
        </div>
        <!-- Desktop copy button -->
        <button onclick="copyToClipboard()"
                id="copyBtnDesktop"
                class="hidden sm:block px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary/90 transition-colors text-sm font-medium whitespace-nowrap">
            Copy
        </button>
    </div>
    <p id="copyMessage" class="text-success text-xs mt-2 hidden">
        ✓ Link copied to clipboard!
    </p>
</div>
            <!-- Quick Share Buttons -->
            <div class="mb-6">
                <p class="text-sm font-medium text-foreground-light dark:text-foreground-dark mb-3">
                    Share via
                </p>
                <div class="grid grid-cols-4 gap-3">
                    <!-- WhatsApp -->
                    <a href="https://wa.me/?text=Check%20out%20this%20placement%20drive%20at%20{{ $drive->company_name }}:%20{{ route('drive.detail', $drive->id) }}"
                       target="_blank"
                       class="flex flex-col items-center justify-center p-3 bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300 rounded-lg hover:bg-green-200 dark:hover:bg-green-900/50 transition-colors">
                        <span class="material-symbols-outlined text-xl mb-1">chat</span>
                        <span class="text-xs font-medium">WhatsApp</span>
                    </a>

                    <!-- Email -->
                    <a href="mailto:?subject=Placement%20Drive:%20{{ urlencode($drive->company_name) }}&body=Check%20out%20this%20placement%20drive:%20{{ route('drive.detail', $drive->id) }}"
                       class="flex flex-col items-center justify-center p-3 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 rounded-lg hover:bg-blue-200 dark:hover:bg-blue-900/50 transition-colors">
                        <span class="material-symbols-outlined text-xl mb-1">mail</span>
                        <span class="text-xs font-medium">Email</span>
                    </a>

                    <!-- LinkedIn -->
                    <a href="https://www.linkedin.com/sharing/share-offsite/?url={{ route('drive.detail', $drive->id) }}"
                       target="_blank"
                       class="flex flex-col items-center justify-center p-3 bg-blue-50 dark:bg-blue-950/30 text-blue-600 dark:text-blue-400 rounded-lg hover:bg-blue-100 dark:hover:bg-blue-950/50 transition-colors">
                        <span class="material-symbols-outlined text-xl mb-1">person</span>
                        <span class="text-xs font-medium">LinkedIn</span>
                    </a>

                    <!-- Twitter -->
                    <a href="https://twitter.com/intent/tweet?text=Check%20out%20this%20placement%20drive%20at%20{{ urlencode($drive->company_name) }}&url={{ route('drive.detail', $drive->id) }}"
                       target="_blank"
                       class="flex flex-col items-center justify-center p-3 bg-sky-100 dark:bg-sky-900/30 text-sky-700 dark:text-sky-300 rounded-lg hover:bg-sky-200 dark:hover:bg-sky-900/50 transition-colors">
                        <span class="material-symbols-outlined text-xl mb-1">flutter_dash</span>
                        <span class="text-xs font-medium">Twitter</span>
                    </a>
                </div>
            </div>

            <!-- QR Code Section -->
            <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                <p class="text-sm font-medium text-foreground-light dark:text-foreground-dark mb-3">
                    Scan QR Code
                </p>
                <div class="flex flex-col items-center">
                    <div id="qrcode" class="mb-3 p-4 bg-white rounded-lg border border-gray-200 dark:border-gray-700" 
                         style="width: 150px; height: 150px; display: flex; align-items: center; justify-content: center;">
                        <div class="text-center">
                            <span class="material-symbols-outlined text-3xl text-subtle-light dark:text-subtle-dark">qr_code_scanner</span>
                            <p class="text-xs text-subtle-light dark:text-subtle-dark mt-2">Generate on click</p>
                        </div>
                    </div>
                    <p class="text-xs text-subtle-light dark:text-subtle-dark text-center">
                        Scan to open on mobile
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Share Modal Functions
    function openShareModal() {
        document.getElementById('shareModal').classList.remove('hidden');
        document.getElementById('shareModal').classList.add('flex');
        document.body.style.overflow = 'hidden';
        
        // Generate QR Code using reliable API
        generateQRCode();
    }

    function generateQRCode() {
        const qrCodeElement = document.getElementById('qrcode');
        if (!qrCodeElement) return;
        
        const currentUrl = encodeURIComponent(window.location.href);
        
        // Use a simple and reliable QR code API
        const qrCodeUrl = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${currentUrl}&format=png&bgcolor=f8fafc&color=0f172a`;
        
        qrCodeElement.innerHTML = `
            <div class="text-center">
                <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-primary mx-auto mb-2"></div>
                <p class="text-xs text-subtle-light dark:text-subtle-dark">Generating...</p>
            </div>
        `;
        
        // Create image and test if it loads
        const img = new Image();
        img.onload = function() {
            qrCodeElement.innerHTML = `
                <img src="${qrCodeUrl}" 
                     alt="QR Code for drive page" 
                     class="w-full h-auto rounded"
                     loading="lazy">
            `;
        };
        img.onerror = function() {
            // Fallback to text if QR fails
            qrCodeElement.innerHTML = `
                <div class="text-center p-3">
                    <span class="material-symbols-outlined text-2xl text-warning mb-2">link</span>
                    <p class="text-xs text-subtle-light dark:text-subtle-dark">Use Copy Link above</p>
                </div>
            `;
        };
        
        // Set timeout in case image hangs
        setTimeout(() => {
            if (!img.complete) {
                qrCodeElement.innerHTML = `
                    <div class="text-center p-3">
                        <span class="material-symbols-outlined text-2xl text-subtle-light dark:text-subtle-dark mb-2">qr_code_scanner</span>
                        <p class="text-xs text-subtle-light dark:text-subtle-dark">QR not available</p>
                    </div>
                `;
            }
        }, 3000);
        
        img.src = qrCodeUrl;
    }

    function closeShareModal() {
        document.getElementById('shareModal').classList.add('hidden');
        document.getElementById('shareModal').classList.remove('flex');
        document.body.style.overflow = 'auto';
    }

    // Copy to Clipboard
    function copyToClipboard() {
        const linkInput = document.getElementById('shareableLink');
        const copyBtn = document.getElementById('copyBtn');
        const copyMessage = document.getElementById('copyMessage');
        
        // Select text
        linkInput.select();
        linkInput.setSelectionRange(0, 99999);
        
        // Try modern clipboard API first
        if (navigator.clipboard && navigator.clipboard.writeText) {
            navigator.clipboard.writeText(linkInput.value)
                .then(() => {
                    showCopySuccess(copyBtn, copyMessage);
                })
                .catch(() => {
                    // Fallback to execCommand
                    document.execCommand('copy');
                    showCopySuccess(copyBtn, copyMessage);
                });
        } else {
            // Fallback for older browsers
            document.execCommand('copy');
            showCopySuccess(copyBtn, copyMessage);
        }
    }

    function showCopySuccess(copyBtn, copyMessage) {
        copyMessage.classList.remove('hidden');
        copyBtn.innerHTML = 'Copied!';
        copyBtn.classList.remove('bg-primary');
        copyBtn.classList.add('bg-success');
        
        setTimeout(() => {
            copyMessage.classList.add('hidden');
            copyBtn.innerHTML = 'Copy';
            copyBtn.classList.remove('bg-success');
            copyBtn.classList.add('bg-primary');
        }, 2000);
    }

    // Close modal when clicking outside
    document.getElementById('shareModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeShareModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !document.getElementById('shareModal').classList.contains('hidden')) {
            closeShareModal();
        }
    });
</script>






                <!-- Job Details with improved layout -->
                <section class="bg-card-light dark:bg-card-dark   shadow-lg card-hover animate-slide-up">
                    <div class="p-6">
                        <div class="flex items-center mb-6">
                            <div class="w-1.5 h-6 bg-primary rounded-full mr-3"></div>
                            <h3 class="text-lg font-semibold flex items-center">
                                <span class="material-symbols-outlined mr-3 text-primary">work_outline</span>Job Details
                            </h3>
                        </div>

                        <div class="space-y-6">
                            <div class="flex items-start gap-4">
                                <div class="bg-primary/10 p-2 rounded-lg">
                                    <span class="material-symbols-outlined text-primary text-xl">description</span>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-foreground-light dark:text-foreground-dark mb-1">Description
                                    </p>
                                    <p class="text-subtle-light dark:text-subtle-dark text-sm">{{ $drive->description }}
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start gap-4">
                                <div class="bg-primary/10 p-2 rounded-lg">
                                    <span class="material-symbols-outlined text-primary text-xl">code</span>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-foreground-light dark:text-foreground-dark mb-2">Required
                                        Skills</p>
                                    <div class="flex flex-wrap gap-2">
                                        @php
                                            $skills = is_array($drive->required_skills)
                                                ? $drive->required_skills
                                                : explode(',', $drive->required_skills ?? '');
                                        @endphp
                                        @foreach ($skills as $skill)
                                            @if (trim($skill) !== '')
                                                <span
                                                    class="skill-tag bg-primary/10 text-primary dark:bg-primary/20 dark:text-primary-light text-xs font-semibold px-3 py-1.5 rounded-full flex items-center">
                                                    <span
                                                        class="material-symbols-outlined text-xs mr-1">code</span>{{ trim($skill) }}
                                                </span>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div
                                    class="flex items-center gap-4 p-3 bg-background-light/50 dark:bg-background-dark/50 rounded-lg">
                                    <div class="bg-primary/10 p-2 rounded-lg">
                                        <span class="material-symbols-outlined text-primary text-xl">work</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-foreground-light dark:text-foreground-dark">Job Role</p>
                                        <p class="text-subtle-light dark:text-subtle-dark text-sm">{{ $drive->job_role }}
                                        </p>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center gap-4 p-3 bg-background-light/50 dark:bg-background-dark/50 rounded-lg">
                                    <div class="bg-primary/10 p-2 rounded-lg">
                                        <span class="material-symbols-outlined text-primary text-xl">payments</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-foreground-light dark:text-foreground-dark">Package</p>
                                        <p class="text-subtle-light dark:text-subtle-dark text-sm">
                                            ₹{{ $drive->package_offered }} LPA</p>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center gap-4 p-3 bg-background-light/50 dark:bg-background-dark/50 rounded-lg">
                                    <div class="bg-primary/10 p-2 rounded-lg">
                                        <span class="material-symbols-outlined text-primary text-xl">groups</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-foreground-light dark:text-foreground-dark">Vacancies
                                        </p>
                                        <p class="text-subtle-light dark:text-subtle-dark text-sm">{{ $drive->vacancies }}
                                        </p>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center gap-4 p-3 bg-background-light/50 dark:bg-background-dark/50 rounded-lg">
                                    <div class="bg-primary/10 p-2 rounded-lg">
                                        <span class="material-symbols-outlined text-primary text-xl">schedule</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-foreground-light dark:text-foreground-dark">Experience
                                        </p>
                                        <p class="text-subtle-light dark:text-subtle-dark text-sm">0-2 years</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Eligibility & Schedule with enhanced timeline -->
                <section class="bg-card-light dark:bg-card-dark   shadow-lg card-hover animate-slide-up">
                    <div class="p-6">
                        <div class="flex items-center mb-6">
                            <div class="w-1.5 h-6 bg-accent rounded-full mr-3"></div>
                            <h3 class="text-lg font-semibold flex items-center">
                                <span class="material-symbols-outlined mr-3 text-accent">event_available</span>Eligibility
                                &
                                Schedule
                            </h3>
                        </div>

                        <div class="space-y-6">
                            <div class="flex items-start gap-4">
                                <div class="bg-accent/10 p-2 rounded-lg">
                                    <span class="material-symbols-outlined text-accent text-xl">school</span>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-foreground-light dark:text-foreground-dark mb-1">Eligibility
                                    </p>
                                    <p class="text-subtle-light dark:text-subtle-dark text-sm">CGPA:
                                        {{ $drive->eligibility_cgpa }} ||
                                        Courses:
                                       @php
                            $courses = is_array($drive->courses) ? $drive->courses : [];
                            $uniqueCourses = array_unique($courses);
                        @endphp
                        {{ implode(', ', $uniqueCourses) }}
                                        || Depts:
                                        @php
                            $departments = is_array($drive->departments) ? $drive->departments : [];
                            $uniqueDepartments = array_unique($departments);
                        @endphp
                        {{ implode(', ', $uniqueDepartments) }}
                                        || Years:
                                        @php
                                            $years = $drive->eligibility_passing_year;

                                            if (is_string($years)) {
                                                $years = json_decode($years, true);
                                            }
                                        @endphp

                                        {{ implode(', ', (array) $years) }}
                                        ||
                                        <br>

                                        Reapppear: {{ $drive->is_reappear ? 'Allowed' : 'Not Allowed' }}

                                        @if ($drive->tenth_percentage != null && $drive->twelfth_percentage != null)
                                            || 10th: {{ $drive->tenth_percentage }}% - 12th:
                                            {{ $drive->twelfth_percentage }}% min ||  Graduation: {{ $drive->graduation_percentage }}% min
                                        @endif


                                       

                                    </p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div
                                    class="flex items-center gap-4 p-3 bg-background-light/50 dark:bg-background-dark/50 rounded-lg">
                                    <div class="bg-accent/10 p-2 rounded-lg">
                                        <span class="material-symbols-outlined text-accent text-xl">event</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-foreground-light dark:text-foreground-dark">Drive Date
                                        </p>
                                        <p class="text-subtle-light dark:text-subtle-dark text-sm">
                                            {{ $drive->drive_date }}
                                        </p>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center gap-4 p-3 bg-background-light/50 dark:bg-background-dark/50 rounded-lg">
                                    <div class="bg-warning/10 p-2 rounded-lg">
                                        <span class="material-symbols-outlined text-warning text-xl">assignment_late</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-foreground-light dark:text-foreground-dark">Application
                                            Deadline</p>
                                        <p class="text-subtle-light dark:text-subtle-dark text-sm">
                                            {{ $drive->application_deadline }}
                                        </p>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center gap-4 p-3 bg-background-light/50 dark:bg-background-dark/50 rounded-lg">
                                    <div class="bg-accent/10 p-2 rounded-lg">
                                        <span class="material-symbols-outlined text-accent text-xl">location_on</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-foreground-light dark:text-foreground-dark">Location</p>
                                        <p class="text-subtle-light dark:text-subtle-dark text-sm">{{ $drive->location }}
                                        </p>
                                    </div>
                                </div>

                                <div
                                    class="flex items-center gap-4 p-3 bg-background-light/50 dark:bg-background-dark/50 rounded-lg">
                                    <div class="bg-accent/10 p-2 rounded-lg">
                                        <span
                                            class="material-symbols-outlined text-accent text-xl">laptop_chromebook</span>
                                    </div>
                                    <div>
                                        <p class="font-medium text-foreground-light dark:text-foreground-dark">Drive Type
                                        </p>
                                        <p class="text-subtle-light dark:text-subtle-dark text-sm">
                                            {{ $drive->drive_type }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Progress bar for application timeline -->
                            @php
                                // Calculate progress based on current date between application_deadline and drive_date
                                $now = strtotime(now());
                                $start = strtotime($drive->application_deadline);
                                $end = strtotime($drive->drive_date);
                                $total = max($end - $start, 1);
                                $elapsed = max(min($now - $start, $total), 0);
                                $progress = round(($elapsed / $total) * 100);
                            @endphp
                            <div class="mt-4">
                                <div class="flex justify-between text-sm text-subtle-light dark:text-subtle-dark mb-2">
                                    <span>Registration</span>
                                    <span>Drive Date</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress-fill" style="width: {{ $progress }}%"></div>
                                </div>
                                <div class="flex justify-between text-xs text-subtle-light dark:text-subtle-dark mt-2">
                                    <span>{{ $drive->application_deadline }}</span>
                                    <span>{{ $drive->drive_date }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Contact Information with improved interaction -->
                <section class="bg-card-light dark:bg-card-dark   shadow-lg card-hover animate-slide-up">
                    <div class="p-6">
                        <div class="flex items-center mb-6">
                            <div class="w-1.5 h-6 bg-info rounded-full mr-3"></div>
                            <h3 class="text-lg font-semibold flex items-center">
                                <span class="material-symbols-outlined mr-3 text-info">contacts</span>Contact Information
                            </h3>
                        </div>

                        <div class="space-y-5">
                            <div
                                class="flex items-center gap-4 p-3 bg-background-light/50 dark:bg-background-dark/50 rounded-lg">
                                <div class="bg-info/10 p-2 rounded-lg">
                                    <span class="material-symbols-outlined text-info text-xl">person</span>
                                </div>
                                <div>
                                    <p class="font-medium text-foreground-light dark:text-foreground-dark">Contact Person
                                    </p>
                                    <p class="text-subtle-light dark:text-subtle-dark text-sm">
                                        {{ $drive->contact_person }}
                                    </p>
                                </div>
                            </div>

                            <div
                                class="flex items-center gap-4 p-3 bg-background-light/50 dark:bg-background-dark/50 rounded-lg">
                                <div class="bg-info/10 p-2 rounded-lg">
                                    <span class="material-symbols-outlined text-info text-xl">support_agent</span>
                                </div>
                                <div>
                                    <p class="font-medium text-foreground-light dark:text-foreground-dark">Drive
                                        Coordinator
                                    </p>
                                    <p class="text-subtle-light dark:text-subtle-dark text-sm">
                                        {{ $drive->drive_coordinator }}
                                    </p>
                                </div>
                            </div>

                            <a class="flex items-center gap-4 p-3 bg-background-light/50 dark:bg-background-dark/50 rounded-lg group hover:bg-primary/5 transition-colors no-underline"
                                href="{{ $drive->company_website }}" style="text-decoration: none;"  target="_blank" >
                                <div class="bg-info/10 p-2 rounded-lg group-hover:bg-primary/20 transition-colors">
                                    <span
                                        class="material-symbols-outlined text-info text-xl group-hover:text-primary transition-colors">language</span>
                                </div>
                                <div>
                                    <p
                                        class="font-medium text-foreground-light dark:text-foreground-dark group-hover:text-primary transition-colors">
                                        Company Website</p>
                                    <p
                                        class="text-subtle-light dark:text-subtle-dark text-sm group-hover:text-primary/80 transition-colors">
                                        {{ Str::limit($drive->company_website, 25) }}
                                    </p>
                                </div>
                            </a>
                        </div>
                    </div>


                    <!-- Main content with improved spacing and animations -->
                    <main class="flex-1 overflow-y-auto  space-y-6 animate-fade-in">

                        <!-- Notifications Section - ADD THIS CODE HERE -->
                        @php
                            $notifications = DB::table('drive_notifications')
                                ->where('drive_id', $drive->id)
                                ->where('user_id', Auth::id())
                                ->where('from_admin', 1)
                                ->orderBy('created_at', 'desc')
                                ->get();
                        @endphp

                        @if ($notifications->count() > 0)
                            <section class="bg-card-light dark:bg-card-dark shadow-lg card-hover animate-slide-up">
                                <div class="p-6">
                                    <div class="flex items-center mb-6">
                                        <div class="w-1.5 h-6 bg-warning rounded-full mr-3"></div>
                                        <h3 class="text-lg font-semibold flex items-center">
                                            <span
                                                class="material-symbols-outlined mr-3 text-warning">admin_panel_settings</span>
                                            Admin Notifications
                                            <span
                                                class="ml-2 bg-warning text-white text-xs font-semibold px-2 py-1 rounded-full">
                                                {{ $notifications->count() }}
                                            </span>
                                        </h3>
                                    </div>

                                    <div class="space-y-4">
                                        @foreach ($notifications as $notification)
                                            <div
                                                class="border-l-4 border-warning bg-background-light/30 dark:bg-background-dark/30 p-4 rounded-r-lg">
                                                <div class="flex items-start justify-between">
                                                    <div class="flex-1">
                                                        <!-- Message -->
                                                        <p class="text-foreground-light dark:text-foreground-dark mb-3">
                                                            {!! nl2br(e($notification->message)) !!}
                                                        </p>

                                                        <!-- Link Display - Only if link exists and is_link = 1 -->
                                                        @if ($notification->is_link && $notification->link)
                                                            <div class="mb-2">
                                                                <a href="{{ $notification->link }}" target="_blank"
                                                                    class="inline-flex items-center gap-2 px-4 py-2 bg-info/20 text-info dark:bg-info/30 dark:text-blue-300 rounded-lg text-sm font-medium hover:bg-info/30 transition-colors no-underline border border-info/30">
                                                                    <span
                                                                        class="material-symbols-outlined text-base">link</span>
                                                                    <span>Click here to view important link</span>
                                                                </a>
                                                            </div>
                                                        @endif

                                                        <!-- Attachment Display - Only if attachment exists and is_attachment = 1 -->
                                                        @if ($notification->is_attachment && $notification->attachment_path)
                                                            <div class="mb-2">
                                                                <a href="{{ asset('public/' . $notification->attachment_path) }}"
                                                                    target="_blank"
                                                                    class="inline-flex items-center gap-2 px-4 py-2 bg-success/20 text-success dark:bg-success/30 dark:text-green-300 rounded-lg text-sm font-medium hover:bg-success/30 transition-colors no-underline border border-success/30">
                                                                    <span
                                                                        class="material-symbols-outlined text-base">attachment</span>
                                                                    <span>Download Attachment</span>
                                                                </a>
                                                            </div>
                                                        @endif

                                                        <!-- Notification Metadata -->
                                                        <div
                                                            class="flex items-center gap-4 mt-3 text-xs text-subtle-light dark:text-subtle-dark">
                                                            <span class="flex items-center gap-1">
                                                                <span
                                                                    class="material-symbols-outlined text-xs">schedule</span>
                                                                {{ \Carbon\Carbon::parse($notification->created_at)->format('M d, Y h:i A') }}
                                                            </span>

                                                            @if ($notification->read_at)
                                                                <span class="flex items-center gap-1 text-success">
                                                                    <span
                                                                        class="material-symbols-outlined text-xs">check_circle</span>
                                                                    Read
                                                                </span>
                                                            @else
                                                                <span class="flex items-center gap-1 text-warning">
                                                                    <span
                                                                        class="material-symbols-outlined text-xs">mark_unread_chat_alt</span>
                                                                    Unread
                                                                </span>
                                                            @endif

                                                            <span class="flex items-center gap-1 text-primary">
                                                                <span
                                                                    class="material-symbols-outlined text-xs">admin_panel_settings</span>
                                                                From Admin
                                                            </span>
                                                        </div>
                                                    </div>

                                                    <!-- Mark as read button for unread notifications -->
                                                    @if (!$notification->read_at)
                                                        <form
                                                            action="{{ route('notification.mark-read', $notification->id) }}"
                                                            method="POST" class="ml-2">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit"
                                                                class="p-2 bg-warning/20 text-warning rounded-full hover:bg-warning/30 transition-colors"
                                                                title="Mark as read">
                                                                <span
                                                                    class="material-symbols-outlined text-sm">check</span>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </section>
                        @endif


                </section>

                <!-- Action buttons -->
                <div
                    class="sticky bottom-0 bg-background-light/80 dark:bg-background-dark/80 backdrop-blur-lg p-1 pb-10 rounded-t-xl shadow-lg">
                    <div class="flex gap-3">
                        <button
                            class="w-1/2 py-3 px-4 bg-primary text-white font-medium  hover:bg-primary/90 transition-colors flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-lg">description</span>
                            View PDF
                        </button>
                        @php
                            $applied = DB::table('placement_applications')
                                ->where('placement_drive_id', $drive->id)
                                ->where('student_id', Auth::id())
                                ->exists();
                        @endphp

                        <form action="{{ route('placement.apply', $drive->id) }}" method="POST" class="w-1/2">
                            @csrf
                            <button type="submit"
                                class="w-full py-3 px-4 text-white font-medium transition-colors flex items-center justify-center gap-2
        {{ $applied ? 'bg-gray-500 cursor-not-allowed' : 'bg-success hover:bg-success/90' }}"
                                {{ $applied ? 'disabled' : '' }}>
                                <span class="material-symbols-outlined text-lg">
                                    {{ $applied ? 'check_circle' : 'send' }}
                                </span>
                                {{ $applied ? 'Applied' : 'Apply Now' }}
                            </button>
                        </form>





                    </div>
                </div>
            </main>
        </div>






    </body>

    </html>
@endsection
