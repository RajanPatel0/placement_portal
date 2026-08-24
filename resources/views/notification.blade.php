@extends('layouts.base')

@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="utf-8" />
        <meta content="width=device-width, initial-scale=1.0" name="viewport" />
        <title>Notifications</title>
        <link href="https://fonts.googleapis.com" rel="preconnect" />
        <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect" />
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700;900&amp;display=swap" rel="stylesheet" />
        <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
        <script id="tailwind-config">
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
                            "display": ["Inter"]
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
        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet" />
    </head>

    <div class="flex flex-col min-h-screen">

        <main class="flex-grow  mx-auto  lg:px-12 py-5">
            <div class="max-w-4xl mx-auto">
                <div class="flex justify-center items-center mb-6">
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white text-center">Notifications</h2>
                </div>

                <div class="bg-white dark:bg-background-dark/50 rounded-lg shadow-md">
                    <ul class="divide-y divide-gray-200 dark:divide-gray-700 ">
                        {{-- ✅ Loop through drive_notifications --}}
                        @forelse($notifications as $notification)
                            <li class="notification-item p-4 hover:bg-gray-50 dark:hover:bg-background-dark flex items-center justify-between space-x-4 cursor-pointer {{ $notification->read_at ? 'opacity-60' : '' }}"
                                data-id="{{ $notification->id }}"
                                data-drive-url="{{ $notification->drive_id ? route('drive.detail', $notification->drive_id) : '#' }}"
                                data-readat="{{ $notification->read_at }}" data-message="{{ $notification->message }}"
                                data-created="{{ \Carbon\Carbon::parse($notification->created_at)->format('d M Y, h:i A') }}">
                                <div class="flex items-center space-x-4">
                                    <div class="relative">
                                        <div
                                            class="{{ $notification->read_at ? 'bg-gray-100 dark:bg-gray-700 text-gray-500' : 'bg-primary/10 dark:bg-primary/20 text-primary' }} rounded-full size-12 flex items-center justify-center">
                                            <span class="material-symbols-outlined">
                                                {{ $notification->read_at ? 'notifications' : 'notifications_active' }}
                                            </span>
                                        </div>
                                        @if (!$notification->read_at)
                                            <span
                                                class="absolute -top-1 -right-1 block h-3 w-3 rounded-full bg-primary ring-2 ring-white dark:ring-background-dark"></span>
                                        @endif
                                    </div>
                                    <div class="flex-1">
                                        <p class="font-semibold text-gray-800 dark:text-gray-100 mb-1">
                                            {{ $notification->message ?? 'New Notification' }}
                                        </p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ \Carbon\Carbon::parse($notification->created_at)->format('d M Y, h:i A') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    @if ($notification->read_at)
                                        <p class="text-xs text-gray-500 dark:text-gray-400 read-time">
                                            {{ \Carbon\Carbon::parse($notification->read_at)->format('d-m-Y H:i') }}
                                        </p>
                                    @else
                                        <p class="text-xs text-gray-500 dark:text-gray-400 read-time">
                                            Created:
                                            {{ \Carbon\Carbon::parse($notification->created_at)->format('d-m-Y') }}
                                        </p>
                                    @endif
                                    <button
                                        class="mt-2 text-xs font-semibold text-primary/80 hover:text-primary toggle-read">
                                        {{ $notification->read_at ? 'Seen' : 'Unseen' }}
                                    </button>
                                </div>
                            </li>
                        @empty
                            <li class="p-4 text-center text-gray-500 dark:text-gray-400">
                                No notifications found.
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </main>
    </div>

    {{-- ✅ Modal --}}
    <div id="notificationModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
        <div class="bg-white dark:bg-background-dark rounded-lg p-6 w-full max-w-md shadow-lg">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4">Notification Details</h3>
            <p class="text-sm text-gray-700 dark:text-gray-300 mb-2"><strong>Message:</strong> <span
                    id="modalMessage"></span></p>
            <p class="text-sm text-gray-700 dark:text-gray-300 mb-2"><strong>Created:</strong> <span
                    id="modalCreated"></span></p>
            <p class="text-sm text-gray-700 dark:text-gray-300 mb-4"><strong>Seen Time:</strong> <span
                    id="modalSeen"></span></p>
            <div class="grid grid-cols-2 gap-4">
                <button id="closeModal"
                    class="px-4 py-2 text-white hover:bg-gray-600 transition bg-gray-500 rounded">Close</button>
                <a id="visitDriveLink" href="#" class="no-underline">
                    <button class="w-full px-4 py-2 text-white hover:bg-primary/90 transition bg-primary rounded">Visit
                        Drive</button>
                </a>
            </div>
        </div>
    </div>

    {{-- ✅ JS --}}
    <script>
        const modal = document.getElementById('notificationModal');
        const closeModal = document.getElementById('closeModal');
        const visitDriveLink = document.getElementById('visitDriveLink');

        function formatDate(dateStr) {
            if (!dateStr) return 'Not seen yet';
            const d = new Date(dateStr);
            if (isNaN(d)) return 'Invalid date';
            const day = String(d.getDate()).padStart(2, '0');
            const month = String(d.getMonth() + 1).padStart(2, '0');
            const year = d.getFullYear();
            const hours = String(d.getHours()).padStart(2, '0');
            const minutes = String(d.getMinutes()).padStart(2, '0');
            return `${day}-${month}-${year} ${hours}:${minutes}`;
        }

        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', async (e) => {
                // Prevent marking as read if clicking the toggle button
                if (e.target.classList.contains('toggle-read')) {
                    return;
                }

                const id = item.dataset.id;
                const driveUrl = item.dataset.driveUrl;
                const message = item.dataset.message;
                const created = item.dataset.created;

                // Fill modal first
                document.getElementById('modalMessage').textContent = message;
                document.getElementById('modalCreated').textContent = created;
                visitDriveLink.href = driveUrl;

                // Mark as seen only if not already
                if (!item.dataset.readat) {
                    try {
                        console.log('Marking notification as read:', id);
                        
                        const response = await fetch(`/notifications/${id}/mark-seen`, {
                            method: 'POST', // ✅ Changed to POST
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            }
                        });

                        console.log('Response status:', response.status);
                        
                        if (!response.ok) {
                            // Try to get error message from response
                            let errorMessage = `HTTP error! status: ${response.status}`;
                            try {
                                const errorData = await response.json();
                                errorMessage = errorData.message || errorMessage;
                            } catch (e) {
                                // If no JSON response, use status text
                                errorMessage = response.statusText || errorMessage;
                            }
                            throw new Error(errorMessage);
                        }

                        const data = await response.json();
                        console.log('Response data:', data);

                        if (data.success) {
                            const seenTime = formatDate(data.read_at);
                            document.getElementById('modalSeen').textContent = seenTime;

                            // ✅ Update current item dynamically
                            item.classList.add('opacity-60');
                            item.querySelector('.toggle-read').textContent = 'Seen';
                            item.querySelector('.material-symbols-outlined').textContent = 'notifications';

                            // Remove unread dot
                            const dot = item.querySelector('span.absolute');
                            if (dot) dot.remove();

                            // Update date display
                            const timeP = item.querySelector('.read-time');
                            if (timeP) {
                                timeP.textContent = 'Seen: ' + seenTime;
                            }

                            // Update dataset
                            item.dataset.readat = data.read_at;
                        } else {
                            throw new Error(data.message || 'Server returned success: false');
                        }
                    } catch (error) {
                        console.error('Error marking notification as read:', error);
                        document.getElementById('modalSeen').textContent = 'Not seen yet (Update failed)';
                        
                        // Show a temporary alert with the error
                        setTimeout(() => {
                            alert('Failed to mark as read. Check Laravel logs for details. Error: ' + error.message);
                        }, 100);
                    }
                } else {
                    const seenTime = formatDate(item.dataset.readat);
                    document.getElementById('modalSeen').textContent = seenTime;
                }

                // Show modal after processing
                modal.classList.remove('hidden');
            });
        });

        // Close modal handlers
        closeModal.addEventListener('click', () => {
            modal.classList.add('hidden');
        });

        // Close modal when clicking outside
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                modal.classList.add('hidden');
            }
        });

        // Close with Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                modal.classList.add('hidden');
            }
        });
    </script>
@endsection