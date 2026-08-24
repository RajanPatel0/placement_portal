@extends('layouts.base')

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .app-container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
        }

      .app-header {
    padding: 20px;
margin-bottom: 30px;
    background: linear-gradient(135deg, #6c5ce7, #a29bfe);
    border-radius: 10px;
    color: white;
 
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

/* Optional: make Back button smaller on small screens */
.app-header .btn {
    white-space: nowrap;
}


        .app-title {
            font-size: 2.2rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .app-subtitle {
            font-size: 1rem;
            opacity: 0.9;
        }

        .card1 {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
            border: none;
        }

        .card1-header {
            background: #6c5ce7;
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
            font-weight: 500;
        }

        .card1-body {
            padding: 20px;
        }

        .form-control,
        .form-select {
            border-radius: 8px;
            padding: 12px 15px;
            border: 1px solid #ddd;
            margin-bottom: 15px;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #6c5ce7;
            box-shadow: 0 0 0 0.2rem rgba(108, 92, 231, 0.15);
        }

        .btn {
            border-radius: 8px;
            padding: 10px 20px;
            font-weight: 500;
        }

        .btn-primary {
            background: #6c5ce7;
            border: none;
        }

        .btn-primary:hover {
            background: #5649c5;
        }

        .btn-sm {
            padding: 6px 12px;
            font-size: 0.875rem;
        }

        .skill-item {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 15px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-left: 3px solid #6c5ce7;
        }

        .skill-name {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: 5px;
        }

        .skill-level {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 500;
            background: #e0e0e0;
        }

        .level-beginner {
            background: #ffebee;
            color: #e53935;
        }

        .level-intermediate {
            background: #fff8e1;
            color: #ff8f00;
        }

        .level-advanced {
            background: #e8f5e9;
            color: #43a047;
        }

        .level-expert {
            background: #e3f2fd;
            color: #1e88e5;
        }

        .skill-actions {
            display: flex;
            gap: 10px;
        }

        .empty-state {
            text-align: center;
            padding: 30px 20px;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 15px;
            color: #ced4da;
        }

        .empty-state p {
            font-size: 1.1rem;
            margin-bottom: 20px;
        }

        .progress {
            height: 8px;
            border-radius: 4px;
            margin-top: 8px;
            background: #e9ecef;
        }

        .progress-bar {
            border-radius: 4px;
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
    </style>

    <div class="app-container">
        <div class="app-header d-flex align-items-center justify-content-between">
    <a href="javascript:history.back()" class="btn btn-secondary btn-sm me-3">
        <i class="fas fa-arrow-left"></i> Back 
    </a>

    <div class="text-center flex-grow-1">
        <h1 class="app-title mb-1"><i class="fas fa-laptop-code me-2"></i> Skills Manager</h1>
        <p class="app-subtitle mb-0">Manage your technical skills and proficiency levels</p>
    </div>
</div>


        <div class="notification-container">
            <!-- Notification system remains the same as in your code -->
        </div>

        <div class="row">
            <div class="col-lg-6">
                <div class="card1q">
                    <div class="card1-header">
                        <i class="fas fa-plus-circle me-2"></i> Add New Skill
                    </div>
                    <div class="card1-body">
                        <form action="{{ route('add.skill') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="skill_name" class="form-label">Skill Name</label>
                                <input type="text" name="skill_name" id="skill_name" class="form-control"
                                    placeholder="e.g. JavaScript  (Only One at a Time)" required>
                            </div>
                            <div class="form-group">
                                <label for="proficiency_level" class="form-label">Proficiency Level</label>
                                <select name="proficiency_level" id="proficiency_level" class="form-select" required>
                                    <option value="" disabled selected>Select proficiency level</option>
                                    <option value="beginner">Beginner</option>
                                    <option value="intermediate">Intermediate</option>
                                    <option value="advanced">Advanced</option>
                                    <option value="expert">Expert</option>
                                </select>

                                <div class="progress mt-2">
                                    <div id="level-progress" class="progress-bar" role="progressbar"
                                        style="width: 0%; background-color: #6c5ce7;" aria-valuenow="0" aria-valuemin="0"
                                        aria-valuemax="100"></div>
                                </div>
                                <small id="level-help" class="form-text text-muted">Select your proficiency level</small>
                            </div>
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="fas fa-plus me-2"></i> Add Skill
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-6">
                <div class="card1">
                    <div class="card1-header">
                        <i class="fas fa-cogs me-2"></i> Manage Your Skills
                    </div>
                    <div class="card1-body">
                        <div class="skills-list">
                            @if (isset($skills) && $skills->count() > 0)
                                @foreach ($skills as $skill)
                                    <div class="skill-item">
                                        <div class="skill-info">
                                            <div class="skill-name">{{ $skill->skill_name }}</div>
                                            <span class="skill-level level-{{ $skill->proficiency_level }}">
                                                {{ ucfirst($skill->proficiency_level) }}
                                            </span>
                                        </div>
                                        <div class="skill-actions">
                                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editSkillModal" data-id="{{ $skill->id }}"
                                                data-name="{{ $skill->skill_name }}"
                                                data-level="{{ $skill->proficiency_level }}">
                                                <i class="fas fa-edit"></i>
                                            </button>

                                            <form action="{{ route('delete.skill', $skill->id) }}" method="POST"
                                                class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-danger btn-sm"
                                                    onclick="return confirm('Are you sure you want to delete this skill?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="empty-state">
                                    <i class="fas fa-code"></i>
                                    <p>No skills added yet. Add your first skill to get started!</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Skill Modal -->
    <div class="modal fade" id="editSkillModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Skill</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <form id="editSkillForm" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="edit_skill_name" class="form-label">Skill Name</label>
                            <input type="text" name="skill_name" value id="edit_skill_name" class="form-control"
                                required>
                        </div>
                        <div class="form-group">
                            <label for="edit_proficiency_level" class="form-label">Proficiency Level</label>
                            <select name="proficiency_level" id="edit_proficiency_level" class="form-select" required>
                                <option value="beginner">Beginner</option>
                                <option value="intermediate">Intermediate</option>
                                <option value="advanced">Advanced</option>
                                <option value="expert">Expert</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" form="editSkillForm" class="btn btn-primary">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Update progress bar based on selected proficiency level
        document.getElementById('proficiency_level').addEventListener('change', function() {
            const progressBar = document.getElementById('level-progress');
            const helpText = document.getElementById('level-help');

            switch (this.value) {
                case 'beginner':
                    progressBar.style.width = '25%';
                    progressBar.style.backgroundColor = '#e53935';
                    helpText.textContent = 'You have basic knowledge of this skill';
                    break;
                case 'intermediate':
                    progressBar.style.width = '50%';
                    progressBar.style.backgroundColor = '#ff8f00';
                    helpText.textContent = 'You can work independently on this skill';
                    break;
                case 'advanced':
                    progressBar.style.width = '75%';
                    progressBar.style.backgroundColor = '#43a047';
                    helpText.textContent = 'You have deep knowledge of this skill';
                    break;
                case 'expert':
                    progressBar.style.width = '100%';
                    progressBar.style.backgroundColor = '#1e88e5';
                    helpText.textContent = 'You are an authority on this skill';
                    break;
                default:
                    progressBar.style.width = '0%';
                    helpText.textContent = 'Select your proficiency level';
            }
        });

        // Handle edit modal
        const editSkillModal = document.getElementById('editSkillModal');
        editSkillModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const skillId = button.getAttribute('data-id');
            const skillName = button.getAttribute('data-name');
            const proficiencyLevel = button.getAttribute('data-level');

            // Set form action
            const form = document.getElementById('editSkillForm');
            form.action = `/skill/edit/${skillId}`;

            // Set form field values
            document.getElementById('edit_skill_name').value = skillName;
            document.getElementById('edit_proficiency_level').value = proficiencyLevel;
        });

        // Auto-hide notifications after 5 seconds
        setTimeout(() => {
            document.querySelectorAll('.notification').forEach(notification => {
                notification.style.opacity = '0';
                setTimeout(() => {
                    notification.style.display = 'none';
                }, 1000);
            });
        }, 5000);

        // Manually close notification
        function closeNotification(id) {
            const element = document.getElementById(id);
            if (element) {
                element.style.opacity = '0';
                setTimeout(() => {
                    element.style.display = 'none';
                }, 1000);
            }
        }
    </script>


@endsection
