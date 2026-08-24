@extends('layouts.base')

@section('content')
    <!-- Main Content -->
    <main class="main-content pb-5">

        @auth
            <!-- Quick Actions -->
            <div class="quick-actions">
                <a href="{{ route('applied.placements') }}" style="text-decoration: none;">

                    <div class="action-item">
                        <div class="action-icon action-1">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <span class="action-label">Applications</span>
                    </div>
                </a>

                <a href="{{ route('saved.drives') }}" style="text-decoration: none;">
                    <div class="action-item">
                        <div class="action-icon action-2">
                            <i class="fas fa-bookmark"></i>
                        </div>
                        <span class="action-label">Saved Jobs</span>
                    </div>
                </a>
                <div class="action-item">
                    <div class="action-icon action-3">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <span class="action-label">Interviews</span>
                </div>


                <a href="{{ route('placement.stats') }}" style="text-decoration: none;">

                    <div class="action-item">
                        <div class="action-icon action-4">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <span class="action-label">Stats</span>
                    </div>
                </a>
            </div>
        @endauth


        <!-- Recommended Jobs -->
        <div class="section-title">
            <span>Recommended for you</span>
            <span class="view-all">View all</span>
        </div>

        <div class="job-list">
            <!-- Job Card 1 -->
            @foreach ($driveData as $drive)
                <a href="{{ route('drive.detail', $drive->id) }}" class="no-underline" style="text-decoration: none;">
                    <div class="job-card">
                        <div class="job-header">
                            <div class="job-title w-90">{{ $drive->job_title }}</div>
                            @php
                                $salaryText = 'Not Disclosed';
                                if (!empty($drive->package_offered) && $drive->package_offered > 0) {
                                    $salaryText = '₹' . number_format($drive->package_offered, 1) . ' LPA';
                                }
                            @endphp
                            <div class="job-salary w-10" style="white-space: nowrap;">{{ $salaryText }}</div>
                        </div>
                        <div class="company-info">
                            @php
                                $name = trim($drive->company_name);
                                $words = preg_split('/\s+/', $name);
                                $logoText = '';

                                if (count($words) >= 2) {
                                    // First letter of first word + first letter of last word
                                    $logoText = strtoupper(substr($words[0], 0, 1) . substr(end($words), 0, 1));
                                } elseif (count($words) === 1) {
                                    // Only one word → first letter only
                                    $logoText = strtoupper(substr($words[0], 0, 1));
                                }
                            @endphp

                            <div class="company-logo">
                                {{ $logoText }}
                            </div>
                            <div class="company-name">
                                {{ $drive->company_name }}
                            </div>




                        </div>

                        <!-- Drive Date and Status -->
                        <div class="drive-details d-flex justify-content-between align-items-center w-100">

                            <!-- LEFT: Drive Date -->
                            <div class="detail-item date-item d-flex align-items-center">
                                <i class="fas fa-calendar-alt mr-2"></i>
                                <span
                                    style="color: #1a84a1">{{ \Carbon\Carbon::parse($drive->drive_date)->format('M d, Y') }}</span>
                            </div>

                            <!-- RIGHT: Drive Status -->
                            <div class="detail-item status-badge status-{{ $drive->status }} d-flex align-items-center">
                                <i
                                    class="fas 
            @if ($drive->status === 'upcoming') fa-clock
            @elseif($drive->status === 'ongoing') fa-play-circle
            @elseif($drive->status === 'completed') fa-check-circle
            @elseif($drive->status === 'cancelled') fa-times-circle @endif
        "></i>
                                <span class="ml-2">{{ ucfirst($drive->status) }}</span>
                            </div>

                        </div>
                        <style>
                            .drive-details {
                                padding: 3px 0;
                            }

                            .detail-item {
                                font-size: 14px;
                                font-weight: 600;
                                display: flex;
                                align-items: center;
                            }

                            /* STATUS BADGES */
                            .status-badge {
                                padding: 2px 6px;
                                border-radius: 8px;
                            }

                            /* COLORS */
                            .status-upcoming {
                                background: #fff8e1;
                                color: #f4b400;
                                border: 1px solid #f4b400;
                            }

                            .status-ongoing {
                                background: #e3f2fd;
                                color: #1976d2;
                                border: 1px solid #1976d2;
                            }

                            .status-completed {
                                background: #e8f5e9;
                                color: #2e7d32;
                                border: 1px solid #2e7d32;
                            }

                            .status-cancelled {
                                background: #ffebee;
                                color: #d32f2f;
                                border: 1px solid #d32f2f;
                            }
                        </style>
                        <div class="job-tags">
                            @php
                                // Handle both JSON array and comma-separated strings
                                $skills = [];
                                if (!empty($drive->required_skills)) {
                                    $decoded = json_decode($drive->required_skills, true);
                                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                        $skills = $decoded;
                                    } else {
                                        $skills = explode(',', $drive->required_skills);
                                    }
                                }
                            @endphp

                            @foreach ($skills as $skill)
                                @php
                                    $skillName = ucfirst(strtolower(trim($skill)));
                                @endphp
                                @if ($skillName !== '')
                                    <span class="tag">{{ $skillName }}</span>
                                @endif
                            @endforeach
                        </div>


                    </div>
                </a>
            @endforeach

            <!-- Job Card 2 -->

        </div>

        <!-- Recent Interviews -->
        {{-- <div class="section-title">
        <span>Recent Interviews</span>
        <span class="view-all">View all</span>
    </div> --}}


    </main>
    <style>
        /* Main Content */
        .main-content {
            flex: 1;
            overflow-y: auto;
            padding: 20px 16px;
            background: linear-gradient(to bottom, #f5f7fa 0%, #e4e9f2 100%);
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 700;
            margin-bottom: 18px;
            color: #2d3748;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .view-all {
            font-size: 0.9rem;
            color: #4361ee;
            font-weight: 600;
            cursor: pointer;
        }

        /* Job Cards with Gradient */
        .job-list {
            display: flex;
            flex-direction: column;
            gap: 18px;
            margin-bottom: 28px;
        }

        .job-card {
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fc 100%);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            gap: 14px;
            transition: transform 0.3s, box-shadow 0.3s;
            border-left: 5px solid #4361ee;
        }

        .job-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.1);
        }

        .job-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .job-title {
            font-weight: 700;
            font-size: 1.2rem;
            background: linear-gradient(135deg, #3a0ca3 0%, #4361ee 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .job-salary {
            color: #4cc9a4;
            font-weight: 700;
            font-size: 1rem;
        }

        .company-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .company-logo {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            box-shadow: 0 4px 8px rgba(67, 97, 238, 0.2);
        }

        .company-name {
            font-weight: 600;
            color: #2d3748;
        }

        .job-tags {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .tag {
            background: linear-gradient(135deg, #4895ef 0%, #4361ee 100%);
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            color: white;
            font-weight: 500;
        }

        /* Quick Actions with Gradient */
        .quick-actions {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 18px;
            margin-bottom: 28px;
        }

        .action-item {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 10px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .action-item:hover {
            transform: translateY(-3px);
        }

        .action-icon {
            width: 55px;
            height: 55px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            color: white;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
        }

        .action-1 {
            background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        }

        .action-2 {
            background: linear-gradient(135deg, #f72585 0%, #b5179e 100%);
        }

        .action-3 {
            background: linear-gradient(135deg, #4cc9a4 0%, #4895ef 100%);
        }

        .action-4 {
            background: linear-gradient(135deg, #ff9e00 0%, #ff5400 100%);
        }

        .action-label {
            font-size: 0.85rem;
            text-align: center;
            color: #2d3748;
            font-weight: 500;
        }
    </style>
@endsection
