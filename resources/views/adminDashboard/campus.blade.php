@extends('dashboardLayouts.base')

@section('title', 'Campus Management')

@section('content')

    <div class="container">
        <!-- Notification Container -->
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
                right: .5px;
                background: transparent;
                border: none;
                font-size: 1.2rem;
                font-weight: bold;
                color: #fff;
                cursor: pointer;
            }

            @keyframes slideIn {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }

                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }

            .action-buttons {
                display: flex;
                gap: 5px;
            }

            .btn-sm {
                padding: 0.25rem 0.5rem;
                font-size: 0.875rem;
            }

            .modal-header {
                background-color: #f8f9fa;
                border-bottom: 1px solid #dee2e6;
            }

            .modal-title {
                color: #343a40;
            }

            .form-label {
                font-weight: 500;
                margin-bottom: 0.5rem;
            }

            .college-badge {
                display: inline-block;
                padding: 2px 8px;
                border-radius: 12px;
                font-size: 0.8rem;
                background-color: #e9ecef;
                color: #495057;
                margin-left: 8px;
            }
        </style>

        <script>
            // Auto-hide the notification after 10 seconds
            setTimeout(() => {
                document.querySelectorAll('.notification').forEach(notification => {
                    notification.style.opacity = '0';
                    setTimeout(() => {
                        notification.style.display = 'none';
                    }, 1000);
                });
            }, 10000);

            // Manually close the notification
            function closeNotification(id) {
                const element = document.getElementById(id);
                if (element) {
                    element.style.opacity = '0';
                    setTimeout(() => {
                        element.style.display = 'none';
                    }, 1000);
                }
            }

            // Function to open edit modal
            function openEditModal(id, name, collegeId) {
                document.getElementById('editCampusId').value = id;
                document.getElementById('editCampusName').value = name;
                document.getElementById('editCollegeId').value = collegeId;
                const editModal = new bootstrap.Modal(document.getElementById('editCampusModal'));
                editModal.show();
            }

            // Function to confirm deletion
            function confirmDelete(id, name) {
                if (confirm(`Are you sure you want to delete campus "${name}"?`)) {
                    document.getElementById('deleteForm' + id).submit();
                }
            }

            // Function to open delete modal (alternative approach)
            function openDeleteModal(id, name) {
                document.getElementById('deleteCampusId').value = id;
                document.getElementById('deleteCampusName').textContent = name;
                const deleteModal = new bootstrap.Modal(document.getElementById('deleteCampusModal'));
                deleteModal.show();
            }
        </script>

        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Campus Management</h5>
                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal"
                        data-bs-target="#addCampusModal">
                        <i class="fas fa-plus"></i> Add New Campus
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if ($campusData->isEmpty())
                    <div class="alert alert-info">
                        No campuses found. Add your first campus using the button above.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th width="80">ID</th>
                                    <th>Campus Name</th>
                                    <th>College</th>
                                    <th width="150">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($campusData as $campus)
                                    <tr>
                                        <td>{{ $campus->id }}</td>
                                        <td>
                                            {{ $campus->name }}
                                            @if (!empty($campus->college_name))
                                                <span class="college-badge">{{ $campus->college_name }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            {{ $campus->college_name ?? 'Not Assigned' }}
                                        </td>
                                        <td class="action-buttons">
                                            <button type="button" class="btn btn-warning btn-sm"
                                                onclick="openEditModal({{ $campus->id }}, '{{ addslashes($campus->name) }}', {{ $campus->college_id ?? 'null' }})">
                                                <i class="fas fa-edit"></i> Edit
                                            </button>
                                            <button type="button" class="btn btn-danger btn-sm"
                                                onclick="confirmDelete({{ $campus->id }}, '{{ addslashes($campus->name) }}')">
                                                <i class="fas fa-trash"></i> Delete
                                            </button>

                                            <!-- Delete Form (Hidden) -->
                                            <form id="deleteForm{{ $campus->id }}"
                                                action="{{ route('admin.campus.delete', $campus->id) }}" method="POST"
                                                style="display: none;">
                                                @csrf
                                                @method('DELETE')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Add Campus Modal -->
    <div class="modal fade" id="addCampusModal" tabindex="-1" aria-labelledby="addCampusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.campus.add') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCampusModalLabel">Add New Campus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Campus Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                                name="name" value="{{ old('name') }}" placeholder="Enter campus name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="college_id" class="form-label">College <span class="text-danger">*</span></label>
                            <select class="form-control @error('college_id') is-invalid @enderror" id="college_id"
                                name="college_id" required>
                                <option value="">Select College</option>
                                @foreach ($colleges as $college)
                                    <option value="{{ $college->id }}"
                                        {{ old('college_id') == $college->id ? 'selected' : '' }}>
                                        {{ $college->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('college_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Add Campus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Campus Modal -->
    <div class="modal fade" id="editCampusModal" tabindex="-1" aria-labelledby="editCampusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.campus.update', '') }}" method="POST" id="editCampusForm">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editCampusModalLabel">Edit Campus</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="editCampusId" name="campus_id">
                        <div class="mb-3">
                            <label for="editCampusName" class="form-label">Campus Name <span
                                    class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                id="editCampusName" name="name" placeholder="Enter campus name" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="editCollegeId" class="form-label">College <span
                                    class="text-danger">*</span></label>
                            <select class="form-control @error('college_id') is-invalid @enderror" id="editCollegeId"
                                name="college_id" required>
                                <option value="">Select College</option>
                                @foreach ($colleges as $college)
                                    <option value="{{ $college->id }}">
                                        {{ $college->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('college_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Update Campus</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal (Alternative) -->
    <div class="modal fade" id="deleteCampusModal" tabindex="-1" aria-labelledby="deleteCampusModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title text-danger" id="deleteCampusModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete campus "<span id="deleteCampusName" class="fw-bold"></span>"?</p>
                    <p class="text-danger"><small>This action cannot be undone.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <form action="{{ route('admin.campus.delete', '') }}" method="POST" id="deleteModalForm">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" id="deleteCampusId" name="campus_id">
                        <button type="submit" class="btn btn-danger">Delete Campus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Update form action URLs when modals are shown
        document.addEventListener('DOMContentLoaded', function() {
            const editModal = document.getElementById('editCampusModal');
            if (editModal) {
                editModal.addEventListener('show.bs.modal', function(event) {
                    const form = document.getElementById('editCampusForm');
                    const campusId = document.getElementById('editCampusId').value;
                    form.action = "{{ route('admin.campus.update', '') }}/" + campusId;

                    // Set selected college in dropdown
                    const collegeId = document.getElementById('editCollegeId').value;
                    if (collegeId) {
                        const collegeSelect = document.getElementById('editCollegeId');
                        for (let i = 0; i < collegeSelect.options.length; i++) {
                            if (collegeSelect.options[i].value == collegeId) {
                                collegeSelect.options[i].selected = true;
                                break;
                            }
                        }
                    }
                });
            }

            const deleteModal = document.getElementById('deleteCampusModal');
            if (deleteModal) {
                deleteModal.addEventListener('show.bs.modal', function(event) {
                    const form = document.getElementById('deleteModalForm');
                    const campusId = document.getElementById('deleteCampusId').value;
                    form.action = "{{ route('admin.campus.delete', '') }}/" + campusId;
                });
            }
        });
    </script>

@endsection
