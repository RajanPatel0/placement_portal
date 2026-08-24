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
        
        .navbar {
            background-color: var(--secondary-color);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        
        .navbar-brand {
            font-weight: 700;
            color: white !important;
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
        
        .card-header {
            background-color: white;
            border-bottom: 1px solid #eaeaea;
            font-weight: 600;
            padding: 15px 20px;
            border-radius: 10px 10px 0 0 !important;
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
        
        .btn-success {
            background-color: var(--success-color);
            border: none;
        }
        
        .btn-danger {
            background-color: var(--danger-color);
            border: none;
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
        
        .project-card {
            border-left: 4px solid var(--primary-color);
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
        
        .alert-success {
            background-color: #d4edda;
            color: #155724;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
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
        
        .text-danger {
            font-size: 0.875rem;
        }
        
        @media (max-width: 768px) {
            .skill-input-row {
                flex-direction: column;
            }
        }
    </style>

 
    <div class="container my-5 pb-2">
      

        <!-- Page Title -->
        <h1 class="page-title">My Projects</h1>

        <!-- Add Project Form -->
       
        <!-- Add Project Form -->
        <div class="form-section">
            <h4 class="mb-4"><i class="fas fa-plus-circle me-2"></i>Add New Project</h4>
            <form method="POST" action="{{ route('projects.add') }}" id="addProjectForm">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="tittle" class="form-label">Project Title *</label>
                        <input type="text" class="form-control @error('tittle') is-invalid @enderror" 
                               id="tittle" name="tittle" value="{{ old('tittle', '') }}" required
                               placeholder="Enter project title">
                        @error('tittle')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="project_url" class="form-label">Project URL *</label>
                        <input type="url" class="form-control @error('project_url') is-invalid @enderror" 
                               id="project_url" name="project_url" value="{{ old('project_url', '') }}" required
                               placeholder="https://example.com">
                        @error('project_url')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description *</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" 
                              id="description" name="description" rows="3" required
                              placeholder="Describe your project... (100 Char.. Max)">{{ old('description', '') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="mb-3">
                    <label class="form-label">Skills *</label>
                    <div class="skills-input-group">
                        <div class="skill-input-row">
                            <input type="text" class="form-control" id="newSkill" placeholder="e.g. JavaScript (Add one at a time then Click on Add Skill)">
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
                                <span class="text-muted" id="noSkillsText">No skills added yet</span>
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
                    <i class="fas fa-plus me-1"></i> Add Project
                </button>
            </form>
        </div>

        <!-- Projects List -->
        <div class="form-section">
            <h4 class="mb-4"><i class="fas fa-list me-2"></i>My Projects</h4>
            
            @if($projects->count() > 0)
                <div class="projects-list">
                    @foreach($projects as $project)
                        <div class="card project-card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-start">
                                    <div class="flex-grow-1">
                                        <h5 class="card-title">{{ $project->tittle }}</h5>
                                        <p class="card-text">{{ $project->description }}</p>
                                        <div class="mb-2">
                                            @php
                                                $skills = json_decode($project->skills, true);
                                            @endphp
                                            @foreach($skills as $skill)
                                                <span class="skill-tag">{{ $skill }}</span>
                                            @endforeach
                                        </div>
                                        <a href="{{ $project->project_url }}" target="_blank" class="card-link">
                                            <i class="fas fa-external-link-alt me-1"></i>View Project
                                        </a>
                                    </div>
                                    <div class="project-actions">
                                        <!-- Edit Form -->
                                        <form method="POST" action="{{ route('projects.edit', $project->id) }}" class="d-inline">
                                            @csrf
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    data-bs-toggle="modal" data-bs-target="#editProjectModal{{ $project->id }}">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                        </form>
                                        
                                        <!-- Delete Form -->
                                        <form method="POST" action="{{ route('projects.delete', $project->id) }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                    onclick="return confirm('Are you sure you want to delete this project?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                                @php
                                    $createdAt = \Carbon\Carbon::parse($project->created_at);
                                    $updatedAt = \Carbon\Carbon::parse($project->updated_at);
                                @endphp

                                <div class="text-muted small mt-2">
                                    <i class="far fa-clock me-1"></i>Created: {{ $createdAt->format('M j, Y') }}
                                    @if($updatedAt->ne($createdAt))
                                        | Updated: {{ $updatedAt->format('M j, Y') }}
                                    @endif
                                </div>

                            </div>
                        </div>

                        <!-- Edit Project Modal -->
                        <div class="modal fade" id="editProjectModal{{ $project->id }}" tabindex="-1">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Project</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <form method="POST" action="{{ route('projects.edit', $project->id) }}">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label for="edit_tittle{{ $project->id }}" class="form-label">Project Title *</label>
                                                    <input type="text" class="form-control" 
                                                           id="edit_tittle{{ $project->id }}" name="tittle" 
                                                           value="{{ $project->tittle }}" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="edit_project_url{{ $project->id }}" class="form-label">Project URL *</label>
                                                    <input type="url" class="form-control" 
                                                           id="edit_project_url{{ $project->id }}" name="project_url" 
                                                           value="{{ $project->project_url }}" required>
                                                </div>
                                            </div>
                                            <div class="mb-3">
                                                <label for="edit_description{{ $project->id }}" class="form-label">Description *</label>
                                                <textarea class="form-control" id="edit_description{{ $project->id }}" 
                                                          name="description" rows="3" required>{{ $project->description }}</textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label class="form-label">Skills *</label>
                                                <div class="skills-input-group">
                                                    <div class="skill-input-row">
                                                        <input type="text" class="form-control" 
                                                               id="edit_newSkill{{ $project->id }}" placeholder="Enter a skill">
                                                        <button type="button" class="btn btn-outline-primary add-skill-btn" 
                                                                onclick="addEditSkill('{{ $project->id }}')">
                                                            <i class="fas fa-plus me-1"></i> Add Skill
                                                        </button>
                                                    </div>
                                                    <div class="skills-display" id="edit_skillsDisplay{{ $project->id }}">
                                                        @php
                                                            $skills = json_decode($project->skills, true);
                                                        @endphp
                                                        @foreach($skills as $skill)
                                                            <span class="skill-tag">
                                                                {{ $skill }}
                                                                <input type="hidden" name="skills[]" value="{{ $skill }}">
                                                                <i class="fas fa-times ms-1" onclick="removeEditSkill(this, '{{ $project->id }}')"></i>
                                                            </span>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                            <button type="submit" class="btn btn-primary">Update Project</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="empty-state">
                    <i class="fas fa-folder-open"></i>
                    <h4>No projects yet</h4>
                    <p>Add your first project using the form above.</p>
                </div>
            @endif
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Minimal JavaScript for skills management (no database operations)
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

        function addEditSkill(projectId) {
            const newSkillInput = document.getElementById('edit_newSkill' + projectId);
            const skill = newSkillInput.value.trim();
            
            if (skill) {
                const skillsDisplay = document.getElementById('edit_skillsDisplay' + projectId);
                
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
                    <i class="fas fa-times ms-1" onclick="removeEditSkill(this, '${projectId}')"></i>
                `;
                
                skillsDisplay.appendChild(skillTag);
                newSkillInput.value = '';
            }
        }

        function removeEditSkill(element, projectId) {
            element.parentElement.remove();
            
            const skillsDisplay = document.getElementById('edit_skillsDisplay' + projectId);
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
    </script>
@endsection