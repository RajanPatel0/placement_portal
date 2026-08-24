@extends('dashboardLayouts.base')

@section('title', 'Site Configuration')

@section('content')

<div class="container-fluid">

    {{-- HEADER --}}
    <div class="mb-3">
        <h3 class="text-dark">
            <i class="fas fa-images"></i> Manage Sliders
        </h3>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif


    {{-- ADD SLIDER FORM --}}
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Add New Slider</h5>
        </div>

        <div class="card-body">
            <form action="{{ route('adminDashboard.siteConfig.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row">

                    <div class="col-md-3 mb-3">
                        <input type="text" name="title" class="form-control" placeholder="Title">
                    </div>

                    <div class="col-md-3 mb-3">
                        <input type="text" name="subheading" class="form-control" placeholder="Subheading">
                    </div>

                    <div class="col-md-3 mb-3">
                        <input type="number" name="order" class="form-control" placeholder="Order">
                    </div>

                    <div class="col-md-3 mb-3">
                        <select name="is_active" class="form-control">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <textarea name="description" class="form-control" placeholder="Description"></textarea>
                    </div>

                    <div class="col-md-6 mb-3">
                        <input type="file" name="image" class="form-control">
                    </div>

                </div>

                <button class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Slider
                </button>

            </form>
        </div>
    </div>


    {{-- TABLE --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">All Sliders</h5>
        </div>

        <div class="card-body table-responsive">

            <table class="table table-bordered table-hover text-center align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Title</th>
                        <th>Subheading</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($sliders as $slider)
                        <tr>
                            <td>{{ $slider->id }}</td>

                            {{-- IMAGE WITH HOVER ZOOM EFFECT --}}
                            <td>
                                @if($slider->image_path)
                                    <div class="image-container">
                                        <img src="{{ asset('/public/' . $slider->image_path) }}"
                                             class="hover-zoom-img"
                                             width="80"
                                             height="50"
                                             style="object-fit:cover; border-radius:5px;">
                                    </div>
                                @endif
                            </td>

                            <td>{{ $slider->title }}</td>
                            <td>{{ $slider->subheading }}</td>
                            <td>{{ $slider->order }}</td>

                            <td>
                                <span class="badge bg-{{ $slider->is_active ? 'success' : 'danger' }}">
                                    {{ $slider->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                            <td>
                                <form action="{{ route('adminDashboard.siteConfig.delete', $slider->id) }}"
                                      method="POST"
                                      style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')

                                    <button class="btn btn-danger btn-sm"
                                            onclick="return confirm('Delete this slider?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>

                        </tr>
                    @endforeach
                </tbody>

            </table>

        </div>
    </div>

</div>

<style>
/* Hover Zoom Effect CSS */
.image-container {
    position: relative;
    display: inline-block;
}

.hover-zoom-img {
    width: 80px;
    height: 50px;
    object-fit: cover;
    border-radius: 5px;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    cursor: pointer;
}

.hover-zoom-img:hover {
    transform: scale(3.5);
    box-shadow: 0 10px 20px rgba(0,0,0,0.3);
    z-index: 1000;
    position: relative;
    border-radius: 8px;
}

/* Agar table ke cells mein overflow issue ho to */
.table td {
    vertical-align: middle;
    overflow: visible !important;
}

.table .image-container {
    overflow: visible !important;
}
</style>

@endsection

@section('scripts')
<!-- No extra JavaScript needed, pure CSS hover effect -->
@endsection