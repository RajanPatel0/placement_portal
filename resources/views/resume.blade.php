@extends('layouts.base')

@section('content')

    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3a0ca3;
            --success: #4cc9f0;
            --light: #f8f9fa;
            --dark: #212529;
            --gray: #6c757d;
            --danger: #e5383b;
            --warning: #fca311;
            --box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            --transition: all 0.3s ease;
        }

       
      

       

       


        .dashboard {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 25px;
        }

        .card {
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--box-shadow);
            padding: 20px;
            transition: var(--transition);
        }

        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
        }

        .card-title {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 20px;
            color: var(--primary);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .upload-area {
            border: 2px dashed var(--primary);
            border-radius: var(--border-radius);
            padding: 30px;
            text-align: center;
            margin-bottom: 20px;
            cursor: pointer;
            transition: var(--transition);
        }

        .upload-area:hover {
            background: rgba(67, 97, 238, 0.05);
        }

        .upload-icon {
            font-size: 48px;
            color: var(--primary);
            margin-bottom: 15px;
        }

        .upload-text {
            margin-bottom: 15px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 50px;
            font-weight: 500;
            cursor: pointer;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }

        .btn-primary:hover {
            background: var(--secondary);
        }

        .btn-danger {
            background: var(--danger);
            color: white;
        }

        .btn-danger:hover {
            background: #c1121f;
        }

        .btn-outline {
            background: transparent;
            border: 1px solid var(--primary);
            color: var(--primary);
        }

        .btn-outline:hover {
            background: var(--primary);
            color: white;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
        }

        input[type="text"],
        input[type="file"] {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            font-family: 'Poppins', sans-serif;
        }

        input:focus {
            outline: none;
            border-color: var(--primary);
        }

        .resume-list {
            list-style: none;
        }

        .resume-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px;
            border-radius: var(--border-radius);
            background: var(--light);
            margin-bottom: 15px;
            margin-left: -50px;
        }

        .resume-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .resume-icon {
            width: 40px;
            height: 40px;
            background: rgba(67, 97, 238, 0.1);
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--primary);
        }

        .resume-details h3 {
            font-size: 16px;
            margin-bottom: 5px;
        }

        .resume-details p {
            font-size: 14px;
            color: var(--gray);
        }

        .resume-actions {
            display: flex;
            gap: 10px;
        }

        .badge {
            padding: 5px 10px;
            border-radius: 50px;
            font-size: 12px;
            font-weight: 500;
        }

        .badge-primary {
            background: rgba(67, 97, 238, 0.1);
            color: var(--primary);
        }

        .badge-success {
            background: rgba(76, 201, 240, 0.1);
            color: var(--success);
        }

        .alert {
            padding: 15px;
            border-radius: var(--border-radius);
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .alert-success {
            background: rgba(76, 201, 240, 0.1);
            color: #0a9396;
            border-left: 4px solid var(--success);
        }

        .alert-error {
            background: rgba(229, 56, 59, 0.1);
            color: var(--danger);
            border-left: 4px solid var(--danger);
        }

        .alert-icon {
            font-size: 24px;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 24px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 16px;
            width: 16px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: var(--primary);
        }

        input:checked + .slider:before {
            transform: translateX(26px);
        }

        .toggle-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }

        @media (max-width: 768px) {
            .dashboard {
                grid-template-columns: 1fr;
            }

            
        }
    </style>

      

       
        <div class="dashboard">
            <div class="card">
                <h2 class="card-title"><i class="fas fa-file-upload"></i> Upload New Resume</h2>

                <form id="resumeUploadForm" action="{{ route('resume.upload') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    {{-- Drag & Drop Area --}}
                    <div class="upload-area" id="dropZone">
                        <i class="fas fa-cloud-upload-alt upload-icon"></i>
                        <div class="upload-text">
                            <h3>Drag & Drop your file here</h3>
                            <p>Supported formats: PDF, DOC, DOCX (Max: 2MB)</p>
                        </div>
                        <p>or</p>

                        <input type="file" name="resume" id="resumeFile" accept=".pdf,.doc,.docx" style="display: none;">
                        <button type="button" class="btn btn-primary" onclick="document.getElementById('resumeFile').click()">
                            <i class="fas fa-file"></i> Browse Files
                        </button>
                    </div>

                    <div class="form-group">
                        <label for="resumeTitle">Resume Title (Optional)</label>
                        <input type="text" name="resume_title" id="resumeTitle" placeholder="e.g., Software Engineer Resume">
                    </div>

                    <div class="toggle-group">
                        <label class="switch">
                            <input type="checkbox" name="is_default" id="isDefault" value="1" checked>
                            <span class="slider"></span>
                        </label>
                        <span>Set as default resume</span>
                    </div>

                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-upload"></i> Upload Resume
                    </button>
                </form>

            </div>


            <div class="card pb-4">
                <h2 class="card-title"><i class="fas fa-file-alt"></i> My Resumes</h2>
                @if($resumes->isEmpty())
                    <p class="text-gray-500">You haven't uploaded any resumes yet.</p>
                @else
                    <ul class="resume-list">
                        @foreach($resumes as $resume)
                        <li class="resume-item">
                            <div class="resume-info">
                                <div class="resume-icon">
                                    <i class="fas fa-file-{{ pathinfo($resume->resume_path, PATHINFO_EXTENSION) == 'pdf' ? 'pdf' : 'word' }}"></i>
                                </div>
                                <div class="resume-details">
                                    <h3>{{ $resume->resume_title ?? 'Untitled Resume' }}</h3>
                                    <p>Uploaded: {{ \Carbon\Carbon::parse($resume->updated_at)->format('F d, Y') }}</p>
                                </div>
                            </div>
                            <div class="resume-actions">
                                @if($resume->is_default)
                                    <span class="badge badge-success">Default</span>
                                @else
                                    <form action="{{ route('resume.edit') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="resume_id" value="{{ $resume->id }}">
                                        <button type="submit" class="btn btn-outline" title="Set as Default">
                                            <i class="fas fa-star"></i>
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ asset('/public/' .$resume->resume_path) }}" target="_blank" class="btn btn-primary" title="View">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <form action="{{ route('resume.delete') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this resume?');">
                                    @csrf
                                    <input type="hidden" name="resume_id" value="{{ $resume->id }}">
                                    <button type="submit" class="btn btn-danger" title="Delete">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>

    <script>
        const dropZone = document.getElementById('dropZone');
        const fileInput = document.getElementById('resumeFile');
        const form = document.getElementById('resumeUploadForm');

        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, preventDefaults, false);
        });

        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }

        ['dragenter', 'dragover'].forEach(eventName => {
            dropZone.addEventListener(eventName, highlight, false);
        });

        ['dragleave', 'drop'].forEach(eventName => {
            dropZone.addEventListener(eventName, unhighlight, false);
        });

        function highlight() {
            dropZone.style.backgroundColor = 'rgba(67, 97, 238, 0.1)';
            dropZone.style.borderColor = '#3a0ca3';
        }

        function unhighlight() {
            dropZone.style.backgroundColor = '';
            dropZone.style.borderColor = 'var(--primary)';
        }

        dropZone.addEventListener('drop', handleDrop, false);

        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            if (files.length) {
                fileInput.files = files;
                updateDropZoneText(files[0].name);
            }
        }

        fileInput.addEventListener('change', function() {
            if (this.files.length) {
                updateDropZoneText(this.files[0].name);
            }
        });

        function updateDropZoneText(fileName) {
            const fileType = fileName.split('.').pop().toUpperCase();
            dropZone.querySelector('.upload-text').innerHTML = `
                <h3>${fileName}</h3>
                <p>File type: ${fileType}</p>
            `;
        }
    </script>

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
</body>
</html>
@endsection