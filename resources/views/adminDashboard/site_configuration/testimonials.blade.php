@extends('dashboardLayouts.base')

@section('title', 'Manage Testimonials')

@section('content')
<div class="container-fluid">
    <div class="mb-3">
        <h3 class="text-dark">
            <i class="fas fa-star"></i> Manage Testimonials
        </h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <!-- ADD TESTIMONIAL FORM -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Add New Testimonial</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('testimonials.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <input type="text" name="name" class="form-control" placeholder="Full Name" required>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <input type="text" name="designation" class="form-control" placeholder="Designation (e.g., Software Engineer)">
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <input type="text" name="company" class="form-control" placeholder="Company (e.g., Google)">
                    </div>
                    
                    <div class="col-md-8 mb-3">
                        <textarea name="quote" class="form-control" rows="3" placeholder="Testimonial Quote" required></textarea>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <input type="file" name="image" class="form-control" accept="image/*">
                        <small class="text-muted">Optional: Profile image</small>
                    </div>
                </div>
                
                <button class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add Testimonial
                </button>
            </form>
        </div>
    </div>

    <!-- TESTIMONIALS TABLE -->
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">All Testimonials</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Designation</th>
                        <th>Company</th>
                        <th>Quote</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($testimonials as $testimonial)
                    <tr>
                        <td>{{ $testimonial->id }}</td>
                        <td>
                            @if($testimonial->image_path)
                                <img src="{{ asset('/public/' . $testimonial->image_path) }}" 
                                     width="50" 
                                     height="50" 
                                     style="object-fit:cover; border-radius:50%;">
                            @else
                                <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center" 
                                     style="width:50px; height:50px;">
                                    <i class="fas fa-user"></i>
                                </div>
                            @endif
                        </td>
                        <td>{{ $testimonial->name }}</td>
                        <td>{{ $testimonial->designation ?? '-' }}</td>
                        <td>{{ $testimonial->company ?? '-' }}</td>
                        <td>{{ Str::limit($testimonial->quote, 60) }}</td>
                        <td>
                            <span class="badge bg-{{ $testimonial->is_active ? 'success' : 'danger' }}">
                                {{ $testimonial->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <form action="{{ route('testimonials.destroy', $testimonial->id) }}" 
                                  method="POST" 
                                  style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger btn-sm" onclick="return confirm('Delete this testimonial?')">
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
@endsection