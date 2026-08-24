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
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Placement Drive Details - {{ $driveDetails->company_name ?? 'Drive Details' }}</title>
        <script src="https://cdn.tailwindcss.com"></script>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    </head>
    <style>
        /* Custom styles for better visualization */
        #header {
            background: linear-gradient(90deg, #e5c846, #6ced5e);
        }
    </style>

    <body class="bg-gray-50">
        <div class="container mx-auto px-4 py-8">
            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Placement Drive Details</h1>
                <div class="flex space-x-3">

                    <button onclick="openEditModal()"
                        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-edit mr-2"></i> Edit
                    </button>
                    <a href="{{ url()->previous() }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Back
                    </a>
                </div>
            </div>

            @if ($driveDetails)
                <!-- Main Card -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <!-- Company Header -->
                    <div class="p-6 text-white" id="header">
                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center">
                            <div>
                                <h2 class="text-2xl font-bold mb-2">{{ $driveDetails->company_name }}</h2>
                                <p class="text-blue-100 text-lg">{{ $driveDetails->job_title }}</p>
                            </div>
                            <div class="mt-4 md:mt-0">
                                @php
                                    function getStatusBadge($status)
                                    {
                                        $statusColors = [
                                            'upcoming' => 'bg-yellow-500',
                                            'ongoing' => 'bg-green-500',
                                            'completed' => 'bg-gray-500',
                                            'cancelled' => 'bg-red-500',
                                        ];
                                        $statusIcons = [
                                            'upcoming' => 'fa-clock',
                                            'ongoing' => 'fa-play-circle',
                                            'completed' => 'fa-check-circle',
                                            'cancelled' => 'fa-times-circle',
                                        ];

                                        $color = $statusColors[$status] ?? 'bg-gray-500';
                                        $icon = $statusIcons[$status] ?? 'fa-circle';
                                        $text = ucfirst($status);

                                        return "<span class='inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {$color} mr-2'>
                                                <i class='fas {$icon} mr-1'></i> {$text}
                                            </span>";
                                    }

                                    function getDriveTypeBadge($type)
                                    {
                                        $typeColors = [
                                            'on_campus' => 'bg-purple-500',
                                            'off_campus' => 'bg-orange-500',
                                            'virtual' => 'bg-teal-500',
                                        ];
                                        $typeIcons = [
                                            'on_campus' => 'fa-university',
                                            'off_campus' => 'fa-building',
                                            'virtual' => 'fa-desktop',
                                        ];

                                        $color = $typeColors[$type] ?? 'bg-gray-500';
                                        $icon = $typeIcons[$type] ?? 'fa-map-marker-alt';
                                        $text = str_replace('_', ' ', ucfirst($type));

                                        return "<span class='inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold {$color}'>
                                                <i class='fas {$icon} mr-1'></i> {$text}
                                            </span>";
                                    }

                                    function getActiveBadge($isActive)
                                    {
                                        if ($isActive) {
                                            return "<span class='inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-500'>
                                                    <i class='fas fa-check mr-1'></i> Active
                                                </span>";
                                        } else {
                                            return "<span class='inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-red-500'>
                                                    <i class='fas fa-times mr-1'></i> Inactive
                                                </span>";
                                        }
                                    }
                                @endphp
                                {!! getStatusBadge($driveDetails->status) !!}
                                {!! getDriveTypeBadge($driveDetails->drive_type) !!}
                                {!! getActiveBadge($driveDetails->is_active) !!}
                            </div>
                        </div>
                    </div>

                    <div class="p-6">
                        <!-- Key Information Grid -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                            <!-- Drive Date & Deadline -->
                            <div class="bg-blue-50 p-4 rounded-lg">
                                <div class="flex items-center mb-3">
                                    <i class="fas fa-calendar-day text-blue-600 mr-3"></i>
                                    <h3 class="font-semibold text-gray-700">Drive Schedule</h3>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Drive Date:</span>
                                        <span
                                            class="font-medium">{{ date('M d, Y', strtotime($driveDetails->drive_date)) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Application Deadline:</span>
                                        <span
                                            class="font-medium {{ strtotime($driveDetails->application_deadline) < time() ? 'text-red-600' : 'text-green-600' }}">
                                            {{ date('M d, Y', strtotime($driveDetails->application_deadline)) }}
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Package & Vacancies -->
                            <div class="bg-green-50 p-4 rounded-lg">
                                <div class="flex items-center mb-3">
                                    <i class="fas fa-money-bill-wave text-green-600 mr-3"></i>
                                    <h3 class="font-semibold text-gray-700">Compensation</h3>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Package:</span>
                                        <span
                                            class="font-medium text-green-700">₹{{ number_format($driveDetails->package_offered, 2) }}
                                            LPA</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Vacancies:</span>
                                        <span class="font-medium">{{ $driveDetails->vacancies ?? 'Not specified' }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- Eligibility -->
                            <div class="bg-purple-50 p-4 rounded-lg">
                                <div class="flex items-center mb-3">
                                    <i class="fas fa-graduation-cap text-purple-600 mr-3"></i>
                                    <h3 class="font-semibold text-gray-700">Eligibility</h3>
                                </div>
                                <div class="space-y-2">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Minimum CGPA:</span>
                                        <span
                                            class="font-medium">{{ $driveDetails->eligibility_cgpa ?? 'Not specified' }}</span>
                                    </div>
                                    @if ($driveDetails->eligibility_passing_year)
                                        <div class="flex justify-between">
                                            <span class="text-gray-600">Passing Year:</span>
                                            <span class="font-medium">
                                                @php
                                                    $years = json_decode($driveDetails->eligibility_passing_year);
                                                    echo implode(', ', $years);
                                                @endphp
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Location & Contact Information -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                            <!-- Location -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center mb-3">
                                    <i class="fas fa-map-marker-alt text-red-500 mr-3"></i>
                                    <h3 class="font-semibold text-gray-700">Location</h3>
                                </div>
                                <p class="text-gray-700">{{ $driveDetails->location ?? 'Location not specified' }}</p>
                                @if ($driveDetails->company_website)
                                    <div class="mt-2">
                                        <a href="{{ $driveDetails->company_website }}" target="_blank"
                                            class="text-blue-600 hover:text-blue-800 flex items-center">
                                            <i class="fas fa-external-link-alt mr-2"></i> Visit Company Website
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <!-- Contact Information -->
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <div class="flex items-center mb-3">
                                    <i class="fas fa-address-card text-indigo-500 mr-3"></i>
                                    <h3 class="font-semibold text-gray-700">Contact Information</h3>
                                </div>
                                <div class="space-y-2">
                                    @if ($driveDetails->contact_person)
                                        <div class="flex items-center">
                                            <i class="fas fa-user text-gray-400 mr-2 w-5"></i>
                                            <span class="text-gray-700">{{ $driveDetails->contact_person }}</span>
                                        </div>
                                    @endif
                                    @if ($driveDetails->contact_email)
                                        <div class="flex items-center">
                                            <i class="fas fa-envelope text-gray-400 mr-2 w-5"></i>
                                            <a href="mailto:{{ $driveDetails->contact_email }}"
                                                class="text-blue-600 hover:text-blue-800">
                                                {{ $driveDetails->contact_email }}
                                            </a>
                                        </div>
                                    @endif
                                    @if ($driveDetails->contact_phone)
                                        <div class="flex items-center">
                                            <i class="fas fa-phone text-gray-400 mr-2 w-5"></i>
                                            <a href="tel:{{ $driveDetails->contact_phone }}"
                                                class="text-blue-600 hover:text-blue-800">
                                                {{ $driveDetails->contact_phone }}
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Job Details -->
                        <div class="space-y-6">
                            <!-- Job Role -->
                            @if ($driveDetails->job_role)
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-800 mb-3 flex items-center">
                                        <i class="fas fa-briefcase text-blue-500 mr-2"></i> Job Role & Responsibilities
                                    </h3>
                                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                                        <p class="text-gray-700 whitespace-pre-line">{{ $driveDetails->job_role }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Required Skills -->
                            @if ($driveDetails->required_skills)
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-800 mb-3 flex items-center">
                                        <i class="fas fa-tools text-orange-500 mr-2"></i> Required Skills
                                    </h3>
                                    <div class="flex flex-wrap gap-2">
                                        @php
                                            $skills = json_decode($driveDetails->required_skills);
                                        @endphp
                                        @foreach ($skills as $skill)
                                            <span
                                                class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm font-medium">
                                                {{ $skill }}
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Description -->
                            @if ($driveDetails->description)
                                <div>
                                    <h3 class="text-xl font-semibold text-gray-800 mb-3 flex items-center">
                                        <i class="fas fa-file-alt text-green-500 mr-2"></i> Additional Information
                                    </h3>
                                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                                        <p class="text-gray-700 whitespace-pre-line">{{ $driveDetails->description }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Course & Department Information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @if ($driveDetails->course_id)
                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-800 mb-3 flex items-center">
                                            <i class="fas fa-book text-purple-500 mr-2"></i> Eligible Courses
                                        </h3>
                                        <div class="flex flex-wrap gap-2">
                                            @php
                                                $courseIds = json_decode($driveDetails->course_id);
                                            @endphp
                                            @foreach ($courseIds as $courseId)
                                                <span
                                                    class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-medium">
                                                    {{ $driveDetails->course_names[$courseId] ?? 'Course ' . $courseId }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                @if ($driveDetails->department_id)
                                    <div>
                                        <h3 class="text-xl font-semibold text-gray-800 mb-3 flex items-center">
                                            <i class="fas fa-building text-teal-500 mr-2"></i> Eligible Departments
                                        </h3>
                                        <div class="flex flex-wrap gap-2">
                                            @php
                                                $departmentIds = json_decode($driveDetails->department_id);
                                            @endphp
                                            @foreach ($departmentIds as $departmentId)
                                                <span
                                                    class="bg-teal-100 text-teal-800 px-3 py-1 rounded-full text-sm font-medium">
                                                    {{ $driveDetails->department_names[$departmentId] ?? 'Department ' . $departmentId }}
                                                </span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <!-- Additional Information -->
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Drive Coordinator -->
                                @if ($driveDetails->drive_coordinator)
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                        <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center">
                                            <i class="fas fa-user-tie text-yellow-600 mr-2"></i> Drive Coordinator
                                        </h3>
                                        <p class="text-gray-700">{{ $driveDetails->drive_coordinator }}</p>
                                    </div>
                                @endif

                                <!-- Reappear Information -->
                                @if (!is_null($driveDetails->is_reappear))
                                    <div class="bg-indigo-50 border border-indigo-200 rounded-lg p-4">
                                        <h3 class="text-lg font-semibold text-gray-800 mb-2 flex items-center">
                                            <i class="fas fa-redo-alt text-indigo-600 mr-2"></i> Reappear Policy
                                        </h3>
                                        <p class="text-gray-700">
                                            {{ $driveDetails->is_reappear ? 'Reappear allowed' : 'Reappear not allowed' }}
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Footer -->
                    <div class="bg-gray-100 px-6 py-4 border-t border-gray-200">
                        <div class="flex flex-col md:flex-row justify-between items-center text-sm text-gray-600">
                            <div class="flex items-center mb-2 md:mb-0">
                                <i class="fas fa-info-circle mr-2"></i>
                                <span>Drive ID: {{ $driveDetails->id }}</span>
                            </div>
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center">
                                    <i class="fas fa-user-plus mr-2"></i>
                                    <span>Created by: {{ $driveDetails->creator_name ?? 'Unknown' }}</span>

                                </div>
                                <div class="flex items-center">
                                    <i class="fas fa-clock mr-2"></i>
                                    <span>Created: {{ date('M d, Y h:i A', strtotime($driveDetails->created_at)) }}</span>
                                </div>
                                @if ($driveDetails->updated_at && $driveDetails->updated_at != $driveDetails->created_at)
                                    <div class="flex items-center">
                                        <i class="fas fa-sync-alt mr-2"></i>
                                        <span>Updated:
                                            {{ date('M d, Y h:i A', strtotime($driveDetails->updated_at)) }}</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <!-- No Data Found -->
                <div class="bg-white rounded-xl shadow-lg p-8 text-center">
                    <i class="fas fa-exclamation-triangle text-yellow-500 text-5xl mb-4"></i>
                    <h2 class="text-2xl font-bold text-gray-800 mb-2">Placement Drive Not Found</h2>
                    <p class="text-gray-600 mb-4">The requested placement drive details could not be found.</p>
                    <a href="{{ url()->previous() }}"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg inline-flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i> Go Back
                    </a>
                </div>
            @endif
        </div>


        <!-- Edit Modal -->
        <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 z-50 hidden">
            <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
                <div class="sticky top-0 bg-white border-b border-gray-200 px-6 py-4 flex justify-between items-center">
                    <h3 class="text-2xl font-bold text-gray-800">Edit Placement Drive</h3>
                    <button onclick="closeEditModal()" class="text-gray-500 hover:text-gray-700 text-2xl">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <form id="editForm" action="{{ route('placement.drive.update', $driveDetails->id) }}" method="POST"
                    class="p-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Company Information -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Company Name *</label>
                                <input type="text" name="company_name" value="{{ $driveDetails->company_name }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Job Title</label>
                                <input type="text" name="job_title" value="{{ $driveDetails->job_title }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Drive Date *</label>
                                <input type="date" name="drive_date" value="{{ $driveDetails->drive_date }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Application Deadline</label>
                                <input type="date" name="application_deadline"
                                    value="{{ $driveDetails->application_deadline }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>

                        <!-- Drive Information -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Status *</label>
                                <select name="status"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required>
                                    <option value="upcoming" {{ $driveDetails->status == 'upcoming' ? 'selected' : '' }}>
                                        Upcoming</option>
                                    <option value="ongoing" {{ $driveDetails->status == 'ongoing' ? 'selected' : '' }}>
                                        Ongoing</option>
                                    <option value="completed"
                                        {{ $driveDetails->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled"
                                        {{ $driveDetails->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Drive Type *</label>
                                <select name="drive_type"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                                    required>
                                    <option value="on_campus"
                                        {{ $driveDetails->drive_type == 'on_campus' ? 'selected' : '' }}>On Campus</option>
                                    <option value="off_campus"
                                        {{ $driveDetails->drive_type == 'off_campus' ? 'selected' : '' }}>Off Campus
                                    </option>
                                    <option value="virtual"
                                        {{ $driveDetails->drive_type == 'virtual' ? 'selected' : '' }}>Virtual</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Package Offered (LPA)</label>
                                <input type="number" step="0.01" name="package_offered"
                                    value="{{ $driveDetails->package_offered }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Vacancies</label>
                                <input type="number" name="vacancies" value="{{ $driveDetails->vacancies }}"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Additional Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Location</label>
                            <input type="text" name="location" value="{{ $driveDetails->location }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        </div>


                    </div>



                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                        <button type="button" onclick="closeEditModal()"
                            class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center">
                            <i class="fas fa-save mr-2"></i> Update Drive
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <script>
            // Modal functions
            function openEditModal() {
                document.getElementById('editModal').classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            function closeEditModal() {
                document.getElementById('editModal').classList.add('hidden');
                document.body.style.overflow = 'auto';
            }

            // Close modal when clicking outside
            document.getElementById('editModal').addEventListener('click', function(e) {
                if (e.target === this) {
                    closeEditModal();
                }
            });

            // Close modal with Escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeEditModal();
                }
            });

            // Form submission handling
            document.getElementById('editForm').addEventListener('submit', function(e) {
                const form = this;
                const submitBtn = form.querySelector('button[type="submit"]');
                const originalText = submitBtn.innerHTML;

                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Updating...';
                submitBtn.disabled = true;

                // You can add additional validation here if needed
            });

            // Add any interactive functionality here if needed
            document.addEventListener('DOMContentLoaded', function() {
                // Example: Add confirmation for external links
                const externalLinks = document.querySelectorAll('a[href^="http"]');
                externalLinks.forEach(link => {
                    if (!link.href.includes(window.location.hostname)) {
                        link.addEventListener('click', function(e) {
                            if (!confirm('You are about to leave this site. Continue?')) {
                                e.preventDefault();
                            }
                        });
                    }
                });
            });

            // Show success/error messages
            @if (session('success'))
                setTimeout(() => {
                    alert('{{ session('success') }}');
                }, 100);
            @endif

            @if (session('error'))
                setTimeout(() => {
                    alert('{{ session('error') }}');
                }, 100);
            @endif
        </script>

        <script>
            // Add any interactive functionality here if needed
            document.addEventListener('DOMContentLoaded', function() {
                // Example: Add confirmation for external links
                const externalLinks = document.querySelectorAll('a[href^="http"]');
                externalLinks.forEach(link => {
                    if (!link.href.includes(window.location.hostname)) {
                        link.addEventListener('click', function(e) {
                            if (!confirm('You are about to leave this site. Continue?')) {
                                e.preventDefault();
                            }
                        });
                    }
                });

                // Add confirmation for edit button
                const editButton = document.querySelector('a[href*="edit"]');
                if (editButton) {
                    editButton.addEventListener('click', function(e) {
                        if (!confirm('Are you sure you want to edit this placement drive?')) {
                            e.preventDefault();
                        }
                    });
                }
            });
        </script>
    </body>

    </html>

@endsection

@section('scripts')
    <!-- Additional JS specific to the dashboard -->
    <script src="{{ asset('js/dashboard.js') }}"></script>
@endsection
