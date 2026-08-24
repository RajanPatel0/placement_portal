@extends('dashboardLayouts.base')

@section('content')

    <style>
        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            padding: 30px 0;
        }

        .header h1 {
            font-size: 2rem;
            color: #1f2937;
            margin-bottom: 10px;
            font-weight: 600;
        }

        .header p {
            color: #6b7280;
            font-size: 1.1rem;
        }

        .form-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 40px;
        }

        .form-tabs {
            display: flex;
            background: #f8fafc;
            border-bottom: 1px solid #e5e7eb;
            padding: 0 20px;
            flex-wrap: wrap;
        }

        .form-tab {
            padding: 16px 24px;
            cursor: pointer;
            font-weight: 500;
            color: #6b7280;
            border-bottom: 2px solid transparent;
            transition: all 0.2s ease;
            flex: 1;
            min-width: 140px;
            text-align: center;
        }

        .form-tab.active {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
            background: white;
        }

        .tab-content {
            display: none;
            padding: 30px;
        }

        .tab-content.active {
            display: block;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-section {
            background: #f8fafc;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 24px;
            border-left: 4px solid #3b82f6;
        }

        .form-section-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-section-title i {
            color: #3b82f6;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: #374151;
            font-size: 0.9rem;
        }

        label.required::after {
            content: " *";
            color: #ef4444;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 0.95rem;
            transition: all 0.2s ease;
            background: white;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        textarea {
            min-height: 100px;
            resize: vertical;
        }

        .skills-container {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            padding: 8px;
            background: white;
        }

        .skills-tags,
        .year-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-bottom: 8px;
        }

        .skill-tag,
        .year-tag {
            background: #3b82f6;
            color: white;
            padding: 4px 10px;
            border-radius: 16px;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .year-tag {
            background: #10b981;
        }

        .skill-tag-remove,
        .year-tag-remove {
            cursor: pointer;
            font-weight: bold;
            font-size: 0.9rem;
        }

        .skill-tag-remove:hover,
        .year-tag-remove:hover {
            opacity: 0.8;
        }

        .item-list {
            border: 1px solid #d1d5db;
            border-radius: 6px;
            max-height: 200px;
            overflow-y: auto;
            background: white;
        }

        .item {
            padding: 12px 16px;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
            transition: background 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .item:hover {
            background: #f9fafb;
        }

        .item.selected {
            background: #eff6ff;
            border-left: 3px solid #3b82f6;
        }

        .item:last-child {
            border-bottom: none;
        }

        .placeholder {
            text-align: center;
            color: #9ca3af;
            padding: 20px;
            font-style: italic;
        }

        .selected-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 8px;
        }

        .selected-tag {
            background: #e5e7eb;
            padding: 6px 12px;
            border-radius: 16px;
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .remove-tag {
            cursor: pointer;
            font-weight: bold;
            margin-left: 6px;
            padding: 2px 4px;
            border-radius: 2px;
            transition: background-color 0.2s ease;
        }

        .remove-tag:hover {
            background-color: rgba(0, 0, 0, 0.1);
        }

        .btn-group {
            display: flex;
            justify-content: space-between;
            margin-top: 32px;
            padding-top: 24px;
            border-top: 1px solid #e5e7eb;
            gap: 12px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
            justify-content: center;
        }

        .btn-primary {
            background: #3b82f6;
            color: white;
        }

        .btn-primary:hover {
            background: #2563eb;
        }

        .btn-outline {
            background: white;
            color: #374151;
            border: 1px solid #d1d5db;
        }

        .btn-outline:hover {
            background: #f9fafb;
        }

        .info-text {
            font-size: 0.8rem;
            color: #6b7280;
            margin-top: 4px;
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .checkbox-group input {
            width: auto;
        }

        .input-with-icon {
            position: relative;
        }

        .input-with-icon i {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            color: #6b7280;
        }

        .input-with-icon input,
        .input-with-icon select,
        .input-with-icon textarea {
            padding-left: 40px;
        }

        .academic-criteria-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 16px;
            margin-bottom: 20px;
        }

        @media (max-width: 1024px) {
            .academic-criteria-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
                gap: 16px;
            }

            .academic-criteria-grid {
                grid-template-columns: 1fr;
            }

            .form-tabs {
                flex-direction: column;
            }

            .form-tab {
                flex: none;
                text-align: left;
                padding: 12px 16px;
            }

            .tab-content {
                padding: 20px;
            }

            .btn-group {
                flex-direction: column;
            }

            .btn {
                justify-content: center;
            }
        }
    </style>


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
    <div class="container">
        <div class="header">
            <h1>Create Placement Drive</h1>
            <p>Add new placement drive and notify eligible students</p>
        </div>

        <div class="form-container">
            <div class="form-tabs">
                <div class="form-tab active" data-tab="company">Company Details</div>
                <div class="form-tab" data-tab="drive">Drive Information</div>
                <div class="form-tab" data-tab="eligibility">Eligibility Criteria</div>
                <div class="form-tab" data-tab="contact">Contact & Finalize</div>
            </div>

            <form id="placementDriveForm" action="{{ route('admin.placement.drives.store') }}" method="POST">
                @csrf

                <!-- Company Tab -->
                <div class="tab-content active" id="company-tab">
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-building"></i>
                            Company Information
                        </div>
                        <div class="form-grid">
                            <div>
                                <div class="form-group">
                                    <label class="required">Company Name</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-building"></i>
                                        <input type="text" name="company_name" required placeholder="Enter company name"
                                            value="{{ old('company_name') }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="required">Job Title</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-user-tie"></i>
                                        <input type="text" name="job_title" required
                                            placeholder="e.g. Software Engineer, Data Analyst"
                                            value="{{ old('job_title') }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="required">Job Role</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-briefcase"></i>
                                        <input type="text" name="job_role"
                                            placeholder="e.g. Software Engineer, Data Analyst"
                                            value="{{ old('job_role') }}">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="form-group">
                                    <label class="required">Company Website</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-globe"></i>
                                        <input type="url" name="company_website" placeholder="https://example.com"
                                            value="{{ old('company_website') }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="required">Package Offered (LPA)</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-money-bill-wave"></i>
                                        <input type="number" name="package_offered" step="0.01" min="0"
                                            placeholder="e.g. 8.5" value="{{ old('package_offered') }}">
                                    </div>
                                    <p class="info-text">Cost to Company in Lakhs Per Annum</p>
                                </div>

                                <div class="form-group">
                                    <label>Required Skills</label>
                                    <div class="skills-container">
                                        <div class="skills-tags" id="skills-tags"></div>
                                        <input type="text" class="skills-input" id="skills-input"
                                            placeholder="Type a skill and press Enter or comma...">
                                    </div>
                                    <input type="hidden" name="required_skills" id="required_skills"
                                        value="{{ old('required_skills', '[]') }}">
                                    <p class="info-text">Type skills and press Enter or comma to add. Click × to remove.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-section-title" class="required">
                            <i class="fas fa-file-alt"></i>
                            Job Description
                        </div>
                        <div class="form-group">
                            <textarea name="description"
                                placeholder="Describe the job role, responsibilities, requirements, and any other important details...">{{ old('description') }}</textarea>
                        </div>
                    </div>

                    <div class="btn-group">
                        <button type="button" class="btn btn-outline">Cancel</button>
                        <button type="button" class="btn btn-primary btn-next" data-next="drive">Next: Drive
                            Information</button>
                    </div>
                </div>

                <!-- Drive Information Tab -->
                <div class="tab-content" id="drive-tab">
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-calendar-alt"></i>
                            Drive Schedule
                        </div>
                        <div class="form-grid">
                            <div>
                                <div class="form-group">
                                    <label class="required">Drive Date</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-calendar-day"></i>
                                        <input type="date" name="drive_date" required
                                            value="{{ old('drive_date') }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="required">Application Deadline</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-clock"></i>
                                        <input type="date" name="application_deadline"
                                            value="{{ old('application_deadline') }}">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="form-group">
                                    <label class="required">Drive Type</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-location-arrow"></i>
                                        <select name="drive_type" required>
                                            <option value="">Select Drive Type</option>
                                            <option value="on_campus"
                                                {{ old('drive_type') == 'on_campus' ? 'selected' : '' }}>On Campus</option>
                                            <option value="off_campus"
                                                {{ old('drive_type') == 'off_campus' ? 'selected' : '' }}>Off Campus
                                            </option>
                                            <option value="virtual"
                                                {{ old('drive_type') == 'virtual' ? 'selected' : '' }}>Virtual</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="required">Location</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                        <input type="text" name="location" placeholder="e.g. Main Campus, Building A"
                                            value="{{ old('location') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-info-circle"></i>
                            Drive Status & Capacity
                        </div>
                        <div class="form-grid">
                            <div>
                                <div class="form-group">
                                    <label class="required">Status</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-info-circle"></i>
                                        <select name="status" required>
                                            <option value="">Select Status</option>
                                            <option value="upcoming" {{ old('status') == 'upcoming' ? 'selected' : '' }}>
                                                Upcoming</option>
                                            <option value="ongoing" {{ old('status') == 'ongoing' ? 'selected' : '' }}>
                                                Ongoing</option>
                                            <option value="completed"
                                                {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                            <option value="cancelled"
                                                {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="form-group">
                                    <label>Number of Vacancies</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-users"></i>
                                        <input type="number" name="vacancies" min="0" placeholder="e.g. 10"
                                            value="{{ old('vacancies') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="btn-group">
                        <button type="button" class="btn btn-outline btn-prev" data-prev="company">Previous</button>
                        <button type="button" class="btn btn-primary btn-next" data-next="eligibility">Next: Eligibility
                            Criteria</button>
                    </div>
                </div>

                <!-- Eligibility Criteria Tab -->
                <div class="tab-content" id="eligibility-tab">
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-university"></i>
                            Institutional Eligibility
                        </div>
                        <div class="form-grid">
                            <div>
                                <div class="form-group">
                                    <label class="required">Select Colleges</label>
                                    <div id="college-list" class="item-list">
                                        @foreach ($colleges as $college)
                                            <div class="item" data-id="{{ $college->id }}" data-type="college">
                                                <i class="fas fa-university"></i> {{ $college->name }}
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="selected-tags" id="college-tags"></div>
                                    <input type="hidden" name="college_ids" id="college_ids"
                                        value="{{ old('college_ids') }}">
                                </div>
                            </div>

                            <div>
                                <div class="form-group">
                                    <label class="required">Select Courses</label>
                                    <div id="course-list" class="item-list">
                                        <p class="placeholder">Select at least one college first</p>
                                    </div>
                                    <div class="selected-tags" id="course-tags"></div>
                                    <input type="hidden" name="course_ids" id="course_ids"
                                        value="{{ old('course_ids') }}">
                                </div>

                                <div class="form-group">
                                    <label class="required">Select Departments</label>
                                    <div id="department-list" class="item-list">
                                        <p class="placeholder">Select at least one course first</p>
                                    </div>
                                    <div class="selected-tags" id="department-tags"></div>
                                    <input type="hidden" name="department_ids" id="department_ids"
                                        value="{{ old('department_ids') }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-graduation-cap"></i>
                            Academic Criteria
                        </div>

                        <div class="academic-criteria-grid">
                            <div class="form-group">
                                <label>10th Percentage (%)</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-percentage"></i>
                                    <input type="number" name="tenth_percentage" step="0.01" min="0"
                                        max="100" placeholder="e.g. 75.00" value="{{ old('tenth_percentage') }}">
                                </div>
                                <p class="info-text">Minimum required</p>
                            </div>

                            <div class="form-group">
                                <label>12th Percentage (%)</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-percentage"></i>
                                    <input type="number" name="twelfth_percentage" step="0.01" min="0"
                                        max="100" placeholder="e.g. 80.00" value="{{ old('twelfth_percentage') }}">
                                </div>
                                <p class="info-text">Minimum required</p>
                            </div>

                            <div class="form-group">
                                <label>Graduation Percentage (%)</label>
                                <div class="input-with-icon">
                                    <i class="fas fa-percentage"></i>
                                    <input type="number" name="graduation_percentage" step="0.01" min="0"
                                        max="100" placeholder="e.g. 70.00"
                                        value="{{ old('graduation_percentage') }}">
                                </div>
                                <p class="info-text">Minimum required</p>
                            </div>
                        </div>

                        <div class="form-grid">
                            <div>
                                <div class="form-group">
                                    <label>Minimum CGPA Required</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-chart-line"></i>
                                        <input type="text" name="eligibility_cgpa" placeholder="e.g. 7.5"
                                            value="{{ old('eligibility_cgpa') }}">
                                    </div>
                                </div>
                            </div>

                            <div>
                                <div class="form-group">
                                    <label class="required">Eligible Passing Years</label>
                                    <div class="skills-container">
                                        <div class="year-tags" id="year-tags"></div>
                                        <input type="text" id="year-input" placeholder="Type year and press Enter">
                                    </div>
                                    <input type="hidden" name="eligibility_passing_year" id="eligibility_years"
                                        value="{{ old('eligibility_passing_year') }}">
                                    <p class="info-text">Type year and press Enter to add. Click × to remove.</p>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="checkbox-group">
                                <input type="checkbox" name="is_reappear" value="1" id="is_reappear"
                                    {{ old('is_reappear') ? 'checked' : '' }}>
                                <label for="is_reappear">Allow Reappear Students</label>
                            </div>
                            <p class="info-text">Check to allow students marked as reappear to apply for this drive</p>
                        </div>
                    </div>

                    <div class="btn-group">
                        <button type="button" class="btn btn-outline btn-prev" data-prev="drive">Previous</button>
                        <button type="button" class="btn btn-primary btn-next" data-next="contact">Next: Contact &
                            Finalize</button>
                    </div>
                </div>

                <!-- Contact & Finalize Tab -->
                <div class="tab-content" id="contact-tab">
                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-address-book"></i>
                            Contact Information
                        </div>
                        <div class="form-grid">
                            <div>
                                <div class="form-group">
                                    <label class="required">Contact Person</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-user"></i>
                                        <input type="text" name="contact_person" placeholder="Name of contact person"
                                            value="{{ old('contact_person') }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="required">Contact Email</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-envelope"></i>
                                        <input type="email" name="contact_email" placeholder="contact@company.com"
                                            value="{{ old('contact_email') }}">
                                    </div>
                                </div>


                            </div>

                            <div>
                                <div class="form-group">
                                    <label class="required">Drive Coordinator</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-user-cog"></i>
                                        <input type="text" name="drive_coordinator"
                                            placeholder="Name of drive coordinator"
                                            value="{{ old('drive_coordinator') }}">
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="required">Contact Phone</label>
                                    <div class="input-with-icon">
                                        <i class="fas fa-phone"></i>
                                        <input type="tel" name="contact_phone" placeholder="+91 XXXXXXXXXX"
                                            value="{{ old('contact_phone') }}">
                                    </div>
                                </div>


                            </div>
                        </div>
                    </div>

                    <div class="form-section">
                        <div class="form-section-title">
                            <i class="fas fa-check-circle"></i>
                            Final Review
                        </div>
                        <div class="form-group">
                            <div class="info-text">
                                <p><strong>Please review all information before submitting:</strong></p>
                                <ul>
                                    <li>All required fields are completed</li>
                                    <li>Academic criteria are correctly set</li>
                                    <li>Eligibility requirements are properly configured</li>
                                    <li>Contact information is accurate</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="btn-group">
                        <button type="button" class="btn btn-outline btn-prev" data-prev="eligibility">Previous</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-paper-plane"></i>
                            Create Placement Drive
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>



    <script>
        // Global variables
        let selectedColleges = [];
        let selectedCourses = [];
        let selectedDepartments = [];
        let skills = [];
        let years = [];

        // Store college names for reference
        let collegeNamesMap = {};

        document.addEventListener('DOMContentLoaded', function() {
            initializeAllComponents();

            // Build college names map
            buildCollegeNamesMap();
        });

        function buildCollegeNamesMap() {
            const collegeItems = document.querySelectorAll('#college-list .item');
            collegeItems.forEach(item => {
                const collegeId = item.getAttribute('data-id');
                const collegeName = item.textContent.trim().replace(/[\n\r]/g, '');
                collegeNamesMap[collegeId] = collegeName;
            });
        }

        function initializeAllComponents() {
            // Initialize Skills
            initializeSkills();

            // Initialize Years
            initializeYears();

            // Initialize Tabs
            initializeTabs();

            // Initialize Academic Selection
            initializeAcademicSelection();

            // Initialize Form Validation
            initializeFormValidation();
        }

        // ========== SKILLS MANAGEMENT ==========
        function initializeSkills() {
            const requiredSkillsInput = document.getElementById('required_skills');
            try {
                skills = JSON.parse(requiredSkillsInput.value) || [];
                renderSkillsTags();
            } catch (e) {
                skills = [];
            }

            const skillsInput = document.getElementById('skills-input');
            if (!skillsInput) return;

            skillsInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' || e.key === ',') {
                    e.preventDefault();
                    const value = this.value.trim();
                    if (value && !skills.includes(value)) {
                        skills.push(value);
                        this.value = "";
                        renderSkillsTags();
                    }
                }
            });
        }

        function renderSkillsTags() {
            const skillsTags = document.getElementById('skills-tags');
            const requiredSkillsInput = document.getElementById('required_skills');

            if (!skillsTags || !requiredSkillsInput) return;

            skillsTags.innerHTML = "";
            skills.forEach(skill => {
                const tag = document.createElement("div");
                tag.className = "skill-tag";
                tag.innerHTML = `${skill} <span class="skill-tag-remove">×</span>`;
                tag.querySelector(".skill-tag-remove").addEventListener("click", () => {
                    skills = skills.filter(s => s !== skill);
                    renderSkillsTags();
                });
                skillsTags.appendChild(tag);
            });
            requiredSkillsInput.value = JSON.stringify(skills);
        }

        // ========== YEARS MANAGEMENT ==========
        function initializeYears() {
            const eligibilityYearsInput = document.getElementById('eligibility_years');
            if (eligibilityYearsInput && eligibilityYearsInput.value) {
                years = eligibilityYearsInput.value.split(',').filter(year => year.trim());
                renderYearTags();
            }

            const yearInput = document.getElementById('year-input');
            if (!yearInput) return;

            yearInput.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const value = this.value.trim();
                    if (value && !years.includes(value)) {
                        if (!/^\d{4}$/.test(value)) {
                            alert('Please enter a valid 4-digit year');
                            return;
                        }
                        years.push(value);
                        this.value = '';
                        renderYearTags();
                    }
                }
            });
        }

        function renderYearTags() {
            const yearTags = document.getElementById('year-tags');
            const eligibilityYearsInput = document.getElementById('eligibility_years');

            if (!yearTags || !eligibilityYearsInput) return;

            yearTags.innerHTML = '';
            years.forEach(year => {
                const tag = document.createElement('div');
                tag.className = 'year-tag';
                tag.innerHTML = `${year} <span class="year-tag-remove">×</span>`;
                tag.querySelector('.year-tag-remove').addEventListener('click', () => {
                    years = years.filter(y => y !== year);
                    renderYearTags();
                });
                yearTags.appendChild(tag);
            });
            eligibilityYearsInput.value = years.join(',');
        }

        // ========== TAB NAVIGATION ==========
        function initializeTabs() {
            const tabs = document.querySelectorAll('.form-tab');
            const tabContents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.addEventListener('click', () => {
                    const tabId = tab.getAttribute('data-tab');
                    activateTab(tabId);
                });
            });

            document.querySelectorAll('.btn-next').forEach(btn => {
                btn.addEventListener('click', () => {
                    const nextTab = btn.getAttribute('data-next');
                    activateTab(nextTab);
                });
            });

            document.querySelectorAll('.btn-prev').forEach(btn => {
                btn.addEventListener('click', () => {
                    const prevTab = btn.getAttribute('data-prev');
                    activateTab(prevTab);
                });
            });
        }

        function activateTab(tabId) {
            const tabs = document.querySelectorAll('.form-tab');
            const tabContents = document.querySelectorAll('.tab-content');

            tabs.forEach(tab => {
                tab.classList.remove('active');
                if (tab.getAttribute('data-tab') === tabId) {
                    tab.classList.add('active');
                }
            });

            tabContents.forEach(content => {
                content.classList.remove('active');
                if (content.id === `${tabId}-tab`) {
                    content.classList.add('active');
                }
            });
        }

        // ========== ACADEMIC SELECTION ==========
        function initializeAcademicSelection() {
            // Load existing college selections
            const collegeIdsInput = document.getElementById('college_ids');
            if (collegeIdsInput && collegeIdsInput.value) {
                selectedColleges = collegeIdsInput.value.split(',').filter(id => id.trim());

                // Update UI for selected colleges
                selectedColleges.forEach(collegeId => {
                    const collegeItem = document.querySelector(`#college-list .item[data-id="${collegeId}"]`);
                    if (collegeItem) {
                        collegeItem.classList.add('selected');
                    }
                });

                // Load courses if colleges are selected
                if (selectedColleges.length > 0) {
                    setTimeout(() => loadCoursesForSelectedColleges(), 100);
                }
            }

            // Load existing course selections
            const courseIdsInput = document.getElementById('course_ids');
            if (courseIdsInput && courseIdsInput.value) {
                selectedCourses = courseIdsInput.value.split(',').filter(id => id.trim());
            }

            // Load existing department selections
            const departmentIdsInput = document.getElementById('department_ids');
            if (departmentIdsInput && departmentIdsInput.value) {
                selectedDepartments = departmentIdsInput.value.split(',').filter(id => id.trim());
            }

            // College selection
            const collegeList = document.getElementById('college-list');
            if (collegeList) {
                collegeList.addEventListener('click', async e => {
                    const item = e.target.closest('.item');
                    if (item) {
                        await toggleCollegeSelection(item);
                    }
                });
            }

            // Course selection
            const courseList = document.getElementById('course-list');
            if (courseList) {
                courseList.addEventListener('click', async e => {
                    const item = e.target.closest('.item');
                    if (item) {
                        await toggleCourseSelection(item);
                    }
                });
            }

            // Department selection
            const departmentList = document.getElementById('department-list');
            if (departmentList) {
                departmentList.addEventListener('click', e => {
                    const item = e.target.closest('.item');
                    if (item) {
                        toggleDepartmentSelection(item);
                    }
                });
            }
        }

        async function toggleCollegeSelection(item) {
            const collegeId = item.getAttribute('data-id');
            const collegeName = item.textContent.trim();

            // Toggle selection
            if (selectedColleges.includes(collegeId)) {
                selectedColleges = selectedColleges.filter(id => id !== collegeId);
                item.classList.remove('selected');
            } else {
                selectedColleges.push(collegeId);
                item.classList.add('selected');
            }

            // Update hidden input
            document.getElementById('college_ids').value = selectedColleges.join(',');

            // Clear dependent selections when no colleges selected
            if (selectedColleges.length === 0) {
                clearDependentSelections();
            } else {
                // Load courses for selected colleges
                await loadCoursesForSelectedColleges();
            }
        }

        async function loadCoursesForSelectedColleges() {
            const courseList = document.getElementById('course-list');
            if (!courseList) return;

            // Show smooth loading
            courseList.style.opacity = '0.7';
            courseList.innerHTML = `
            <div class="placeholder" style="padding: 30px; text-align: center;">
                <i class="fas fa-spinner fa-spin fa-lg" style="color: #3b82f6; margin-bottom: 10px;"></i>
                <div>Loading courses...</div>
                <small style="color: #6b7280; font-size: 0.9rem;">Selected college${selectedColleges.length > 1 ? 's' : ''}: ${selectedColleges.map(id => collegeNamesMap[id] || id).join(', ')}</small>
            </div>
        `;

            if (selectedColleges.length === 0) {
                courseList.innerHTML = '<div class="placeholder">Select at least one college</div>';
                courseList.style.opacity = '1';
                return;
            }

            try {
                // For each selected college, load courses
                let allCourses = [];

                for (const collegeId of selectedColleges) {
                    const response = await fetch("{{ route('fetch.courses') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            college_id: collegeId
                        })
                    });

                    const courses = await response.json();
                    if (courses && courses.length > 0) {
                        // Add college context to each course
                        const coursesWithContext = courses.map(course => ({
                            ...course,
                            college_id: collegeId,
                            college_name: collegeNamesMap[collegeId] || `College ${collegeId}`
                        }));
                        allCourses = [...allCourses, ...coursesWithContext];
                    }
                }

                // Smooth transition
                setTimeout(() => {
                    courseList.style.opacity = '1';
                    courseList.innerHTML = '';

                    if (allCourses.length > 0) {
                        // Group courses by college for better organization
                        const coursesByCollege = {};
                        allCourses.forEach(course => {
                            const collegeId = course.college_id;
                            if (!coursesByCollege[collegeId]) {
                                coursesByCollege[collegeId] = [];
                            }
                            coursesByCollege[collegeId].push(course);
                        });

                        // Display courses grouped by college
                        Object.keys(coursesByCollege).forEach(collegeId => {
                            const collegeCourses = coursesByCollege[collegeId];
                            const collegeName = collegeNamesMap[collegeId] || `College ${collegeId}`;

                            // Add college header if multiple colleges
                            if (selectedColleges.length > 1) {
                                const collegeHeader = document.createElement('div');
                                collegeHeader.className = 'college-section-header';
                                collegeHeader.style.cssText = `
                                background: #f8fafc;
                                padding: 10px 15px;
                                margin: 10px 0 5px 0;
                                border-radius: 6px;
                                font-weight: 600;
                                color: #374151;
                                border-left: 4px solid #3b82f6;
                                display: flex;
                                justify-content: space-between;
                                align-items: center;
                            `;
                                collegeHeader.innerHTML = `
                                <div>
                                    <i class="fas fa-university" style="margin-right: 8px;"></i>
                                    ${collegeName}
                                </div>
                                <span style="background: #e5e7eb; padding: 2px 8px; border-radius: 12px; font-size: 0.8rem;">
                                    ${collegeCourses.length} course${collegeCourses.length !== 1 ? 's' : ''}
                                </span>
                            `;
                                courseList.appendChild(collegeHeader);
                            }

                            // Add courses for this college
                            collegeCourses.forEach(course => {
                                const courseItem = document.createElement('div');
                                courseItem.className = 'item';
                                courseItem.setAttribute('data-id', course.id);
                                courseItem.setAttribute('data-college-id', collegeId);
                                courseItem.innerHTML = `
                                <i class="fas fa-book" style="color: #3b82f6;"></i>
                                <div style="flex: 1;">
                                    <div style="font-weight: 500;">${course.name}</div>
                                    ${selectedColleges.length === 1 ? '' : `<div style="font-size: 0.8rem; color: #6b7280; margin-top: 2px;">${collegeName}</div>`}
                                </div>
                                ${selectedCourses.includes(course.id.toString()) ? 
                                    '<i class="fas fa-check-circle" style="color: #10b981;"></i>' : ''}
                            `;

                                if (selectedCourses.includes(course.id.toString())) {
                                    courseItem.classList.add('selected');
                                }

                                courseList.appendChild(courseItem);
                            });
                        });
                    } else {
                        courseList.innerHTML = `
                        <div class="placeholder">
                            <i class="fas fa-book" style="font-size: 2rem; color: #9ca3af; margin-bottom: 10px;"></i>
                            <div>No courses found</div>
                            <small style="color: #6b7280;">Try selecting different colleges</small>
                        </div>
                    `;
                    }
                }, 300);

            } catch (error) {
                console.error('Error loading courses:', error);
                courseList.innerHTML = `
                <div class="placeholder" style="color: #ef4444;">
                    <i class="fas fa-exclamation-circle"></i>
                    Error loading courses
                </div>
            `;
                courseList.style.opacity = '1';
            }
        }

        async function toggleCourseSelection(item) {
            const courseId = item.getAttribute('data-id');
            const courseName = item.textContent.trim().split('\n')[0];
            const collegeId = item.getAttribute('data-college-id');
            const collegeName = collegeNamesMap[collegeId] || `College ${collegeId}`;

            // Toggle selection
            if (selectedCourses.includes(courseId)) {
                selectedCourses = selectedCourses.filter(id => id !== courseId);
                item.classList.remove('selected');
                // Remove check icon
                const checkIcon = item.querySelector('.fa-check-circle');
                if (checkIcon) checkIcon.remove();
                removeCourseTag(courseId);
            } else {
                selectedCourses.push(courseId);
                item.classList.add('selected');
                // Add check icon
                if (!item.querySelector('.fa-check-circle')) {
                    item.innerHTML += '<i class="fas fa-check-circle" style="color: #10b981;"></i>';
                }
                addCourseTag(courseId, courseName, collegeName);
            }

            // Update hidden input
            document.getElementById('course_ids').value = selectedCourses.join(',');

            // Load departments for selected courses
            if (selectedCourses.length > 0) {
                await loadDepartmentsForSelectedCourses();
            } else {
                clearDepartmentSelections();
            }
        }

        async function loadDepartmentsForSelectedCourses() {
            const departmentList = document.getElementById('department-list');
            if (!departmentList) return;

            // Smooth loading
            departmentList.style.opacity = '0.7';
            departmentList.innerHTML = `
            <div class="placeholder" style="padding: 30px; text-align: center;">
                <i class="fas fa-spinner fa-spin fa-lg" style="color: #3b82f6; margin-bottom: 10px;"></i>
                <div>Loading departments...</div>
                <small style="color: #6b7280; font-size: 0.9rem;">Selected ${selectedCourses.length} course${selectedCourses.length !== 1 ? 's' : ''}</small>
            </div>
        `;

            if (selectedCourses.length === 0) {
                departmentList.innerHTML = '<div class="placeholder">Select courses first</div>';
                departmentList.style.opacity = '1';
                return;
            }

            try {
                const response = await fetch("{{ route('fetch.departments') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({
                        course_ids: selectedCourses
                    })
                });

                const departments = await response.json();

                // Smooth transition
                setTimeout(() => {
                    departmentList.style.opacity = '1';
                    departmentList.innerHTML = '';

                    if (departments && departments.length > 0) {
                        departments.forEach(dept => {
                            const deptItem = document.createElement('div');
                            deptItem.className = 'item';
                            deptItem.setAttribute('data-id', dept.id);

                            // Get course info for this department
                            const courseId = dept.course_id || dept.courses_id;
                            const courseItem = document.querySelector(
                                `#course-list .item[data-id="${courseId}"]`);
                            const collegeId = courseItem ? courseItem.getAttribute('data-college-id') :
                                '';
                            const collegeName = collegeId ? collegeNamesMap[collegeId] ||
                                `College ${collegeId}` : '';

                            deptItem.innerHTML = `
                            <i class="fas fa-user-graduate" style="color: #8b5cf6;"></i>
                            <div style="flex: 1;">
                                <div style="font-weight: 500;">${dept.name}</div>
                                <div style="font-size: 0.8rem; color: #6b7280; margin-top: 2px;">
                                    ${collegeName ? collegeName + ' • ' : ''}Department
                                </div>
                            </div>
                            ${selectedDepartments.includes(dept.id.toString()) ? 
                                '<i class="fas fa-check-circle" style="color: #10b981;"></i>' : ''}
                        `;

                            if (selectedDepartments.includes(dept.id.toString())) {
                                deptItem.classList.add('selected');
                            }

                            departmentList.appendChild(deptItem);
                        });
                    } else {
                        departmentList.innerHTML = `
                        <div class="placeholder">
                            <i class="fas fa-user-graduate" style="font-size: 2rem; color: #9ca3af; margin-bottom: 10px;"></i>
                            <div>No departments found</div>
                            <small style="color: #6b7280;">Try selecting different courses</small>
                        </div>
                    `;
                    }
                }, 300);

            } catch (error) {
                console.error('Error loading departments:', error);
                departmentList.innerHTML = `
                <div class="placeholder" style="color: #ef4444;">
                    <i class="fas fa-exclamation-circle"></i>
                    Error loading departments
                </div>
            `;
                departmentList.style.opacity = '1';
            }
        }

        function toggleDepartmentSelection(item) {
            const deptId = item.getAttribute('data-id');
            const deptName = item.textContent.trim().split('\n')[0];
            const deptDetails = item.querySelector('div')?.textContent || '';

            // Toggle selection
            if (selectedDepartments.includes(deptId)) {
                selectedDepartments = selectedDepartments.filter(id => id !== deptId);
                item.classList.remove('selected');
                // Remove check icon
                const checkIcon = item.querySelector('.fa-check-circle');
                if (checkIcon) checkIcon.remove();
                removeDepartmentTag(deptId);
            } else {
                selectedDepartments.push(deptId);
                item.classList.add('selected');
                // Add check icon
                if (!item.querySelector('.fa-check-circle')) {
                    item.innerHTML += '<i class="fas fa-check-circle" style="color: #10b981;"></i>';
                }
                addDepartmentTag(deptId, deptName, deptDetails);
            }

            // Update hidden input
            document.getElementById('department_ids').value = selectedDepartments.join(',');
        }

        // ========== TAG MANAGEMENT ==========
        function addCourseTag(courseId, courseName, collegeName) {
            const courseTags = document.getElementById('course-tags');
            if (!courseTags) return;

            const tag = document.createElement('div');
            tag.className = 'selected-tag';
            tag.setAttribute('data-id', courseId);
            tag.innerHTML = `
            <i class="fas fa-book" style="color: #3b82f6;"></i>
            <div style="display: flex; flex-direction: column; gap: 2px;">
                <span style="font-weight: 500;">${courseName}</span>
                <span style="font-size: 0.75rem; color: #6b7280;">${collegeName}</span>
            </div>
            <span class="remove-tag" onclick="removeCourseSelection('${courseId}')">×</span>
        `;
            courseTags.appendChild(tag);
        }

        function addDepartmentTag(deptId, deptName, deptDetails) {
            const departmentTags = document.getElementById('department-tags');
            if (!departmentTags) return;

            const tag = document.createElement('div');
            tag.className = 'selected-tag';
            tag.setAttribute('data-id', deptId);
            tag.innerHTML = `
            <i class="fas fa-user-graduate" style="color: #8b5cf6;"></i>
            <div style="display: flex; flex-direction: column; gap: 2px;">
                <span style="font-weight: 500;">${deptName}</span>
                <span style="font-size: 0.75rem; color: #6b7280;">${deptDetails.split('•')[0]?.trim() || 'Department'}</span>
            </div>
            <span class="remove-tag" onclick="removeDepartmentSelection('${deptId}')">×</span>
        `;
            departmentTags.appendChild(tag);
        }

        function removeCourseTag(courseId) {
            const courseTags = document.getElementById('course-tags');
            if (courseTags) {
                const tag = courseTags.querySelector(`[data-id="${courseId}"]`);
                if (tag) tag.remove();
            }
        }

        function removeDepartmentTag(deptId) {
            const departmentTags = document.getElementById('department-tags');
            if (departmentTags) {
                const tag = departmentTags.querySelector(`[data-id="${deptId}"]`);
                if (tag) tag.remove();
            }
        }

        // ========== GLOBAL REMOVAL FUNCTIONS ==========
        window.removeCourseSelection = async function(courseId) {
            selectedCourses = selectedCourses.filter(id => id !== courseId);
            document.getElementById('course_ids').value = selectedCourses.join(',');

            // Remove from UI
            const courseItem = document.querySelector(`#course-list .item[data-id="${courseId}"]`);
            if (courseItem) {
                courseItem.classList.remove('selected');
                const checkIcon = courseItem.querySelector('.fa-check-circle');
                if (checkIcon) checkIcon.remove();
            }

            removeCourseTag(courseId);

            // Reload departments
            if (selectedCourses.length > 0) {
                await loadDepartmentsForSelectedCourses();
            } else {
                clearDepartmentSelections();
            }
        };

        window.removeDepartmentSelection = function(deptId) {
            selectedDepartments = selectedDepartments.filter(id => id !== deptId);
            document.getElementById('department_ids').value = selectedDepartments.join(',');

            // Remove from UI
            const deptItem = document.querySelector(`#department-list .item[data-id="${deptId}"]`);
            if (deptItem) {
                deptItem.classList.remove('selected');
                const checkIcon = deptItem.querySelector('.fa-check-circle');
                if (checkIcon) checkIcon.remove();
            }

            removeDepartmentTag(deptId);
        };

        function clearDependentSelections() {
            // Clear courses
            selectedCourses = [];
            document.getElementById('course_ids').value = '';
            const courseTags = document.getElementById('course-tags');
            if (courseTags) courseTags.innerHTML = '';
            document.querySelectorAll('#course-list .item').forEach(i => {
                i.classList.remove('selected');
                const checkIcon = i.querySelector('.fa-check-circle');
                if (checkIcon) checkIcon.remove();
            });

            // Clear departments
            clearDepartmentSelections();

            // Reset lists
            const courseList = document.getElementById('course-list');
            const departmentList = document.getElementById('department-list');

            if (courseList) {
                courseList.innerHTML = '<div class="placeholder">Select at least one college first</div>';
                courseList.style.opacity = '1';
            }
            if (departmentList) {
                departmentList.innerHTML = '<div class="placeholder">Select courses first</div>';
                departmentList.style.opacity = '1';
            }
        }

        function clearDepartmentSelections() {
            selectedDepartments = [];
            document.getElementById('department_ids').value = '';
            const departmentTags = document.getElementById('department-tags');
            if (departmentTags) departmentTags.innerHTML = '';
            document.querySelectorAll('#department-list .item').forEach(i => {
                i.classList.remove('selected');
                const checkIcon = i.querySelector('.fa-check-circle');
                if (checkIcon) checkIcon.remove();
            });
        }

        // ========== FORM VALIDATION ==========
        function initializeFormValidation() {
            const form = document.getElementById('placementDriveForm');
            if (!form) return;

            form.addEventListener('submit', function(e) {
                let hasErrors = false;
                const errors = [];

                if (selectedColleges.length === 0) {
                    errors.push('Please select at least one college');
                    hasErrors = true;
                }

                if (selectedCourses.length === 0) {
                    errors.push('Please select at least one course');
                    hasErrors = true;
                }

                if (selectedDepartments.length === 0) {
                    errors.push('Please select at least one department');
                    hasErrors = true;
                }

                if (years.length === 0) {
                    errors.push('Please add at least one eligible passing year');
                    hasErrors = true;
                }

                if (hasErrors) {
                    e.preventDefault();
                    activateTab('eligibility');

                    // Show all errors in one alert
                    const errorMessage = errors.join('\n• ');
                    alert('Please fix the following errors:\n\n• ' + errorMessage);
                }
            });
        }

        // ========== DEBUG FUNCTIONS ==========
        function debugSelectionState() {
            console.log('=== DEBUG SELECTION STATE ===');
            console.log('Selected Colleges:', selectedColleges);
            console.log('Selected Courses:', selectedCourses);
            console.log('Selected Departments:', selectedDepartments);
            console.log('College Names Map:', collegeNamesMap);
            console.log('==============================');
        }

        // Make debug function available globally
        window.debugSelectionState = debugSelectionState;
    </script>
@endsection
