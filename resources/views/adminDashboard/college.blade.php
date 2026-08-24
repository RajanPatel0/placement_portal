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
        
        .no-results {
            text-align: center;
            padding: 20px;
            color: #6c757d;
            font-style: italic;
        }
        
        .table-hover tbody tr {
            transition: all 0.3s ease;
        }
        
        .search-info {
            font-size: 0.9rem;
            color: #6c757d;
            margin-top: 5px;
        }
    </style>


    <div class="container pt-4 pb-4">
        <h2 class="mb-4">College Management System</h2>


        <div class="tab-content" id="myTabContent">
            <!-- Colleges Tab -->
            <div class="">
                <div class="card">
                  <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
    <span style="display: flex; align-items: center;">
        <i class="fas fa-university" style="margin-right: 8px;"></i>
        <span>Colleges</span>
    </span>
    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addCollegeModal">
        <i class="fas fa-plus" style="margin-right: 4px;"></i> Add College
    </button>
</div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="search-box">
                                <i class="fas fa-search"></i>
                                <input type="text" id="searchInput" class="form-control"
                                    placeholder="Search colleges by name, location, email, or phone...">
                            </div>
                            <div class="search-info" id="searchInfo">
                                Showing all {{ count($collegeData) }} colleges
                            </div>
                        </div>
                        <div class="table-container">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Name</th>
                                        <th>Location</th>
                                        <th>Website</th>
                                        <th>Contact Email</th>
                                        <th>Contact Phone</th>
                                        <th>Courses View</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="collegeTableBody">
                                    @foreach ($collegeData as $collegeItem)
                                    <tr class="college-row">
                                        <td>{{ $collegeItem->id }}</td>
                                        <td class="college-name">{{ $collegeItem->name }}</td>
                                        <td class="college-location">{{ $collegeItem->location }}</td>
                                        <td class="college-website">{{ $collegeItem->website }}</td>
                                        <td class="college-email">{{ $collegeItem->contact_email }}</td>
                                        <td class="college-phone">{{ $collegeItem->contact_phone }}</td>
                                        <td><a href="{{ route('admin.college.courses', $collegeItem->id) }}"><span
                                                    class="entity-badge course-badge">{{ $courseData->where('college_id', $collegeItem->id)->count() }}
                                                    Courses</span></a></td>

                                        <td class="action-buttons">
                                            <button class="btn btn-sm btn-primary btn-action edit-college-btn" 
                                                data-bs-toggle="modal"
                                                data-bs-target="#editCollegeModal"
                                                data-college-id="{{ $collegeItem->id }}"
                                                data-college-name="{{ $collegeItem->name }}"
                                                data-college-location="{{ $collegeItem->location }}"
                                                data-college-website="{{ $collegeItem->website }}"
                                                data-college-email="{{ $collegeItem->contact_email }}"
                                                data-college-phone="{{ $collegeItem->contact_phone }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            <div id="noResults" class="no-results" style="display: none;">
                                <i class="fas fa-search fa-2x mb-2"></i>
                                <p>No colleges found matching your search.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </div>

    <!-- Add College Modal -->
    <div class="modal fade" id="addCollegeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-plus"></i> Add New College</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('admin.store.college') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">College Name *</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Website</label>
                            <input type="url" name="website" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact Email</label>
                            <input type="email" name="contact_email" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact Phone</label>
                            <input type="tel" name="contact_phone" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="add_college" class="btn btn-primary">Add College</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit College Modal -->
    <div class="modal fade" id="editCollegeModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-edit"></i> Edit College</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="" id="editCollegeForm">
                    @csrf
                   
                    <div class="modal-body">
                        <input type="hidden" name="college_id" id="edit_college_id">
                        <div class="mb-3">
                            <label class="form-label">College Name *</label>
                            <input type="text" name="name" id="edit_college_name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Location</label>
                            <input type="text" name="location" id="edit_college_location" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Website</label>
                            <input type="url" name="website" id="edit_college_website" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact Email</label>
                            <input type="email" name="contact_email" id="edit_college_email" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contact Phone</label>
                            <input type="tel" name="contact_phone" id="edit_college_phone" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="update_college" class="btn btn-primary">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const collegeRows = document.querySelectorAll('.college-row');
            const noResults = document.getElementById('noResults');
            const searchInfo = document.getElementById('searchInfo');
            const totalColleges = {{ count($collegeData) }};
            
            // Handle edit college button clicks
            const editButtons = document.querySelectorAll('.edit-college-btn');
            const editModal = document.getElementById('editCollegeModal');
            const editForm = document.getElementById('editCollegeForm');
            
            // Search functionality
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase().trim();
                let visibleCount = 0;
                
                collegeRows.forEach(row => {
                    const name = row.querySelector('.college-name').textContent.toLowerCase();
                    const location = row.querySelector('.college-location').textContent.toLowerCase();
                    const email = row.querySelector('.college-email').textContent.toLowerCase();
                    const phone = row.querySelector('.college-phone').textContent.toLowerCase();
                    const website = row.querySelector('.college-website').textContent.toLowerCase();
                    
                    // Check if search term exists in any of the fields
                    const isVisible = name.includes(searchTerm) || 
                                     location.includes(searchTerm) || 
                                     email.includes(searchTerm) || 
                                     phone.includes(searchTerm) ||
                                     website.includes(searchTerm);
                    
                    if (isVisible) {
                        row.style.display = '';
                        visibleCount++;
                    } else {
                        row.style.display = 'none';
                    }
                });
                
                // Show/hide no results message
                if (visibleCount === 0 && searchTerm !== '') {
                    noResults.style.display = 'block';
                    document.querySelector('table').style.display = 'none';
                } else {
                    noResults.style.display = 'none';
                    document.querySelector('table').style.display = 'table';
                }
                
                // Update search info
                if (searchTerm === '') {
                    searchInfo.textContent = `Showing all ${totalColleges} colleges`;
                } else {
                    searchInfo.textContent = `Found ${visibleCount} college(s) matching "${searchTerm}"`;
                }
            });
            
            // Edit modal functionality
            editButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const collegeId = this.getAttribute('data-college-id');
                    const collegeName = this.getAttribute('data-college-name');
                    const collegeLocation = this.getAttribute('data-college-location');
                    const collegeWebsite = this.getAttribute('data-college-website');
                    const collegeEmail = this.getAttribute('data-college-email');
                    const collegePhone = this.getAttribute('data-college-phone');
                    
                    // Set form action URL
                    editForm.action = "{{ route('admin.update.college', '') }}/" + collegeId;
                    
                    // Populate form fields
                    document.getElementById('edit_college_id').value = collegeId;
                    document.getElementById('edit_college_name').value = collegeName;
                    document.getElementById('edit_college_location').value = collegeLocation;
                    document.getElementById('edit_college_website').value = collegeWebsite;
                    document.getElementById('edit_college_email').value = collegeEmail;
                    document.getElementById('edit_college_phone').value = collegePhone;
                });
            });
            
            // Clear form data when modal is closed
            editModal.addEventListener('hidden.bs.modal', function () {
                editForm.reset();
                editForm.action = '';
            });
            
            // Clear search when Escape key is pressed
            searchInput.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    this.value = '';
                    this.dispatchEvent(new Event('input'));
                    this.blur();
                }
            });
            
            // Focus search input on Ctrl+F
            document.addEventListener('keydown', function(e) {
                if ((e.ctrlKey || e.metaKey) && e.key === 'f') {
                    e.preventDefault();
                    searchInput.focus();
                    searchInput.select();
                }
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