@extends('layouts.base')

@section('content')

    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --success-color: #2ecc71;
            --danger-color: #e74c3c;
            --warning-color: #f39c12;
            --light-bg: #f8f9fa;
            --card-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        body {
            background-color: #f5f7fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
        }
        
       
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: var(--card-shadow);
            transition: transform 0.3s ease;
            margin-bottom: 20px;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border: none;
            padding: 8px 20px;
            border-radius: 6px;
            font-weight: 500;
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
        }
        
        .experience-card {
            border-left: 4px solid var(--primary-color);
            position: relative;
        }
        
        .level-badge {
            position: absolute;
            top: 15px;
            right: 15px;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .level-beginner { background: var(--success-color); color: white; }
        .level-intermediate { background: var(--warning-color); color: white; }
        .level-expert { background: var(--danger-color); color: white; }
        
        .current-badge {
            background: var(--primary-color);
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
            margin-left: 10px;
        }
        
        .form-section {
            background-color: white;
            border-radius: 10px;
            padding: 25px;
            box-shadow: var(--card-shadow);
            margin-bottom: 30px;
        }
        
        .alert {
            border-radius: 8px;
            border: none;
        }
        
        .page-title {
            color: var(--secondary-color);
            font-weight: 700;
            margin-bottom: 25px;
            padding-bottom: 10px;
            border-bottom: 2px solid var(--primary-color);
            display: inline-block;
        }
        
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #6c757d;
        }
        
        .empty-state i {
            font-size: 4rem;
            margin-bottom: 20px;
            color: #dee2e6;
        }
        
        .timeline {
            position: relative;
            padding-left: 30px;
        }
        
        .timeline::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--primary-color);
        }
        
        .timeline-item {
            position: relative;
            margin-bottom: 30px;
        }
        
        .timeline-item::before {
            content: '';
            position: absolute;
            left: -23px;
            top: 5px;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--primary-color);
            border: 3px solid white;
        }
        
        .duration {
            color: #6c757d;
            font-size: 0.9rem;
            margin-bottom: 5px;
        }
        
        .company-name {
            font-weight: 600;
            color: var(--secondary-color);
            margin-bottom: 5px;
        }
        
        .tittle {
            color: var(--primary-color);
            font-weight: 500;
            margin-bottom: 10px;
        }
        
        .skill-tag {
            display: inline-block;
            background-color: #e1f0fa;
            color: var(--primary-color);
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 0.85rem;
            margin: 3px;
        }
        
        .skills-input-group {
            margin-bottom: 10px;
        }
        
        .skill-input-row {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }
        
        .skill-input-row input {
            flex: 1;
        }
        
        .add-skill-btn {
            white-space: nowrap;
        }
        
        .skills-display {
            min-height: 40px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            padding: 10px;
            margin-top: 5px;
            background-color: #f8f9fa;
        }
        
        .is-invalid {
            border-color: #dc3545;
        }
        
        .invalid-feedback {
            display: block;
        }
    </style>


    <div class=" my-5">
        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Display Validation Errors -->
        @if($errors->any())
            <div class="alert alert-danger">
                <h5>Please fix the following errors:</h5>
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- Page Title -->
        <h1 class="page-title ml-3">Work Experience</h1>

        <!-- Add Experience Form -->
        <div class="form-section">
            <h4 class="mb-4"><i class="fas fa-plus-circle me-2"></i>Add New Experience</h4>
            <form method="POST" action="{{ route('experience.add') }}">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="experience_level" class="form-label">Experience Level *</label>
                        <select class="form-control @error('experience_level') is-invalid @enderror" 
                                id="experience_level" name="experience_level" required>
                            <option value="">Select Level</option>
                            <option value="beginner" {{ old('experience_level') == 'beginner' ? 'selected' : '' }}>Beginner</option>
                            <option value="intermediate" {{ old('experience_level') == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                            <option value="expert" {{ old('experience_level') == 'expert' ? 'selected' : '' }}>Expert</option>
                        </select>
                        @error('experience_level')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tittle" class="form-label">Job Title *</label>
                        <input type="text" class="form-control @error('tittle') is-invalid @enderror" 
                               id="tittle" name="tittle" value="{{ old('tittle') }}" required>
                        @error('tittle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="company_name" class="form-label">Company Name *</label>
                        <input type="text" class="form-control @error('company_name') is-invalid @enderror" 
                               id="company_name" name="company_name" value="{{ old('company_name') }}" required>
                        @error('company_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="start_date" class="form-label">Start Date *</label>
                        <input type="date" class="form-control @error('start_date') is-invalid @enderror" 
                               id="start_date" name="start_date" value="{{ old('start_date') }}" required>
                        @error('start_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="end_date" class="form-label">End Date</label>
                        <input type="date" class="form-control @error('end_date') is-invalid @enderror" 
                               id="end_date" name="end_date" value="{{ old('end_date') }}">
                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="current_job" 
                                   {{ old('current_job') ? 'checked' : '' }} onchange="toggleEndDate()">
                            <label class="form-check-label" for="current_job">
                                I currently work here
                            </label>
                        </div>
                        @error('end_date')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="4" 
                              placeholder="Describe your responsibilities and achievements...">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Skills</label>
                    <div class="skills-input-group">
                        <div class="skill-input-row">
                            <input type="text" class="form-control" id="newSkill" placeholder="Enter a skill">
                            <button type="button" class="btn btn-outline-primary add-skill-btn" onclick="addSkill()">
                                <i class="fas fa-plus me-1"></i> Add Skill
                            </button>
                        </div>
                        <div class="skills-display" id="skillsDisplay">
                            @if(old('skills'))
                                @foreach(old('skills') as $skill)
                                    <span class="skill-tag">
                                        {{ $skill }}
                                        <input type="hidden" name="skills[]" value="{{ $skill }}">
                                        <i class="fas fa-times ms-1" onclick="removeSkill(this)"></i>
                                    </span>
                                @endforeach
                            @else
                                <span class="text-muted">No skills added yet</span>
                            @endif
                        </div>
                        @error('skills')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                        @error('skills.*')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i> Add Experience
                </button>
            </form>
        </div>

        <!-- Experience List -->
        <div class="form-section">
            <h4 class="mb-4"><i class="fas fa-history me-2"></i>Experience Timeline</h4>
            
            @if(isset($experiences) && $experiences->count() > 0)
                    @foreach($experiences as $experience)
                            <div class="card experience-card">
                                <div class="card-body">
                                    <span class="level-badge level-{{ $experience->experience_level }}">
                                        {{ ucfirst($experience->experience_level) }}
                                    </span>
                                    
                                    @if(!$experience->end_date || $experience->end_date > now())
                                        <span class="current-badge">Current</span>
                                    @endif
                                    
                                    <div class="duration">
                                        <i class="far fa-calendar me-1"></i>
                                        {{ \Carbon\Carbon::parse($experience->start_date)->format('M Y') }} - 
                                        @if($experience->end_date)
                                            {{ \Carbon\Carbon::parse($experience->end_date)->format('M Y') }}
                                        @else
                                            Present
                                        @endif
                                    </div>
                                    
                                    <div class="company-name">
                                        <i class="fas fa-building me-2"></i>{{ $experience->company_name }}
                                    </div>
                                    
                                    <div class="tittle">
                                        <i class="fas fa-user-tie me-2"></i>{{ $experience->tittle }}
                                    </div>
                                    
                                    @if($experience->description)
                                        <p class="card-text mt-3">{{ $experience->description }}</p>
                                    @endif

                                    @if($experience->skills)
                                        @php
                                            $skills = json_decode($experience->skills, true);
                                        @endphp
                                        @if(is_array($skills) && count($skills) > 0)
                                            <div class="mt-3">
                                                <strong>Skills:</strong><br>
                                                @foreach($skills as $skill)
                                                    <span class="skill-tag">{{ $skill }}</span>
                                                @endforeach
                                            </div>
                                        @endif
                                    @endif
                                    
                                    <div class="mt-3">
                                        <!-- Edit Button -->
                                        <button type="button" class="btn btn-sm btn-outline-primary" 
                                                data-bs-toggle="modal" data-bs-target="#editExperienceModal{{ $experience->id }}">
                                            <i class="fas fa-edit me-1"></i> Edit
                                        </button>
                                        
                                        <!-- Delete Form -->
                                        <form method="POST" action="{{ route('experience.delete', $experience->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Are you sure you want to delete this experience?')">
                                                <i class="fas fa-trash me-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        <!-- Edit Experience Modal -->
                        <div class="modal fade" id="editExperienceModal{{ $experience->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Experience</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('experience.edit', $experience->id) }}">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="edit_experience_level{{ $experience->id }}" class="form-label">Experience Level *</label>
                                                    <select class="form-control" id="edit_experience_level{{ $experience->id }}" name="experience_level" required>
                                                        <option value="beginner" {{ $experience->experience_level == 'beginner' ? 'selected' : '' }}>Beginner</option>
                                                        <option value="intermediate" {{ $experience->experience_level == 'intermediate' ? 'selected' : '' }}>Intermediate</option>
                                                        <option value="expert" {{ $experience->experience_level == 'expert' ? 'selected' : '' }}>Expert</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="edit_tittle{{ $experience->id }}" class="form-label">Job Title *</label>
                                                    <input type="text" class="form-control" 
                                                           id="edit_tittle{{ $experience->id }}" name="tittle" 
                                                           value="{{ $experience->tittle }}" required>
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="edit_company_name{{ $experience->id }}" class="form-label">Company Name *</label>
                                                    <input type="text" class="form-control" 
                                                           id="edit_company_name{{ $experience->id }}" name="company_name" 
                                                           value="{{ $experience->company_name }}" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="edit_start_date{{ $experience->id }}" class="form-label">Start Date *</label>
                                                    <input type="date" class="form-control" 
                                                           id="edit_start_date{{ $experience->id }}" name="start_date" 
                                                           value="{{ $experience->start_date }}" required>
                                                </div>
                                            </div>
                                            
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="edit_end_date{{ $experience->id }}" class="form-label">End Date</label>
                                                    <input type="date" class="form-control" 
                                                           id="edit_end_date{{ $experience->id }}" name="end_date" 
                                                           value="{{ $experience->end_date }}">
                                                    <div class="form-check mt-2">
                                                        <input class="form-check-input" type="checkbox" 
                                                               id="edit_current_job{{ $experience->id }}" 
                                                               onchange="toggleEditEndDate('{{ $experience->id }}')"
                                                               {{ !$experience->end_date ? 'checked' : '' }}>
                                                        <label class="form-check-label" for="edit_current_job{{ $experience->id }}">
                                                            I currently work here
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <div class="mb-3">
                                                <label for="edit_description{{ $experience->id }}" class="form-label">Description</label>
                                                <textarea class="form-control" id="edit_description{{ $experience->id }}" 
                                                          name="description" rows="4">{{ $experience->description }}</textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label class="form-label">Skills</label>
                                                <div class="skills-input-group">
                                                    <div class="skill-input-row">
                                                        <input type="text" class="form-control" 
                                                               id="edit_newSkill{{ $experience->id }}" placeholder="Enter a skill">
                                                        <button type="button" class="btn btn-outline-primary add-skill-btn" 
                                                                onclick="addEditSkill('{{ $experience->id }}')">
                                                            <i class="fas fa-plus me-1"></i> Add Skill
                                                        </button>
                                                    </div>
                                                    <div class="skills-display" id="edit_skillsDisplay{{ $experience->id }}">
                                                        @php
                                                            $skills = json_decode($experience->skills, true);
                                                        @endphp
                                                        @if(is_array($skills) && count($skills) > 0)
                                                            @foreach($skills as $skill)
                                                                <span class="skill-tag">
                                                                    {{ $skill }}
                                                                    <input type="hidden" name="skills[]" value="{{ $skill }}">
                                                                    <i class="fas fa-times ms-1" onclick="removeEditSkill(this, '{{ $experience->id }}')"></i>
                                                                </span>
                                                            @endforeach
                                                        @else
                                                            <span class="text-muted">No skills added yet</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Update Experience</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
            @else
                <div class="empty-state">
                    <i class="fas fa-briefcase"></i>
                    <h4>No experience yet</h4>
                    <p>Add your first work experience using the form above.</p>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleEndDate() {
            const currentJobCheckbox = document.getElementById('current_job');
            const endDateInput = document.getElementById('end_date');
            
            if (currentJobCheckbox.checked) {
                endDateInput.value = '';
                endDateInput.disabled = true;
            } else {
                endDateInput.disabled = false;
            }
        }

        function toggleEditEndDate(experienceId) {
            const currentJobCheckbox = document.getElementById('edit_current_job' + experienceId);
            const endDateInput = document.getElementById('edit_end_date' + experienceId);
            
            if (currentJobCheckbox.checked) {
                endDateInput.value = '';
                endDateInput.disabled = true;
            } else {
                endDateInput.disabled = false;
            }
        }

        function addSkill() {
            const newSkillInput = document.getElementById('newSkill');
            const skill = newSkillInput.value.trim();
            
            if (skill) {
                const skillsDisplay = document.getElementById('skillsDisplay');
                
                // Remove "No skills added yet" message
                if (skillsDisplay.querySelector('.text-muted')) {
                    skillsDisplay.innerHTML = '';
                }
                
                // Create skill tag
                const skillTag = document.createElement('span');
                skillTag.className = 'skill-tag';
                skillTag.innerHTML = `
                    ${skill}
                    <input type="hidden" name="skills[]" value="${skill}">
                    <i class="fas fa-times ms-1" onclick="removeSkill(this)"></i>
                `;
                
                skillsDisplay.appendChild(skillTag);
                newSkillInput.value = '';
            }
        }

        function removeSkill(element) {
            element.parentElement.remove();
            
            const skillsDisplay = document.getElementById('skillsDisplay');
            if (skillsDisplay.children.length === 0) {
                skillsDisplay.innerHTML = '<span class="text-muted">No skills added yet</span>';
            }
        }

        function addEditSkill(experienceId) {
            const newSkillInput = document.getElementById('edit_newSkill' + experienceId);
            const skill = newSkillInput.value.trim();
            
            if (skill) {
                const skillsDisplay = document.getElementById('edit_skillsDisplay' + experienceId);
                
                // Remove "No skills added yet" message if present
                if (skillsDisplay.querySelector('.text-muted')) {
                    skillsDisplay.innerHTML = '';
                }
                
                // Create skill tag
                const skillTag = document.createElement('span');
                skillTag.className = 'skill-tag';
                skillTag.innerHTML = `
                    ${skill}
                    <input type="hidden" name="skills[]" value="${skill}">
                    <i class="fas fa-times ms-1" onclick="removeEditSkill(this, '${experienceId}')"></i>
                `;
                
                skillsDisplay.appendChild(skillTag);
                newSkillInput.value = '';
            }
        }

        function removeEditSkill(element, experienceId) {
            element.parentElement.remove();
            
            const skillsDisplay = document.getElementById('edit_skillsDisplay' + experienceId);
            if (skillsDisplay.children.length === 0) {
                skillsDisplay.innerHTML = '<span class="text-muted">No skills added yet</span>';
            }
        }

        // Allow pressing Enter to add skills
        document.getElementById('newSkill')?.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addSkill();
            }
        });

        // Initialize on page load
        document.addEventListener('DOMContentLoaded', function() {
            toggleEndDate();
            
            // Initialize edit modals
            @foreach($experiences as $experience)
                toggleEditEndDate('{{ $experience->id }}');
            @endforeach
        });
    </script>

@endsection