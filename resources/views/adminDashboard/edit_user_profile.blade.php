@extends('dashboardLayouts.base')

@section('content')
<div class="container-fluid">
    <div class="card card-primary shadow-sm mt-3">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-user-edit mr-2"></i> Edit Student Profile: {{ $user->name }}</h3>
            <div class="card-tools">
                <a href="{{ route('admin.user.profile', $user->id) }}" class="btn btn-default btn-sm">
                    <i class="fas fa-times mr-1"></i> Cancel
                </a>
            </div>
        </div>

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible m-3">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-ban"></i> Error!</h5>
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.user.profile.update', $user->id) }}">
            @csrf
            <div class="card-body">
                <h5 class="text-primary border-bottom pb-2 mb-3"><i class="fas fa-info-circle mr-1"></i> Basic Details</h5>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="name">Full Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="email">Email <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $user->email) }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="phone">Phone</label>
                        <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $user->phone) }}">
                        @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="gender">Gender <span class="text-danger">*</span></label>
                        <select name="gender" id="gender" class="form-control @error('gender') is-invalid @enderror" required>
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $profile->gender) == 'male' ? 'selected' : '' }}>Male</option>
                            <option value="female" {{ old('gender', $profile->gender) == 'female' ? 'selected' : '' }}>Female</option>
                            <option value="other" {{ old('gender', $profile->gender) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('gender')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="date_of_birth">Date of Birth <span class="text-danger">*</span></label>
                        <input type="date" name="date_of_birth" id="date_of_birth" class="form-control @error('date_of_birth') is-invalid @enderror" value="{{ old('date_of_birth', $profile->date_of_birth) }}" required>
                        @error('date_of_birth')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="is_reappear">Reappear Status <span class="text-danger">*</span></label>
                        <select name="is_reappear" id="is_reappear" class="form-control @error('is_reappear') is-invalid @enderror" required>
                            <option value="0" {{ old('is_reappear', $user->is_reappear) == '0' ? 'selected' : '' }}>No Reappears</option>
                            <option value="1" {{ old('is_reappear', $user->is_reappear) == '1' ? 'selected' : '' }}>Has Reappears</option>
                        </select>
                        @error('is_reappear')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <h5 class="text-primary border-bottom pb-2 mb-3 mt-4"><i class="fas fa-graduation-cap mr-1"></i> Academic Details</h5>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="roll_number">Roll Number <span class="text-danger">*</span></label>
                        <input type="text" name="roll_number" id="roll_number" class="form-control @error('roll_number') is-invalid @enderror" value="{{ old('roll_number', $profile->roll_number) }}" required>
                        @error('roll_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="passing_year">Passing Year <span class="text-danger">*</span></label>
                        <input type="number" name="passing_year" id="passing_year" class="form-control @error('passing_year') is-invalid @enderror" value="{{ old('passing_year', $profile->passing_year) }}" min="2000" max="2030" required>
                        @error('passing_year')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="cgpa">CGPA <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="cgpa" id="cgpa" class="form-control @error('cgpa') is-invalid @enderror" value="{{ old('cgpa', $profile->cgpa) }}" min="0" max="10" required>
                        @error('cgpa')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="collegeSelect">College <span class="text-danger">*</span></label>
                        <select name="college_id" id="collegeSelect" class="form-control @error('college_id') is-invalid @enderror" required>
                            <option value="">Select College</option>
                            @foreach($colleges as $college)
                                <option value="{{ $college->id }}" {{ old('college_id', $profile->college) == $college->id ? 'selected' : '' }}>{{ $college->name }}</option>
                            @endforeach
                        </select>
                        @error('college_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="courseSelect">Course <span class="text-danger">*</span></label>
                        <select name="course_id" id="courseSelect" class="form-control @error('course_id') is-invalid @enderror" required {{ old('college_id', $profile->college) ? '' : 'disabled' }}>
                            <option value="">Select Course</option>
                            @foreach($courses as $course)
                                <option value="{{ $course->id }}" {{ old('course_id', $profile->course) == $course->id ? 'selected' : '' }}>{{ $course->name }}</option>
                            @endforeach
                        </select>
                        @error('course_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="departmentSelect">Department <span class="text-danger">*</span></label>
                        <select name="department_id" id="departmentSelect" class="form-control @error('department_id') is-invalid @enderror" required {{ old('course_id', $profile->course) ? '' : 'disabled' }}>
                            <option value="">Select Department</option>
                            @foreach($departments as $dept)
                                <option value="{{ $dept->id }}" {{ old('department_id', $profile->department) == $dept->id ? 'selected' : '' }}>{{ $dept->name }}</option>
                            @endforeach
                        </select>
                        @error('department_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <h5 class="text-primary border-bottom pb-2 mb-3 mt-4"><i class="fas fa-map-marker-alt mr-1"></i> Address & Bio</h5>
                <div class="row">
                    <div class="col-md-4 form-group">
                        <label for="city">City</label>
                        <input type="text" name="city" id="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city', $user->city) }}">
                        @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="state">State</label>
                        <input type="text" name="state" id="state" class="form-control @error('state') is-invalid @enderror" value="{{ old('state', $user->state) }}">
                        @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="col-md-4 form-group">
                        <label for="country">Country</label>
                        <input type="text" name="country" id="country" class="form-control @error('country') is-invalid @enderror" value="{{ old('country', $user->country) }}">
                        @error('country')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label for="bio">Biography / Summary</label>
                    <textarea name="bio" id="bio" rows="4" class="form-control @error('bio') is-invalid @enderror" placeholder="Write a short summary about the student...">{{ old('bio', $profile->bio) }}</textarea>
                    @error('bio')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="card-footer bg-light d-flex justify-content-between">
                <a href="{{ route('admin.user.profile', $user->id) }}" class="btn btn-default">
                    <i class="fas fa-arrow-left mr-1"></i> Cancel
                </a>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save mr-1"></i> Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const collegeSelect = document.getElementById("collegeSelect");
    const courseSelect = document.getElementById("courseSelect");
    const departmentSelect = document.getElementById("departmentSelect");

    // Cascading dropdown for courses
    collegeSelect.addEventListener("change", function () {
        const collegeId = this.value;
        courseSelect.innerHTML = '<option value="">Select Course</option>';
        courseSelect.disabled = true;
        departmentSelect.innerHTML = '<option value="">Select Department</option>';
        departmentSelect.disabled = true;

        if (!collegeId) return;

        fetch('{{ route("fetch.courses") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ college_id: collegeId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                data.forEach(course => {
                    const opt = document.createElement("option");
                    opt.value = course.id;
                    opt.textContent = course.name;
                    courseSelect.appendChild(opt);
                });
                courseSelect.disabled = false;
            }
        })
        .catch(err => console.error("Error loading courses:", err));
    });

    // Cascading dropdown for departments
    courseSelect.addEventListener("change", function () {
        const courseId = this.value;
        departmentSelect.innerHTML = '<option value="">Select Department</option>';
        departmentSelect.disabled = true;

        if (!courseId) return;

        fetch('{{ route("fetch.departments") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ course_ids: [courseId] })
        })
        .then(response => response.json())
        .then(data => {
            if (data.length > 0) {
                data.forEach(dept => {
                    const opt = document.createElement("option");
                    opt.value = dept.id;
                    opt.textContent = dept.name;
                    departmentSelect.appendChild(opt);
                });
                departmentSelect.disabled = false;
            }
        })
        .catch(err => console.error("Error loading departments:", err));
    });
});
</script>
@endsection
