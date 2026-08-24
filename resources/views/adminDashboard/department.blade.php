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
    .department-badge {
        background-color: #fff3e0;
        color: #e65100;
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

    <h2 class="mb-4">{{ ucfirst($selectedCourse->name ?? 'N/A') }}-> Departments</h2>
    
    <!-- Departments Tab -->
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-building"></i> Departments</span>
            <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addDepartmentModal">
                <i class="fas fa-plus"></i> Add Department
            </button>
        </div>
        <div class="card-body">
            <form method="GET" action="" class="mb-3">
                <div class="row">
                    <div class="col-md-6">
                        <div class="search-box">
                            <i class="fas fa-search"></i>
                            <input type="text" name="search_department" class="form-control" placeholder="Search departments..." value="">
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
                            <th>Description</th>
                             <th>Course</th>
                            <th>College</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($departmentData as $department)
                        <tr>
                            <td>{{ $department->id }}</td>
                            <td>{{ $department->name }}</td>
                            <td>{{ $department->description }}</td>
                            <td>
                                <span class="entity-badge course-badge">
                                    {{ $department->course_name ?? 'N/A' }}
                                </span>
                            </td>
                            <td>
                                <span class="entity-badge course-badge">
                                    {{ $department->college_name ?? 'N/A' }}
                                </span>
                            </td>
                            <td class="action-buttons">
                                <button class="btn btn-sm btn-primary btn-action" data-bs-toggle="modal" 
                                    data-bs-target="#editDepartmentModal{{ $department->id }}">
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

<!-- Add Department Modal -->
<div class="modal fade" id="addDepartmentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus"></i> Add New Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.store.department') }}">
                @csrf
                <div class="modal-body">
                    
                    <div class="mb-3">
                        <label class="form-label">Department Name *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Course *</label>
                        <select class="form-select" name="courses_id" required>
                            <option value="" selected disabled>Select Course</option>
                            @foreach ($courseData as $course)
                                <option value="{{ $course->id }}">{{ $course->name }}</option>
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
                    <button type="submit" name="add_department" class="btn btn-primary">Add Department</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Department Modals - One for each department -->
@foreach ($departmentData as $department)
<div class="modal fade" id="editDepartmentModal{{ $department->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit"></i> Edit Department</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.update.department', $department->id) }}">
                @csrf
                <div class="modal-body">
                    <input type="hidden" name="id" value="{{ $department->id }}">
                    <div class="mb-3">
                        <label class="form-label">Department Name *</label>
                        <input type="text" name="name" class="form-control" value="{{ $department->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Course *</label>
                        <select class="form-select" name="courses_id" required>
                            <option value="" disabled>Select Course</option>
                            @foreach ($courseData as $course)
                                <option value="{{ $course->id }}" {{ $department->courses_id == $course->id ? 'selected' : '' }}>
                                    {{ $course->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3">{{ $department->description }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</section>
<!-- /.content -->
@endsection

@section('scripts')
<!-- Additional JS specific to the dashboard -->
<script src="{{ asset('js/dashboard.js') }}"></script>
@endsection