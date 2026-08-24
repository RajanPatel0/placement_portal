@extends('dashboardLayouts.base')

@section('content')
    <style>
        /* Your exact CSS from profile.blade.php */
        :root {
            --primary: #4361ee;
            --secondary: #3a0ca3;
            --accent: #f72585;
            --light: #f8f9fa;
            --dark: #212529;
            --success: #4cc9f0;
            --warning: #f9c74f;
            --danger: #e63946;
            --gray: #6c757d;
            --card-bg: #ffffff;
            --app-bg: #f0f2f5;
        }

        .profile-sections {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        @media (max-width: 900px) {
            .profile-sections {
                grid-template-columns: 1fr;
            }
        }

        .section-card {
            background: var(--card-bg);
            padding: 25px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .section-title i {
            color: var(--primary);
        }

        .section-action {
            color: var(--primary);
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
        }

        .skills-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .skill-pill {
            padding: 8px 18px;
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary);
            border-radius: 20px;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .skill-pill:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-2px);
        }

        .academic-item {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .academic-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .academic-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }

        .academic-name {
            font-weight: 600;
            color: var(--dark);
        }

        .academic-date {
            color: var(--gray);
            font-size: 14px;
        }

        .academic-details {
            color: var(--gray);
            font-size: 14px;
            margin-bottom: 8px;
        }

        .progress-bar {
            height: 8px;
            background: rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: var(--primary);
            border-radius: 10px;
        }

        .experience-item {
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .experience-item:last-child {
            margin-bottom: 0;
            padding-bottom: 0;
            border-bottom: none;
        }

        .experience-role {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 5px;
        }

        .experience-company {
            color: var(--primary);
            margin-bottom: 8px;
            font-size: 14px;
        }

        .experience-duration {
            color: var(--gray);
            font-size: 13px;
            margin-bottom: 12px;
        }

        .experience-desc {
            color: var(--gray);
            font-size: 14px;
            line-height: 1.5;
        }

        .projects-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }

        .project-card {
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 15px;
            padding: 18px;
            transition: all 0.3s ease;
        }

        .project-card:hover {
            border-color: var(--primary);
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
        }

        .project-title {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 10px;
        }

        .project-desc {
            color: var(--gray);
            font-size: 14px;
            margin-bottom: 15px;
            line-height: 1.5;
        }

        .project-tech {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-bottom: 15px;
        }

        .tech-tag {
            background: rgba(0, 0, 0, 0.06);
            color: var(--gray);
            padding: 4px 12px;
            border-radius: 15px;
            font-size: 12px;
        }

        .content-spacer {
            height: 80px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animated {
            animation: fadeIn 0.5s ease forwards;
        }

        .delay-1 {
            animation-delay: 0.1s;
        }

        .delay-2 {
            animation-delay: 0.2s;
        }

        .delay-3 {
            animation-delay: 0.3s;
        }

        .delay-4 {
            animation-delay: 0.4s;
        }

        /* Your exact profile card styles */
        .profile-card {
            width: 100%;
            background-color: #fff;
            box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.08), 0 4px 12px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .profile-cover {
            width: 100%;
            background: linear-gradient(135deg, #0066a9 0%, #0095da 100%);
            position: relative;
            overflow: hidden;
        }

        .profile-cover img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .profile-cover::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 100px;
            background: linear-gradient(to top, rgba(255, 255, 255, 1) 0%, rgba(255, 255, 255, 0) 100%);
            pointer-events: none;
        }

        .profile-img-container {
            margin-top: -90px;
            padding-left: 24px;
            position: relative;
            width: fit-content;
            z-index: 1;
        }

        .profile-img {
            width: 160px;
            height: 160px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid #fff;
            background-color: #fff;
            display: block;
            box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.08), 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
        }

        .profile-info {
            padding: 16px 24px 24px;
        }

        .profile-name {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 4px;
        }

        .profile-name h1 {
            font-size: 28px;
            font-weight: 600;
            color: rgba(0, 0, 0, 0.9);
        }

        .verification-badge {
            color: #0a66c2;
            font-size: 22px;
        }

        .profile-title {
            font-size: 16px;
            color: rgba(0, 0, 0, 0.9);
            font-weight: 400;
            margin-bottom: 8px;
        }

        .profile-location {
            display: flex;
            align-items: center;
            gap: 8px;
            color: rgba(0, 0, 0, 0.6);
            font-size: 14px;
            margin-bottom: 16px;
        }

        .open-to-section {
            background-color: #f3f2ef;
            padding: 9px;
            transition: all 0.2s ease;
            cursor: pointer;
        }

        .open-to-section:hover {
            background-color: #e8e6e1;
        }

        .open-to-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .open-to-title {
            font-weight: 600;
            color: rgba(0, 0, 0, 0.9);
        }

        .resume-wrapper {
            padding: 10px;
            margin: 13px;
            background-color: #f4f1f1ff;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            margin-bottom: 20px;
        }

        .resume_section {
            padding-left: 10px;
        }

        .resume-wrapper:hover {
            background-color: #f1f1f1;
        }

        .resume-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 10px;
            padding: 5px;
            color: #333;
            border-bottom: 2px solid #ff8000ff;
            padding-bottom: 8px;
        }

        .title-left {
            /* Optional: Style just the left title */
        }

        .title-right {
            font-size: 16px;
            font-weight: 500;
            color: #007bff;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .title-right:hover {
            color: #0056b3;
        }

        .skill-pill {
            padding: 6px 13px;
            border-radius: 20px;
            color: #fff;
            font-weight: bold;
            display: inline-block;
            margin: 5px;
            font-size: 14px;
        }

        .skill-pill.beginner {
            background-color: #ef6644;
        }

        .skill-pill.intermediate {
            background-color: #fbb93e;
        }

        .skill-pill.advanced {
            background-color: #9bd770;
            color: #222;
        }

        .skill-pill.expert {
            background-color: #2e8b57;
            color: #fff;
        }
    </style>

    <!-- Admin Header -->
    <div class="admin-header mb-3 p-3 bg-light rounded">
        <a href="{{ url()->previous() }}" class="btn btn-secondary btn-sm">
            <i class="fas fa-arrow-left"></i> Back to Applications
        </a>
        <span class="ms-3 text-muted">Company View: {{ $user->name }}'s Profile</span>
    </div>

    <!-- Your Exact Profile UI -->
    <div class="profile-cover">
        @if ($user->cover_photo != null)
            <img src="{{ asset('/public/' . $user->cover_photo) }}" alt="Cover photo" id="coverImage">
        @else
            <img src="https://images.unsplash.com/photo-1518837695005-2083093ee35b?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1740&q=80"
                alt="Cover photo" id="coverImage">
        @endif
    </div>

    <div class="profile-content">
        <div class="profile-img-container">
            @if ($user->profile_picture != null)
                <img src="{{ asset('/public/' . $user->profile_picture) }}" alt="{{ $user->name }}" class="profile-img"
                    id="profileImage">
            @else
                <img src="/public/assets/contacts.png" alt="{{ $user->name }}" class="profile-img" id="profileImage">
            @endif
        </div>

        <div class="profile-info">
            <div class="profile-name">
                <h1>{{ $user->name }}</h1>
                <!--<i class="fas fa-check-circle verification-badge"></i>-->
            </div>
            <p class="profile-title" style="font-size: 16px;">{{ $userProfile->bio ?? 'N/A' }}</p>
            <div class="profile-location">
                <i class="fas fa-map-marker-alt"></i>
                <span>{{ collect([$user->city, $user->state, $user->country])->filter()->map(function ($item) {
                        return ucfirst(strtolower($item));
                    })->implode(', ') ?:
                    'Location not set' }}
                </span>
            </div>

            <div class="open-to-section">
                <div class="open-to-header">
                    <div class="open-to-title">
                        <div class="completion-badge">
                            <span class="completion-text">Profile Complete: {{ $completionPercent }}%</span>
                            <div class="completion-progress">
                                <div class="progress-fill" style="width: {{ $completionPercent }}%"></div>
                            </div>
                            @if ($completionPercent < 100)
                                <small class="completion-tip">Complete your profile for better
                                    opportunities</small>
                            @else
                                <small class="completion-success">🎉 Profile Complete!</small>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="resume-wrapper">
        <div class="resume_section">
            <h2 class="resume-title">
                <span class="title-left">Resume</span>
                @if ($resumePath)
                    <span class="title-right">Available</span>
                @else
                    <span class="title-right text-danger">Not Uploaded</span>
                @endif
            </h2>
        </div>
    </div>

    <!-- Profile Sections Grid -->
    <div class="profile-sections">
        <!-- Skills Section -->
        <div class="section-card animated delay-1">
            <div class="section-header">
                <div class="section-title">
                    <i class="fas fa-code"></i>
                    <span>Technical Skills</span>
                </div>
            </div>
            <div class="skills-grid">
                @foreach ($skills as $skill)
                    <div class="skill-pill {{ strtolower($skill->proficiency_level) }}">
                        {{ $skill->skill_name }}
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Academic Section -->
        <div class="section-card animated delay-1">
            <div class="section-header">
                <div class="section-title">
                    <i class="fas fa-graduation-cap"></i>
                    <span>Academic History</span>
                </div>
            </div>
            @if ($academicHistory->isEmpty())
                <p>No academic history added yet.</p>
            @else
                <div class="academic-list">
                    @foreach ($academicHistory as $history)
                        <div class="academic-item">
                            <div class="academic-header">
                                <div class="academic-name">{{ $history->degree }}</div>
                                <div class="academic-date">{{ \Carbon\Carbon::parse($history->start_date)->format('Y') }} -
                                    {{ \Carbon\Carbon::parse($history->end_date)->format('Y') }}
                                </div>
                            </div>
                            @php
                                $grade = $history->grade_type === 'gpa' ? $history->grade * 10 : $history->grade;
                                $progress = min(max($grade, 0), 100); // Clamp between 0–100

                                // Hue: 0 (red) to 120 (green)
                                $hue = (int) ($progress * 1.2); // 100 * 1.2 = 120 (green)

                                // Generate HSL color
                                $color = "hsl($hue, 80%, 50%)";
                            @endphp

                            @if ($history->grade_type == 'percentage')
                                <div class="academic-details">• Percentage: {{ $history->grade }}%</div>
                            @elseif($history->grade_type == 'gpa')
                                <div class="academic-details">• GPA: {{ $history->grade }}/10</div>
                            @endif

                            <div class="progress-bar">
                                <div class="progress-fill"
                                    style="width: {{ $progress }}%; background-color: {{ $color }};"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Experience Section -->
        <div class="section-card animated delay-2">
            <div class="section-header">
                <div class="section-title">
                    <i class="fas fa-briefcase"></i>
                    <span>Work Experience</span>
                </div>
            </div>
            @if ($experiences->isEmpty())
                <p>No work experience added yet.</p>
            @else
                <div class="experience-list">
                    @foreach ($experiences as $experience)
                        <div class="experience-item">
                            <div class="experience-role">{{ $experience->tittle }}</div>
                            <div class="experience-company">{{ $experience->company_name }}</div>
                            <div class="experience-duration">{{ $experience->start_date }} - {{ $experience->end_date }}
                            </div>

                            <div class="experience-desc">
                                {{ $experience->description }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Projects Section -->
        <div class="section-card animated delay-2">
            <div class="section-header">
                <div class="section-title">
                    <i class="fas fa-project-diagram"></i>
                    <span>Projects</span>
                </div>
            </div>

            <div class="projects-grid">
                @if ($projects->isEmpty())
                    <p>No projects added yet.</p>
                @else
                    @foreach ($projects as $project)
                        <div class="project-card">
                            <h4 class="project-title">{{ $project->tittle ?? 'Untitled Project' }}</h4>
                            <p class="project-desc">{{ $project->description ?? 'No description available.' }}</p>

                            <div class="project-tech">
                                @php
                                    // Normalize project->skills (handles JSON, string, or array)
                                    $skills = $project->skills;
                                    if (is_string($skills)) {
                                        $decoded = json_decode($skills, true);
                                        $skills = $decoded ?: array_map('trim', explode(',', $skills));
                                    }
                                @endphp

                                @foreach ($skills as $tech)
                                    @if (!empty($tech))
                                        <span
                                            class="tech-tag badge bg-primary-subtle text-primary border border-primary me-1">
                                            {{ ucfirst($tech) }}
                                        </span>
                                    @endif
                                @endforeach
                                <a href="{{ $project->project_url }}" target="_blank"
                                    style="text-decoration: none;">View</a>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </div>

    <!-- Spacer for bottom navigation -->
    <div class="content-spacer"></div>

    <!-- Include Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
@endsection
