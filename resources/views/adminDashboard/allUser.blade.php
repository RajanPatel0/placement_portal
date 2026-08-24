@extends('dashboardLayouts.base')

@section('title', 'All Users')

@section('content')
<!-- Add SheetJS library -->
<script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>

<div class="container">
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
            right: .5px;
            background: transparent;
            border: none;
            font-size: 1.2rem;
            font-weight: bold;
            color: #fff;
            cursor: pointer;
        }
        .filter-count-badge {
            font-size: 0.8rem;
            padding: 2px 6px;
            border-radius: 10px;
            margin-left: 5px;
            font-weight: normal;
        }
        .badge-male {
            background-color: #d1ecf1;
            color: #0c5460;
        }
        .badge-female {
            background-color: #f8d7da;
            color: #721c24;
        }
        .badge-other {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-default {
            background-color: #e2e3e5;
            color: #383d41;
        }
        .search-container {
            margin-bottom: 20px;
        }
        .filter-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
            font-size: 0.82rem;
            color: #495057;
        }
        .count-display {
            display: block;
            margin-top: 5px;
            font-size: 0.85rem;
            color: #6c757d;
        }
        .count-badge {
            font-size: 0.75rem;
            padding: 1px 5px;
            border-radius: 8px;
            background-color: #e9ecef;
            color: #495057;
        }
        .filter-row {
            margin-bottom: 10px;
        }
        .filter-hidden {
            display: none !important;
        }
        .download-button-container {
            margin: 20px 0;
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }
        .download-button-container .btn {
            display: flex;
            align-items: center;
            gap: 5px;
        }
    </style>
    <script>
        // Auto-hide the notification after 5 seconds
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
    </script>

    <div style="padding-top: 26px; ">
    <form action="{{ route('students.import') }}" method="POST" enctype="multipart/form-data"
            style="padding: 7px; background: #f8f9fa; border-radius: 16px; max-width: 320px; margin: auto; box-shadow: 0 4px 12px rgba(0,0,0,0.1); ">
            @csrf
            <div style="margin-bottom: 20px;">
                <label for="csv_file"
                    style="display: block; font-weight: 600; margin-bottom: 12px; color: #1a1a1a; font-size: 15px;">📁 Upload
                    CSV</label>
                <input type="file" id="csv_file" name="csv_file" accept=".csv" required
                    style="width: 100%; padding: 4px; border: 2px dashed #007bff; border-radius: 12px; background: white; font-size: 13px;">
                <small style="display: block; margin-top: 5px; color: #666; font-size: 12px;">
                    Upload CSV file containing student data
                </small>
            </div>
            <button type="submit"
                style="background: #007bff; color: white; border: none; padding: 6px; border-radius: 12px; cursor: pointer; width: 100%; font-size: 13px; font-weight: 600; box-shadow: 0 2px 8px rgba(0,123,255,0.3);">
                Import Students
            </button>
        </form>
    </div>
    
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h2>All Users (Total: <span id="totalCount">{{ count($users) }}</span>)</h2>
            <h5>Showing: <span id="filteredCount">{{ count($users) }}</span> users</h5>
        </div>
        
        <!-- Single Download Button -->
        <div class="download-button-container">
            <button id="downloadExcelBtn" class="btn btn-success">
                <i class="fas fa-download"></i> Download Users (Excel)
            </button>
        </div>
    </div>

    <!-- SEARCH SECTION -->
    <div class="row mb-4 search-container">
        <div class="col-md-12">
            <div class="input-group">
                <span class="input-group-text">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" id="searchInput" class="form-control" placeholder="Search by name, email, roll number, city, state, college, course, department...">
                <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                    Clear
                </button>
            </div>
        </div>
    </div>

    {{-- FILTER SECTION --}}
    <div class="row mb-4 filter-row">
        {{-- Gender Filter --}}
        <div class="col-md">
            <label class="filter-label">Gender</label>
            <select id="filterGender" class="form-control select-sm">
                <option value="">All</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
        </div>

        {{-- College Filter --}}
        <div class="col-md-2">
            <label class="filter-label">College</label>
            <select id="filterCollege" class="form-control select-sm">
                <option value="">All Colleges</option>
                @foreach($colleges as $college)
                    <option value="{{ strtolower($college->name) }}" data-college-id="{{ $college->id }}">
                        {{ $college->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Course Filter --}}
        <div class="col-md-2">
            <label class="filter-label">Course</label>
            <select id="filterCourse" class="form-control select-sm" disabled>
                <option value="">All Courses</option>
                @foreach($courses as $course)
                    <option value="{{ strtolower($course->name) }}" data-college-id="{{ $course->college_id }}" data-course-id="{{ $course->id }}">
                        {{ $course->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Department Filter --}}
        <div class="col-md-2">
            <label class="filter-label">Department</label>
            <select id="filterDepartment" class="form-control select-sm" disabled>
                <option value="">All Departments</option>
                @foreach($departments as $dept)
                    <option value="{{ strtolower($dept->name) }}" data-course-id="{{ $dept->courses_id }}">
                        {{ $dept->name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Year Filter --}}
        <div class="col-md">
            <label class="filter-label">Passing Year</label>
            <select id="filterYear" class="form-control select-sm">
                <option value="">All Years</option>
                @foreach($years as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                @endforeach
            </select>
        </div>

        {{-- Active Status Filter --}}
        <div class="col-md">
            <label class="filter-label">Active Status</label>
            <select id="filterActive" class="form-control select-sm">
                <option value="">All Status</option>
                <option value="1">Active</option>
                <option value="0">Inactive</option>
            </select>
        </div>

        {{-- Verification Status Filter --}}
        <div class="col-md">
            <label class="filter-label">Verification</label>
            <select id="filterVerified" class="form-control select-sm">
                <option value="">All Verification</option>
                <option value="1">Verified</option>
                <option value="0">Unverified</option>
            </select>
        </div>

        {{-- Clear Filters Button --}}
        <div class="col-md d-flex align-items-end">
            <button class="btn btn-outline-danger w-100" id="clearFilters" style="padding: 0.375rem 0.5rem; font-size: 0.85rem;">
                <i class="fas fa-times-circle"></i> Clear Filters
            </button>
        </div>
    </div>

    <div class="table-responsive">
        <table id="usersTable" class="table table-striped table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>S.No</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Gender</th>
                    <th>Roll No</th>
                    <th>Year</th>
                    <th>College</th>
                    <th>Course</th>
                    <th>Department</th>
                    <th>City</th>
                    <th>State</th>
                    <th>Status</th>
                    <th>Verified</th>
                    <th width="80">Actions</th>
                </tr>
            </thead>

            <tbody>
                @foreach($users as $index => $user)
                    <tr data-user-id="{{ $user->id }}" data-is-active="{{ $user->is_active }}" data-is-verified="{{ $user->is_verified }}">
                        <td>{{ $index + 1 }}</td>
                        <td class="searchable-name">{{ $user->name }}</td>
                        <td class="searchable-email">{{ $user->email }}</td>
                        <td class="searchable-phone">{{ $user->phone ?? '-' }}</td>
                        <td class="searchable-gender">{{ $user->gender ?? '-' }}</td>
                        <td class="searchable-roll">{{ $user->roll_number ?? '-' }}</td>
                        <td class="searchable-year">{{ $user->passing_year ?? '-' }}</td>
                        <td class="searchable-college">{{ $user->college_name ?? '-' }}</td>
                        <td class="searchable-course">{{ $user->course_name ?? '-' }}</td>
                        <td class="searchable-department">{{ $user->department_name ?? '-' }}</td>
                        <td class="searchable-city">{{ $user->city ?? '-' }}</td>
                        <td class="searchable-state">{{ $user->state ?? '-' }}</td>
                        <td class="text-center">
                            <form method="POST" action="{{ route('admin.user.toggle-active', $user->id) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-xs btn-{{ $user->is_active ? 'success' : 'danger' }}" title="Click to Toggle Status">
                                    {{ $user->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td class="text-center">
                            <form method="POST" action="{{ route('admin.user.toggle-verified', $user->id) }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-xs btn-{{ $user->is_verified ? 'primary' : 'warning' }}" title="Click to Toggle Verification">
                                    {{ $user->is_verified ? 'Verified' : 'Unverified' }}
                                </button>
                            </form>
                        </td>
                        <td class="text-center">
                            <div class="btn-group">
                                <a href="{{ route('admin.user.profile', $user->id) }}"
                                    class="btn btn-outline-info btn-sm" title="View Profile">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.user.profile.edit', $user->id) }}"
                                    class="btn btn-outline-primary btn-sm ml-1" title="Edit Profile">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.user.profile.delete', $user->id) }}" method="POST" class="d-inline ml-1" onsubmit="return confirm('Are you sure you want to permanently delete this student? All their academic records, resume, and placement applications will be deleted permanently. This action cannot be undone.');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Delete User">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>

{{-- JAVASCRIPT FILTER LOGIC --}}
<script>
document.addEventListener("DOMContentLoaded", function () {
    // Get all elements
    const filterGender = document.getElementById("filterGender");
    const filterCollege = document.getElementById("filterCollege");
    const filterCourse = document.getElementById("filterCourse");
    const filterDepartment = document.getElementById("filterDepartment");
    const filterYear = document.getElementById("filterYear");
    const filterActive = document.getElementById("filterActive");
    const filterVerified = document.getElementById("filterVerified");
    const searchInput = document.getElementById("searchInput");
    const clearSearch = document.getElementById("clearSearch");
    const clearFilters = document.getElementById("clearFilters");
    const downloadExcelBtn = document.getElementById("downloadExcelBtn");
    
    const rows = document.querySelectorAll("#usersTable tbody tr");
    const totalCountSpan = document.getElementById("totalCount");
    const filteredCountSpan = document.getElementById("filteredCount");
    
    // Store all users data with full information
    const usersData = Array.from(rows).map((row, index) => {
        return {
            id: row.getAttribute('data-user-id'),
            isActive: row.getAttribute('data-is-active'),
            isVerified: row.getAttribute('data-is-verified'),
            index: index + 1,
            name: row.querySelector(".searchable-name").innerText,
            email: row.querySelector(".searchable-email").innerText,
            phone: row.querySelector(".searchable-phone").innerText,
            gender: row.querySelector(".searchable-gender").innerText,
            roll: row.querySelector(".searchable-roll").innerText,
            year: row.querySelector(".searchable-year").innerText,
            college: row.querySelector(".searchable-college").innerText,
            course: row.querySelector(".searchable-course").innerText,
            department: row.querySelector(".searchable-department").innerText,
            city: row.querySelector(".searchable-city").innerText,
            state: row.querySelector(".searchable-state").innerText,
            element: row
        };
    });

    // Function to get current filter values
    function getCurrentFilters() {
        return {
            search: searchInput.value,
            gender: filterGender.value,
            college: filterCollege.value,
            course: filterCourse.value,
            department: filterDepartment.value,
            year: filterYear.value,
            active: filterActive.value,
            verified: filterVerified.value
        };
    }

    // Function to check if any filters are active
    function areFiltersActive() {
        const filters = getCurrentFilters();
        return filters.search || filters.gender || filters.college || 
               filters.course || filters.department || filters.year ||
               filters.active || filters.verified;
    }

    // Function to filter courses based on selected college
    function filterCoursesByCollege(selectedCollegeId) {
        const courseSelect = document.getElementById('filterCourse');
        const deptSelect = document.getElementById('filterDepartment');
        
        if (!selectedCollegeId) {
            courseSelect.value = '';
            courseSelect.disabled = true;
            deptSelect.value = '';
            deptSelect.disabled = true;
            
            Array.from(courseSelect.options).forEach(option => {
                if (option.value !== '') {
                    option.hidden = true;
                    option.disabled = true;
                }
            });
            return;
        }
        
        courseSelect.disabled = false;
        
        Array.from(courseSelect.options).forEach(option => {
            if (option.value === '') {
                option.hidden = false;
                option.disabled = false;
            } else {
                const courseCollegeId = option.getAttribute('data-college-id');
                if (courseCollegeId === selectedCollegeId) {
                    option.hidden = false;
                    option.disabled = false;
                } else {
                    option.hidden = true;
                    option.disabled = true;
                }
            }
        });
        
        const selectedOption = courseSelect.options[courseSelect.selectedIndex];
        if (selectedOption && (selectedOption.disabled || selectedOption.hidden)) {
            courseSelect.value = '';
        }
    }

    // Function to filter departments based on selected course
    function filterDepartmentsByCourse(selectedCourseId) {
        const deptSelect = document.getElementById('filterDepartment');
        
        if (!selectedCourseId) {
            deptSelect.value = '';
            deptSelect.disabled = true;
            
            Array.from(deptSelect.options).forEach(option => {
                if (option.value !== '') {
                    option.hidden = true;
                    option.disabled = true;
                }
            });
            return;
        }
        
        deptSelect.disabled = false;
        
        Array.from(deptSelect.options).forEach(option => {
            if (option.value === '') {
                option.hidden = false;
                option.disabled = false;
            } else {
                const deptCourseId = option.getAttribute('data-course-id');
                if (deptCourseId === selectedCourseId) {
                    option.hidden = false;
                    option.disabled = false;
                } else {
                    option.hidden = true;
                    option.disabled = true;
                }
            }
        });
        
        const selectedOption = deptSelect.options[deptSelect.selectedIndex];
        if (selectedOption && (selectedOption.disabled || selectedOption.hidden)) {
            deptSelect.value = '';
        }
    }

    // Function to get currently visible rows
    function getVisibleRows() {
        const searchTerm = searchInput.value.toLowerCase();
        const genderVal = filterGender.value.toLowerCase();
        const collegeVal = filterCollege.value.toLowerCase();
        const courseVal = filterCourse.value.toLowerCase();
        const departmentVal = filterDepartment.value.toLowerCase();
        const yearVal = filterYear.value.toLowerCase();
        const activeVal = filterActive.value;
        const verifiedVal = filterVerified.value;
        
        return usersData.filter(rowData => {
            if (searchTerm) {
                const matchesSearch = 
                    rowData.name.toLowerCase().includes(searchTerm) ||
                    rowData.email.toLowerCase().includes(searchTerm) ||
                    rowData.phone.toLowerCase().includes(searchTerm) ||
                    rowData.roll.toLowerCase().includes(searchTerm) ||
                    rowData.city.toLowerCase().includes(searchTerm) ||
                    rowData.state.toLowerCase().includes(searchTerm) ||
                    rowData.gender.toLowerCase().includes(searchTerm) ||
                    rowData.college.toLowerCase().includes(searchTerm) ||
                    rowData.course.toLowerCase().includes(searchTerm) ||
                    rowData.department.toLowerCase().includes(searchTerm) ||
                    rowData.year.toLowerCase().includes(searchTerm);
                
                if (!matchesSearch) return false;
            }
            
            if (genderVal && rowData.gender.toLowerCase() !== genderVal) return false;
            if (collegeVal && rowData.college.toLowerCase() !== collegeVal) return false;
            if (courseVal && rowData.course.toLowerCase() !== courseVal) return false;
            if (departmentVal && rowData.department.toLowerCase() !== departmentVal) return false;
            if (yearVal && rowData.year.toLowerCase() !== yearVal) return false;
            if (activeVal !== "" && rowData.isActive !== activeVal) return false;
            if (verifiedVal !== "" && rowData.isVerified !== verifiedVal) return false;
            
            return true;
        });
    }

    // Function to download Excel file
    function downloadExcel(data, filename) {
        // Prepare worksheet data
        const wsData = [
            // Headers
            ['S.No', 'Name', 'Email', 'Phone', 'Gender', 'Roll No', 'Passing Year', 
             'College', 'Course', 'Department', 'City', 'State'],
            // Data rows
            ...data.map(user => [
                user.index,
                user.name,
                user.email,
                user.phone,
                user.gender,
                user.roll,
                user.year,
                user.college,
                user.course,
                user.department,
                user.city,
                user.state,
              
            ])
        ];

        // Create worksheet
        const ws = XLSX.utils.aoa_to_sheet(wsData);

        // Set column widths
        const wscols = [
            { wch: 5 },   // S.No
            { wch: 25 },  // Name
            { wch: 30 },  // Email
            { wch: 15 },  // Phone
            { wch: 10 },  // Gender
            { wch: 15 },  // Roll No
            { wch: 12 },  // Passing Year
            { wch: 25 },  // College
            { wch: 20 },  // Course
            { wch: 25 },  // Department
            { wch: 15 },  // City
            { wch: 15 },  // State
            
        ];
        ws['!cols'] = wscols;

        // Create workbook
        const wb = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(wb, ws, "Users");

        // Generate Excel file and download
        XLSX.writeFile(wb, filename);
    }

    // Function to handle download button click
    function handleDownloadExcel() {
        const visibleRows = getVisibleRows();
        
        if (visibleRows.length === 0) {
            alert('No data to download.');
            return;
        }
        
        // Check if filters are active
        const filtersActive = areFiltersActive();
        const currentDate = new Date().toISOString().split('T')[0];
        
        let filename;
        let dataToDownload;
        
        if (filtersActive) {
            // Download filtered data
            const filters = getCurrentFilters();
            let filterText = '';
            
            if (filters.search) filterText += `_Search_${filters.search.substring(0, 20)}`;
            if (filters.gender) filterText += `_Gender_${filters.gender}`;
            if (filters.college) filterText += `_College_${filters.college.substring(0, 20)}`;
            if (filters.course) filterText += `_Course_${filters.course.substring(0, 20)}`;
            if (filters.department) filterText += `_Dept_${filters.department.substring(0, 20)}`;
            if (filters.year) filterText += `_Year_${filters.year}`;
            
            // Limit filename length
            if (filterText.length > 50) {
                filterText = filterText.substring(0, 50) + '...';
            }
            
            filename = `Filtered_Users_${currentDate}${filterText}.xlsx`;
            dataToDownload = visibleRows;
        } else {
            // Download all data
            filename = `All_Users_${currentDate}.xlsx`;
            dataToDownload = usersData;
        }
        
        downloadExcel(dataToDownload, filename);
    }

    // Event listener for college filter
    filterCollege.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const collegeId = selectedOption.getAttribute('data-college-id');
        
        filterCoursesByCollege(collegeId);
        applyAllFilters();
    });

    // Event listener for course filter
    filterCourse.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const courseId = selectedOption.getAttribute('data-course-id');
        
        filterDepartmentsByCourse(courseId);
        applyAllFilters();
    });

    // Event listener for department filter
    filterDepartment.addEventListener('change', function() {
        applyAllFilters();
    });

    // Event listener for download button
    downloadExcelBtn.addEventListener('click', handleDownloadExcel);

    // Initialize counts
    function initializeCounts() {
        const totalCount = rows.length;
        totalCountSpan.textContent = totalCount;
        filteredCountSpan.textContent = totalCount;
        updateAllCounts();
    }

    // Helper function to create unique counts object
    function getUniqueCounts(dataArray, property) {
        const counts = {};
        dataArray.forEach(item => {
            const value = item[property];
            counts[value] = (counts[value] || 0) + 1;
        });
        return counts;
    }

    // Update all filter counts based on currently visible rows
    function updateAllCounts() {
        const visibleRows = getVisibleRows();
        const visibleCount = visibleRows.length;
        filteredCountSpan.textContent = visibleCount;
        
        const genderCounts = getUniqueCounts(visibleRows, 'gender');
        const collegeCounts = getUniqueCounts(visibleRows, 'college');
        const courseCounts = getUniqueCounts(visibleRows, 'course');
        const departmentCounts = getUniqueCounts(visibleRows, 'department');
        const yearCounts = getUniqueCounts(visibleRows, 'year');
        
        document.getElementById('genderCountAll').textContent = visibleCount;
        let genderCountsHTML = '';
        if (genderCounts['male'] > 0) genderCountsHTML += `| Male: <span class="count-badge">${genderCounts['male']}</span> `;
        if (genderCounts['female'] > 0) genderCountsHTML += `| Female: <span class="count-badge">${genderCounts['female']}</span> `;
        if (genderCounts['other'] > 0) genderCountsHTML += `| Other: <span class="count-badge">${genderCounts['other']}</span> `;
        if (genderCounts['-'] > 0) genderCountsHTML += `| Not Set: <span class="count-badge">${genderCounts['-']}</span> `;
        document.getElementById('genderCountsDisplay').innerHTML = genderCountsHTML;
        
        document.getElementById('collegeCountAll').textContent = visibleCount;
        let collegeCountsHTML = '';
        const collegeOptions = Array.from(filterCollege.options).slice(1);
        
        const collegeMap = {};
        collegeOptions.forEach(option => {
            collegeMap[option.value.toLowerCase()] = option.text;
        });
        
        Object.keys(collegeCounts).forEach(collegeKey => {
            if (collegeKey !== '-' && collegeCounts[collegeKey] > 0) {
                const displayName = collegeMap[collegeKey] || collegeKey;
                collegeCountsHTML += `| ${displayName}: <span class="count-badge">${collegeCounts[collegeKey]}</span> `;
            }
        });
        
        document.getElementById('collegeCountsDisplay').innerHTML = collegeCountsHTML;
    }

    // Apply all filters and update UI
    function applyAllFilters() {
        const visibleRowsData = getVisibleRows();
        
        usersData.forEach(rowData => {
            const isVisible = visibleRowsData.includes(rowData);
            rowData.element.style.display = isVisible ? "" : "none";
        });
        
        updateAllCounts();
    }

    // Clear all filters
    function clearAllFilters() {
        filterGender.value = "";
        filterCollege.value = "";
        filterYear.value = "";
        filterActive.value = "";
        filterVerified.value = "";
        searchInput.value = "";
        
        filterCoursesByCollege("");
        filterDepartmentsByCourse("");
        
        applyAllFilters();
        searchInput.focus();
    }

    // Event listeners
    filterGender.addEventListener("change", applyAllFilters);
    filterYear.addEventListener("change", applyAllFilters);
    filterActive.addEventListener("change", applyAllFilters);
    filterVerified.addEventListener("change", applyAllFilters);
    searchInput.addEventListener("input", function() {
        applyAllFilters();
    });
    clearSearch.addEventListener("click", function() {
        searchInput.value = "";
        applyAllFilters();
        searchInput.focus();
    });
    clearFilters.addEventListener("click", clearAllFilters);

    // Initialize on page load
    filterCoursesByCollege(filterCollege.value);
    filterDepartmentsByCourse(filterCourse.value);
    initializeCounts();
});
</script>

@endsection