@extends('layouts.base')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        /* Your existing CSS styles remain the same */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        body {
            background-color: #f5f7fa;
            color: #333;
            line-height: 1.6;
        }

        .profile-header {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            color: white;
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            border: 3px solid rgba(255, 255, 255, 0.3);
        }

        .section {
            padding: 25px;
            border-bottom: 1px solid #f0f0f0;
            transition: all 0.2s ease;
        }

        .section:last-child {
            border-bottom: none;
        }

        .section:hover {
            background-color: #fafbfc;
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 12px;
            font-size: 18px;
            font-weight: 600;
            color: #2c3e50;
        }

        .section-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
        }

        .section-action {
            color: #6a11cb;
            font-weight: 600;
            font-size: 14px;
            padding: 8px 16px;
            border-radius: 8px;
            background: rgba(106, 17, 203, 0.1);
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .section-action:hover {
            background: rgba(106, 17, 203, 0.2);
        }

        .section-content {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        }

        .info-label {
            font-size: 13px;
            color: #7f8c8d;
        }

        .info-value {
            font-size: 15px;
            color: #2c3e50;
            font-weight: 500;
        }

        /* Color coding for sections */
        .bio .section-icon {
            background: rgba(52, 152, 219, 0.15);
            color: #3498db;
        }

        .academic .section-icon {
            background: rgba(46, 204, 113, 0.15);
            color: #2ecc71;
        }

        .address .section-icon {
            background: rgba(155, 89, 182, 0.15);
            color: #9b59b6;
        }

        /* Modal Styling */
        .modal {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }

        .modal-content {
            background: #fff;
            padding: 25px;
            border-radius: 10px;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .modal-header h2 {
            font-size: 20px;
            margin: 0;
        }

        .close-btn {
            cursor: pointer;
            font-size: 24px;
            color: #555;
            background: none;
            border: none;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            font-weight: 600;
            display: block;
            margin-bottom: 5px;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px 12px;
            border-radius: 6px;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #6a11cb;
            box-shadow: 0 0 0 2px rgba(106, 17, 203, 0.1);
        }

        .save-btn {
            background: #6a11cb;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: background 0.2s ease;
        }

        .save-btn:hover {
            background: #2575fc;
        }

        .alert {
            padding: 10px 15px;
            border-radius: 6px;
            margin-bottom: 15px;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .not-set {
            color: #95a5a6;
            font-style: italic;
        }
    </style>

    <!-- ===================== ERROR/SUCCESS MESSAGES ===================== -->
    @if($errors->any())
        <div class="alert alert-error">
            <strong>Please fix the following errors:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    <!-- ===================== PROFILE SECTIONS ===================== -->

    <div class="section bio">
        <div class="section-header">
            <div class="section-title">
                <div class="section-icon">
                    <i class="fas fa-user-edit"></i>
                </div>
                <span>Bio</span>
            </div>
            <div class="section-action" data-modal="bioModal">Edit</div>
        </div>
        <div class="section-content">
            <div class="info-item">
                <div class="info-label">Bio</div>
                <div class="info-value {{ empty($profile->bio) ? 'not-set' : '' }}">
                    {{ $profile->bio ?? 'No bio added yet.' }}
                </div>
            </div>
        </div>
    </div>

    <div class="section academic">
        <div class="section-header">
            <div class="section-title">
                <div class="section-icon">
                    <i class="fas fa-graduation-cap"></i>
                </div>
                <span>Academic Profile</span>
            </div>
            <div class="section-action" data-modal="academicModal">Edit</div>
        </div>
        <div class="section-content">
            <div class="info-item">
                <div class="info-label">Gender</div>
                <div class="info-value {{ empty($profile->gender) ? 'not-set' : '' }}">
                    {{ $profile->gender ? ucfirst($profile->gender) : 'Not set' }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Date of Birth</div>
                <div class="info-value {{ empty($profile->date_of_birth) ? 'not-set' : '' }}">
                    {{ $profile->date_of_birth ? \Carbon\Carbon::parse($profile->date_of_birth)->format('M d, Y') : 'Not set' }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Roll Number</div>
                <div class="info-value {{ empty($profile->roll_number) ? 'not-set' : '' }}">
                    {{ $profile->roll_number ?? 'Not set' }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">College</div>
                <div class="info-value {{ empty($profile->college) ? 'not-set' : '' }}">
                    {{ $colleges->where('id', $profile->college)->first()->name ?? 'Not set' }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Course</div>
                <div class="info-value {{ empty($profile->course) ? 'not-set' : '' }}">
                    {{ $courses->where('id', $profile->course)->first()->name ?? 'Not set' }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Department</div>
                <div class="info-value {{ empty($profile->department) ? 'not-set' : '' }}">
                    {{ $departments->where('id', $profile->department)->first()->name ?? 'Not set' }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Passing Year</div>
                <div class="info-value {{ empty($profile->passing_year) ? 'not-set' : '' }}">
                    {{ $profile->passing_year ?? 'Not set' }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">CGPA</div>
                <div class="info-value {{ empty($profile->cgpa) ? 'not-set' : '' }}">
                    {{ $profile->cgpa ?? 'Not set' }}
                </div>
            </div>
            
           <div class="info-item">
                <div class="info-label">Reappear</div>
                <div class="info-value {{ $user->is_reappear === null ? 'not-set' : '' }}">
                    {{ $user->is_reappear === null ? 'Not set' : ($user->is_reappear == 1 ? 'Yes' : 'No') }}
                </div>
            </div>
        </div>
    </div>

    <div class="section address mb-5">
        <div class="section-header">
            <div class="section-title">
                <div class="section-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <span>Address</span>
            </div>
            <div class="section-action" data-modal="addressModal">Edit</div>
        </div>
        <div class="section-content">
            <div class="info-item">
                <div class="info-label">City</div>
                <div class="info-value {{ empty($user->city) ? 'not-set' : '' }}">
                    {{ $user->city ?? 'Not set' }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">State</div>
                <div class="info-value {{ empty($user->state) ? 'not-set' : '' }}">
                    {{ $user->state ?? 'Not set' }}
                </div>
            </div>
            <div class="info-item">
                <div class="info-label">Country</div>
                <div class="info-value {{ empty($user->country) ? 'not-set' : '' }}">
                    {{ $user->country ?? 'Not set' }}
                </div>
            </div>
        </div>
    </div>

    <!-- ===================== MODALS ===================== -->

    <!-- Academic Edit Modal -->
    <div class="modal" id="academicModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Academic Profile</h2>
                <button type="button" class="close-btn" data-close>&times;</button>
            </div>

            <form method="POST" action="{{ route('profile.updateAcademic') }}">
                @csrf

                <div class="form-group">
                    <label>Gender <span style="color:red;">*</span></label>
                    <select name="gender" required>
                          <option value="" selected disabled>Select Gender</option>
                        <option value="male" {{ old('gender', $profile->gender ?? '') == 'male' ? 'selected' : '' }}>Male</option>
                        <option value="female" {{ old('gender', $profile->gender ?? '') == 'female' ? 'selected' : '' }}>Female</option>
                        <option value="other" {{ old('gender', $profile->gender ?? '') == 'other' ? 'selected' : '' }}>Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Date of Birth <span style="color:red;">*</span></label>
                    <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $profile->date_of_birth ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label>Roll Number <span style="color:red;">*</span></label>
                    <input type="text" name="roll_number" placeholder="Enter roll number" value="{{ old('roll_number', $profile->roll_number ?? '') }}" required>
                </div>

                <div class="form-group">
                    <label>College <span style="color:red;">*</span></label>
                    <select name="college_id" id="collegeSelect" required>
                        <option value="">Select College</option>
                        @foreach ($colleges as $college)
                            <option value="{{ $college->id }}" {{ old('college_id', $profile->college ?? '') == $college->id ? 'selected' : '' }}>
                                {{ $college->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Course <span style="color:red;">*</span></label>
                    <select name="course_id" id="courseSelect" required>
                        <option value="">Select Course</option>
                        @foreach ($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id', $profile->course ?? '') == $course->id ? 'selected' : '' }}>
                                {{ $course->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Department <span style="color:red;">*</span></label>
                    <select name="department_id" id="departmentSelect" required>
                        <option value="">Select Department</option>
                        @foreach ($departments as $department)
                            <option value="{{ $department->id }}" {{ old('department_id', $profile->department ?? '') == $department->id ? 'selected' : '' }}>
                                {{ $department->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Passing Year <span style="color:red;">*</span></label>
                    <input type="number" name="passing_year" placeholder="e.g. 2026" value="{{ old('passing_year', $profile->passing_year ?? '') }}" min="2000" max="2030" required>
                </div>

                <div class="form-group">
                    <label>CGPA <span style="color:red;">*</span></label>
                    <input type="number" name="cgpa" placeholder="8.00" step="0.01" min="0" max="10" value="{{ old('cgpa', $profile->cgpa ?? '') }}" required>
                </div>
                
                <div class="form-group">
                    <label>Reappear Status <span style="color:red;">*</span></label>
                    <select name="is_reappear" required>
                        <option value="" disabled {{ old('is_reappear', $user->is_reappear ?? '') === '' ? 'selected' : '' }}>Select</option>
                        <option value="0" {{ old('is_reappear', $user->is_reappear ?? '') == '0' ? 'selected' : '' }}>No</option>
                        <option value="1" {{ old('is_reappear', $user->is_reappear ?? '') == '1' ? 'selected' : '' }}>Yes</option>
                    </select>
                </div>
                
                <button type="submit" class="save-btn">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Bio Edit Modal -->
    <div class="modal" id="bioModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Bio</h2>
                <button type="button" class="close-btn" data-close>&times;</button>
            </div>

            <form method="POST" action="{{ route('profile.updateBio') }}">
                @csrf

                <div class="form-group">
                    <label>Bio</label>
                    <textarea name="bio" placeholder="Enter your bio" rows="4">{{ old('bio', $profile->bio ?? '') }}</textarea>
                </div>
                <button type="submit" class="save-btn">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- Address Edit Modal -->
    <div class="modal" id="addressModal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Edit Address</h2>
                <button type="button" class="close-btn" data-close>&times;</button>
            </div>

            <form method="POST" action="{{ route('profile.updateAddress') }}">
                @csrf

                <div class="form-group">
                    <label>City</label>
                    <input type="text" name="city" placeholder="Enter city" value="{{ old('city', $user->city ?? '') }}">
                </div>

                <div class="form-group">
                    <label>State</label>
                    <input type="text" name="state" placeholder="Enter state" value="{{ old('state', $user->state ?? '') }}">
                </div>

                <div class="form-group">
                    <label>Country</label>
                    <input type="text" name="country" placeholder="Enter country" value="{{ old('country', $user->country ?? '') }}">
                </div>

                <button type="submit" class="save-btn">Save Changes</button>
            </form>
        </div>
    </div>

    <!-- ===================== SCRIPTS ===================== -->
    <script>
        // Modal functionality
        const editBtns = document.querySelectorAll('.section-action');
        const modals = document.querySelectorAll('.modal');
        const closeBtns = document.querySelectorAll('[data-close]');

        editBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                const modalId = btn.getAttribute('data-modal');
                document.getElementById(modalId).style.display = 'flex';
            });
        });

        closeBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                btn.closest('.modal').style.display = 'none';
            });
        });

        window.addEventListener('click', (e) => {
            if (e.target.classList.contains('modal')) {
                e.target.style.display = 'none';
            }
        });

        // AJAX for cascading dropdowns
        document.getElementById('collegeSelect')?.addEventListener('change', function() {
            let collegeId = this.value;
            let courseSelect = document.getElementById('courseSelect');
            let departmentSelect = document.getElementById('departmentSelect');

            if (!collegeId) {
                courseSelect.innerHTML = '<option value="">Select Course</option>';
                courseSelect.disabled = true;
                departmentSelect.innerHTML = '<option value="">Select Department</option>';
                departmentSelect.disabled = true;
                return;
            }

            courseSelect.innerHTML = '<option value="">Loading...</option>';
            courseSelect.disabled = true;
            departmentSelect.innerHTML = '<option value="">Select Department</option>';
            departmentSelect.disabled = true;

            fetch(`{{ route('userProfileEditShow') }}?college_id=${collegeId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    courseSelect.innerHTML = '<option value="">Select Course</option>';
                    if (data.courses && data.courses.length > 0) {
                        data.courses.forEach(course => {
                            let selected = '';
                            if (course.id == '{{ old("course_id", $profile->course ?? "") }}') {
                                selected = 'selected';
                            }
                            courseSelect.innerHTML += `<option value="${course.id}" ${selected}>${course.name}</option>`;
                        });
                        courseSelect.disabled = false;
                        
                        // If old value exists for course, trigger change to load departments
                        const oldCourseId = '{{ old("course_id", $profile->course ?? "") }}';
                        if (oldCourseId && courseSelect.querySelector(`option[value="${oldCourseId}"]`)) {
                            courseSelect.value = oldCourseId;
                            courseSelect.dispatchEvent(new Event('change'));
                        }
                    } else {
                        courseSelect.innerHTML = '<option value="">No courses available</option>';
                        courseSelect.disabled = true;
                    }
                })
                .catch(err => {
                    console.error('Error fetching courses:', err);
                    courseSelect.innerHTML = '<option value="">Error loading courses</option>';
                    courseSelect.disabled = false;
                });
        });

        document.getElementById('courseSelect')?.addEventListener('change', function() {
            let courseId = this.value;
            let collegeId = document.getElementById('collegeSelect').value;
            let departmentSelect = document.getElementById('departmentSelect');

            if (!courseId) {
                departmentSelect.innerHTML = '<option value="">Select Department</option>';
                departmentSelect.disabled = true;
                return;
            }

            departmentSelect.innerHTML = '<option value="">Loading...</option>';
            departmentSelect.disabled = true;

            fetch(`{{ route('userProfileEditShow') }}?college_id=${collegeId}&course_id=${courseId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(res => res.json())
                .then(data => {
                    departmentSelect.innerHTML = '<option value="">Select Department</option>';
                    if (data.departments && data.departments.length > 0) {
                        data.departments.forEach(dept => {
                            let selected = '';
                            if (dept.id == '{{ old("department_id", $profile->department ?? "") }}') {
                                selected = 'selected';
                            }
                            departmentSelect.innerHTML += `<option value="${dept.id}" ${selected}>${dept.name}</option>`;
                        });
                        departmentSelect.disabled = false;
                    } else {
                        departmentSelect.innerHTML = '<option value="">No departments available</option>';
                        departmentSelect.disabled = false;
                    }
                })
                .catch(err => {
                    console.error('Error fetching departments:', err);
                    departmentSelect.innerHTML = '<option value="">Error loading departments</option>';
                    departmentSelect.disabled = false;
                });
        });

        // Initialize dropdowns on page load
        document.addEventListener('DOMContentLoaded', function() {
            const collegeSelect = document.getElementById('collegeSelect');
            const courseSelect = document.getElementById('courseSelect');
            const departmentSelect = document.getElementById('departmentSelect');
            
            // Check if there are validation errors and old values exist
            const hasValidationErrors = document.querySelector('.alert-error') !== null;
            
            if (hasValidationErrors) {
                // If validation errors, use old() values
                const oldCollegeId = '{{ old("college_id", $profile->college ?? "") }}';
                const oldCourseId = '{{ old("course_id", $profile->course ?? "") }}';
                const oldDepartmentId = '{{ old("department_id", $profile->department ?? "") }}';
                
                if (oldCollegeId) {
                    collegeSelect.value = oldCollegeId;
                    collegeSelect.dispatchEvent(new Event('change'));
                    
                    // Wait for courses to load, then set course
                    setTimeout(() => {
                        if (oldCourseId && courseSelect) {
                            courseSelect.value = oldCourseId;
                            courseSelect.dispatchEvent(new Event('change'));
                            
                            // Wait for departments to load, then set department
                            setTimeout(() => {
                                if (oldDepartmentId && departmentSelect) {
                                    departmentSelect.value = oldDepartmentId;
                                }
                            }, 300);
                        }
                    }, 300);
                }
            } else {
                // If no validation errors, use current profile values
                if (collegeSelect && collegeSelect.value) {
                    collegeSelect.dispatchEvent(new Event('change'));
                }
            }
        });

        // Close modal on Escape key
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                modals.forEach(modal => {
                    modal.style.display = 'none';
                });
            }
        });
    </script>
@endsection