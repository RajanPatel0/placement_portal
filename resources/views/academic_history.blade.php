@extends('layouts.base')
@section('content')
<!-- Bootstrap 5 CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
    :root {
        --primary-color: #4361ee;
        --secondary-color: #3f37c9;
        --light-color: #f8f9fa;
        --dark-color: #212529;
        --success-color: #4cc9f0;
        --warning-color: #f72585;
        --border-radius: 12px;
        --card-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        --transition: all 0.3s ease;
    }

    .app-container { max-width: 800px; margin: 0 auto; background: white; box-shadow: var(--card-shadow); overflow: hidden; }
    .app-header { background: var(--primary-color); color: white; padding: 20px; text-align: center; position: relative; }
    .app-header h1 { margin: 0; font-size: 1.8rem; font-weight: 600; }
    .app-body { padding: 20px; }
    .history-card { background: white; border-radius: var(--border-radius); box-shadow: var(--card-shadow); margin-bottom: 10px; padding: 20px; transition: var(--transition); border-left: 4px solid var(--primary-color); }
    .history-card:hover { transform: translateY(-3px); box-shadow: 0 6px 16px rgba(0, 0, 0, 0.12); }
    .history-card-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 15px; }
    .history-card-title { font-weight: 600; font-size: 1.2rem; color: var(--dark-color); margin: 0; }
    .history-card-dates { color: #6c757d; font-size: 0.9rem; }
    .history-card-actions { display: flex; gap: 10px; }
    .btn-app { border-radius: 8px; padding: 8px 16px; font-weight: 500; transition: var(--transition); }
    .btn-primary { background: var(--primary-color); border: none; }
    .btn-primary:hover { background: var(--secondary-color); transform: translateY(-2px); }
    .btn-edit { background: #7209b7; color: white; border: none; }
    .btn-delete { background: #f72585; color: white; border: none; }
    .btn-add { background: var(--primary-color); color: white; border: none; display: flex; align-items: center; gap: 8px; margin-bottom: 20px; }
    .form-container { background: white; border-radius: var(--border-radius); box-shadow: var(--card-shadow); padding: 25px; margin-bottom: 20px; }
    .form-title { font-weight: 600; margin-bottom: 20px; color: var(--primary-color); font-size: 1.4rem; }
    .form-label { font-weight: 500; margin-bottom: 5px; color: #495057; }
    .form-control { border-radius: 8px; padding: 10px 15px; border: 1px solid #ced4da; transition: var(--transition); }
    .form-control:focus { border-color: var(--primary-color); box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.15); }
    .alert { border-radius: 10px; padding: 15px 20px; margin-bottom: 20px; }
    .empty-state { text-align: center; padding: 40px 20px; color: #6c757d; }
    .empty-state i { font-size: 3rem; margin-bottom: 15px; color: #adb5bd; }
    .empty-state p { font-size: 1.1rem; margin-bottom: 20px; }
    .modal-content { border-radius: var(--border-radius); border: none; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15); }
    .modal-header { background: var(--primary-color); color: white; border-top-left-radius: var(--border-radius); border-top-right-radius: var(--border-radius); }
    @media (max-width: 576px) {
        .app-body { padding: 15px; }
        .history-card-header { flex-direction: column; }
        .history-card-actions { margin-top: 10px; width: 100%; }
        .btn-app { flex: 1; }
    }
    .notification-container { position: fixed; top: 50px; right: 20px; z-index: 1050; display: flex; flex-direction: column; gap: 15px; }
    .notification { max-width: 300px; padding: 20px 25px; background: linear-gradient(to right, #4caf50, #81c784); color: #fff; font-family: 'Arial', sans-serif; display: flex; flex-direction: column; gap: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); animation: slideIn 0.3s ease-out; position: relative; }
    .notification.error { background: linear-gradient(to right, #e53935, #ef5350); }
    .notification p, .notification ul { margin: 0; }
    .notification ul { padding-left: 20px; list-style: none; font-size: 0.9rem; }
    .close-btn { position: absolute; top: .5px; right: .5px; background: transparent; border: none; font-size: 1.2rem; font-weight: bold; color: #fff; cursor: pointer; }
</style>

<!-- Notifications -->
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

<script>
    setTimeout(() => {
        document.querySelectorAll('.notification').forEach(notification => {
            notification.style.opacity = '0';
            setTimeout(() => { notification.style.display = 'none'; }, 1000);
        });
    }, 10000);

    function closeNotification(id) {
        const element = document.getElementById(id);
        if (element) {
            element.style.opacity = '0';
            setTimeout(() => { element.style.display = 'none'; }, 1000);
        }
    }
</script>

<div class="app-container mb-5">
    <div class="app-header d-flex align-items-center justify-content-between">
    <!-- Back Button -->
    <a href="javascript:history.back()" class="btn btn-light btn-sm">
        <i class="fas fa-arrow-left me-1"></i> Back
    </a>

    <!-- Title -->
    <h1 class="app-title mb-0"><i class="fas fa-graduation-cap me-2"></i> Academic History</h1>

    <!-- Empty div to balance flex -->
    <div style="width: 75px;"></div>
</div>


    <div class="app-body">
        <!-- Add New Button -->
        <button type="button" class="btn btn-add btn-app" data-bs-toggle="modal" data-bs-target="#addHistoryModal">
            <i class="fas fa-plus"></i> Add Academic History
        </button>

        <!-- Academic History List -->
        @forelse ($academicHistory as $history)
            <div class="history-card">
                <div class="history-card-header">
                    <div>
                        <h3 class="history-card-title">{{ $history->institution_name }}</h3>
                        <div class="history-card-dates">
                            <strong>Education Level:</strong> {{ ucfirst($history->education_level) }} | 
                            {{ $history->start_date }} - {{ $history->end_date }}
                        </div>
                    </div>
                    <div class="history-card-actions">
                        <button class="btn btn-app btn-edit" data-bs-toggle="modal" data-bs-target="#editHistoryModal-{{ $history->id }}">
                            <i class="fas fa-edit"></i> Edit
                        </button>
                        <button class="btn btn-app btn-delete" data-bs-toggle="modal" data-bs-target="#deleteHistoryModal-{{ $history->id }}">
                            <i class="fas fa-trash"></i> Delete
                        </button>
                    </div>
                </div>
                <div class="history-card-details">
                    <p class="mb-1"><strong>Degree:</strong> {{ $history->degree ?? 'Not specified' }}</p>
                    <p class="mb-1"><strong>Field of Study:</strong> {{ $history->field_of_study ?? 'Not specified' }}</p>
                    @if($history->grade)
                        <p class="mb-1"><strong>Grade:</strong> {{ $history->grade }} ({{ $history->grade_type }})</p>
                    @endif
                    @if($history->description)
                        <p class="mb-0"><strong>Description:</strong> {{ $history->description }}</p>
                    @endif
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editHistoryModal-{{ $history->id }}" tabindex="-1" aria-labelledby="editHistoryModalLabel-{{ $history->id }}" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editHistoryModalLabel-{{ $history->id }}">Edit Academic History</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                        </div>
                        <form action="/academic-history/edit/{{ $history->id }}" method="POST">
                            @csrf
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Education Level *</label>
                                        <select class="form-select" name="education_level" required>
                                            <option value="">Select Education Level</option>
                                            <option value="10th" {{ $history->education_level == '10th' ? 'selected' : '' }}>10th</option>
                                            <option value="12th" {{ $history->education_level == '12th' ? 'selected' : '' }}>12th</option>
                                            <option value="diploma" {{ $history->education_level == 'diploma' ? 'selected' : '' }}>Diploma</option>
                                            <option value="bachelor" {{ $history->education_level == 'bachelor' ? 'selected' : '' }}>Bachelor</option>
                                            <option value="master" {{ $history->education_level == 'master' ? 'selected' : '' }}>Master</option>
                                            <option value="phd" {{ $history->education_level == 'phd' ? 'selected' : '' }}>PhD</option>
                                            <option value="other" {{ $history->education_level == 'other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Institution Name *</label>
                                        <input type="text" class="form-control" name="institution_name" value="{{ $history->institution_name }}" required>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Degree</label>
                                        <input type="text" class="form-control" name="degree" value="{{ $history->degree }}">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">Field of Study</label>
                                        <input type="text" class="form-control" name="field_of_study" value="{{ $history->field_of_study }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Start Date</label>
                                        <input type="date" class="form-control" name="start_date" value="{{ $history->start_date }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">End Date</label>
                                        <input type="date" class="form-control" name="end_date" value="{{ $history->end_date }}">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Grade Type</label>
                                        <select class="form-select" name="grade_type">
                                            <option value="">Select Grade Type</option>
                                            <option value="gpa" {{ $history->grade_type == 'gpa' ? 'selected' : '' }}>CGPA</option>
                                            <option value="percentage" {{ $history->grade_type == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                        </select>
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="form-label">Grade</label>
                                        <input type="number" step="0.01" class="form-control" name="grade" value="{{ $history->grade }}" min="0" max="100">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Description</label>
                                    <textarea class="form-control" name="description" rows="3">{{ $history->description }}</textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Delete Modal -->
            <div class="modal fade" id="deleteHistoryModal-{{ $history->id }}" tabindex="-1" aria-labelledby="deleteHistoryModalLabel-{{ $history->id }}" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="deleteHistoryModalLabel-{{ $history->id }}">Confirm Deletion</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Are you sure you want to delete this academic history entry? This action cannot be undone.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <form action="/academic-history/delete/{{ $history->id }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger">Delete</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        @empty
            <div class="empty-state">
                <i class="fas fa-graduation-cap"></i>
                <p>No academic history found. Click "Add Academic History" to create one.</p>
            </div>
        @endforelse

    </div>
</div>

<!-- Add Academic History Modal -->
<div class="modal fade" id="addHistoryModal" tabindex="-1" aria-labelledby="addHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addHistoryModalLabel">Add Academic History</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="/academic-history/add" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Education Level *</label>
                            <select class="form-select" name="education_level" required>
                                <option value="" disabled selected>Select Education Level</option>
                                <option value="10th">10th (Secondary School)</option>
                                <option value="12th">12th (Higher Secondary)</option>
                                <option value="diploma">Diploma</option>
                                <option value="bachelor">Bachelor's Degree</option>
                                <option value="master">Master's Degree</option>
                                <option value="phd">PhD/Doctorate</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Institution Name *</label>
                            <input type="text" class="form-control" name="institution_name" 
                                   placeholder="e.g., University of California, Berkeley" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Degree *</label>
                            <input type="text" class="form-control" name="degree" 
                                   placeholder="e.g., matriculation, intermediate " required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Field of Study </label>
                            <input type="text" class="form-control" name="field_of_study" 
                                   placeholder="e.g., Computer Science">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Start Date</label>
                            <input type="date" class="form-control" name="start_date">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">End Date</label>
                            <input type="date" class="form-control" name="end_date">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Grade Type *</label>
                            <select class="form-select" name="grade_type" required>
                                <option value="" selected>Select Grade Type</option>
                                <option value="gpa">CGPA</option>
                                <option value="percentage">Percentage (%)</option>
                            </select>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Grade *</label>
                            <input type="number" step="0.01" class="form-control" name="grade" 
                                   min="0" max="100" placeholder="" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea class="form-control" name="description" rows="3" 
                                  placeholder="Brief description of your academic achievements, courses, honors, etc.(Max 250 characters)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection