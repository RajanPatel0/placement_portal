@extends('dashboardLayouts.base')

@section('title', 'Admin Dashboard')

@section('content')

<section class="content">
    <div class="container-fluid pt-4">

        <div class="row">

            <!-- Sliders -->
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card card-primary card-outline shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-images fa-3x text-primary mb-3"></i>
                        <h4>Carousels / Sliders</h4>
                        <p>Manage homepage sliders</p>

                        <a href="{{ route('adminDashboard.getSliders') }}" class="btn btn-primary btn-sm">
                            Manage
                        </a>
                    </div>
                </div>
            </div>

            <!-- Placement -->
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card card-success card-outline shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-briefcase fa-3x text-success mb-3"></i>
                        <h4>Placement Updates</h4>
                        <p>Manage Latest placements Update</p>

                        <a href="{{ route('placement-updates.index') }}" class="btn btn-success btn-sm">
                            Manage
                        </a>
                    </div>
                </div>
            </div>


            <!-- Testimonials -->
            <div class="col-lg-4 col-md-6 col-12">
                <div class="card card-warning card-outline shadow-sm">
                    <div class="card-body text-center">
                        <i class="fas fa-star fa-3x text-warning mb-3"></i>
                        <h4>Learners to Leaders</h4>
                        <p>Manage testimonials</p>

                        <a href="{{route('testimonials.index')}}" class="btn btn-warning btn-sm">
                            Manage
                        </a>
                    </div>
                </div>
            </div>

        </div>

    </div>
</section>

@endsection