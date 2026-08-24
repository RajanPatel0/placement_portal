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

    <!-- Main content -->
    <section class="content ">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            .search-box {
                position: relative;
            }

            .search-box i {
                position: absolute;
                left: 12px;
                top: 12px;
                color: #6c757d;
            }

            .search-box input {
                padding-left: 35px;
            }

            .action-buttons .btn-action {
                margin-right: 5px;
            }

            .entity-badge {
                padding: 5px 10px;
                border-radius: 15px;
                font-size: 0.85rem;
                font-weight: 500;
            }

            .college-badge {
                background-color: #e8f5e9;
                color: #2e7d32;
            }

            .course-badge {
                background-color: #e3f2fd;
                color: #1565c0;
            }

            .table-container {
                overflow-x: auto;
            }

            .nav-tabs .nav-link.active {
                font-weight: 600;
            }
        </style>


        <div class="container pt-4 pb-4">
            <h2 class="mb-4">{{ ucfirst($selectedCollege->name ?? 'N/A') }} -> Course</h2>

            <!-- Courses Tab -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="fas fa-graduation-cap"></i> Courses</span>
                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addCourseModal">
                        <i class="fas fa-plus"></i> Add Course
                    </button>
                </div>
                <div class="card-body">
                    <form method="GET" action="" class="mb-3">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="search-box">
                                    <i class="fas fa-search"></i>
                                    <input type="text" name="search_course" class="form-control"
                                        placeholder="Search courses..." value="{{ request('search_course') }}">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary">Search</button>
                            </div>
                        </div>
                    </form>
                    <div class="table-container">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>College</th>
                                    <th>Description</th>
                                    <th>View Departments</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($courseData as $course)
                                    <tr>
                                        <td>{{ $course->id }}</td>
                                        <td>{{ $course->name }}</td>
                                        <td>
                                            <span class="entity-badge college-badge">
                                                {{ $course->college_name ?? 'N/A' }}
                                            </span>
                                        </td>
                                        <td>{{ $course->description }}</td>
                                        <td>
                                            @php
                                                $departmentsForCourse = $departmentData->where(
                                                    'courses_id',
                                                    $course->id,
                                                );
                                                $departmentCount = $departmentsForCourse->count();
                                            @endphp

                                            @if ($departmentCount > 0)
                                                <a href="{{ route('admin.college.course.departments', $course->id) }}">
                                                    <span class="entity-badge course-badge">
                                                        {{ $departmentCount }} Departments
                                                    </span>
                                                </a>
                                            @else
                                                <a href="{{ route('admin.college.course.departments', $course->id) }}">
                                                    <span class="entity-badge course-badge text-muted">
                                                        NA
                                                    </span>
                                                </a>
                                            @endif
                                        </td>
                                        <td class="action-buttons">
                                            <button class="btn btn-sm btn-primary btn-action edit-course-btn"
                                                data-bs-toggle="modal" data-bs-target="#editCourseModal"
                                                data-course-id="{{ $course->id }}"
                                                data-course-name="{{ $course->name }}"
                                                data-college-id="{{ $course->college_id }}"
                                                data-description="{{ $course->description }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add Course Modal -->
        <div class="modal fade" id="addCourseModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-plus"></i> Add New Course</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('admin.store.course') }}">
                        @csrf
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Course Name *</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">College *</label>
                                <select class="form-select" name="college_id" required>
                                    <option value="" selected disabled>Select College</option>
                                    @foreach ($collegeData as $college)
                                        <option value="{{ $college->id }}">{{ $college->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="add_course" class="btn btn-primary">Add Course</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Course Modal -->
        <div class="modal fade" id="editCourseModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Course</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" id="editCourseForm" action="">
                        @csrf
                        
                        <div class="modal-body">
                            <input type="hidden" name="course_id" id="edit_course_id">
                            <div class="mb-3">
                                <label class="form-label">Course Name *</label>
                                <input type="text" name="name" id="edit_course_name" class="form-control"
                                    required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">College *</label>
                                <select class="form-select" name="college_id" id="edit_college_id" required>
                                    <option value="" disabled>Select College</option>
                                    @foreach ($collegeData as $college)
                                        <option value="{{ $college->id }}">{{ $college->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" id="edit_description" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="submit" name="update_course" class="btn btn-primary">Save Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Edit Course Modal Handler
                const editButtons = document.querySelectorAll('.edit-course-btn');

                editButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        // Get data from the button's data attributes
                        const courseId = this.getAttribute('data-course-id');
                        const courseName = this.getAttribute('data-course-name');
                        const collegeId = this.getAttribute('data-college-id');
                        const description = this.getAttribute('data-description');

                        // Set the form action URL with the course ID
                        const form = document.getElementById('editCourseForm');
                        form.action = "{{ route('admin.update.course', '') }}/" + courseId;

                        // Populate the form fields
                        document.getElementById('edit_course_id').value = courseId;
                        document.getElementById('edit_course_name').value = courseName;
                        document.getElementById('edit_college_id').value = collegeId;
                        document.getElementById('edit_description').value = description;
                    });
                });
            });
        </script>

    </section>
    <!-- /.content -->

@endsection

@section('scripts')
    <!-- Additional JS specific to the dashboard -->
    <script src="{{ asset('js/dashboard.js') }}"></script>
@endsection
