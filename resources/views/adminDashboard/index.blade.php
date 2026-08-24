@extends('dashboardLayouts.base')

@section('title', 'Register')

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

<!-- Main content -->
<section class="content ">
    <div class="container-fluid pt-3">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <a href="{{ route('admin.college.get') }}" class="text-decoration-none">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $collegecount ?? 0 }}</h3>
                            <p>Colleges</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                    </div>
                </a>

            </div>
            
         


            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <a href="{{ route('admin.placement.drives') }}" class="text-decoration-none">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $drivecount ?? 0}}<sup style="font-size: 20px"></sup></h3>

                            <p>Placement Drives</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                    </div>
                </a>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <a href="{{ route('admin.companies') }}" class="text-decoration-none" ">
                    <div class=" small-box bg-warning">
                    <div class="inner">
                        <h3>44</h3>
                        <p>Applications</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-person-add"></i>
                    </div>
            </div>
            </a>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-6">
            <a href="{{ route('admin.drive.stats') }}" class="text-decoration-none">

                <!-- small box -->
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>65</h3>

                        <p>Drive stats</p>
                    </div>
                    <div class="icon">
                        <i class="ion ion-pie-graph"></i>
                    </div>
                </div>
            </a>
        </div>
        <!-- ./col -->
    </div>
</section>
<!-- /.content -->


<div class="modal fade" id="addDataModalLabel" tabindex="-1" aria-labelledby="addDataModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title" id="addDataModalLabel">Add New App Data</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="row g-3">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="app_name" class="form-label">App Name</label>
                                <input type="text" class="form-control" id="app_name" name="app_name" required>
                            </div>
                            <div class="mb-3">
                                <label for="app_name" class="form-label">Title</label>
                                <input type="text" class="form-control" id="tittle" name="tittle" required>
                            </div>
                            <div class="mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control" id="description" name="description" rows="4"
                                    required></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="active" selected>Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="share_link" class="form-label">Share Link</label>
                                <input type="url" class="form-control" id="share_link" name="share_link" required>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="image" class="form-label">App Image</label>
                                <input type="file" class="form-control" id="image" name="image" required>
                            </div>
                            <div class="d-flex justify-content-between">
                                <div class="me-2">
                                    <label for="background_color" class="form-label">Background Color</label>
                                    <input type="color" class="form-control" id="background_color"
                                        name="background_color" value="#ffffff" required>
                                </div>
                                <div>
                                    <label for="text_color" class="form-label">Text Color</label>
                                    <input type="color" class="form-control" id="text_color" name="text_color"
                                        value="#000000" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-info btn-sm">Add App</button>
                </div>
            </form>
        </div>
    </div>
</div>



@endsection

@section('scripts')
<!-- Additional JS specific to the dashboard -->
<script src="{{ asset('js/dashboard.js') }}"></script>
@endsection