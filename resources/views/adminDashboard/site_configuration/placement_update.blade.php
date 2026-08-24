
@extends('dashboardLayouts.base')

@section('title', 'Manage Placement Updates')

@section('content')
<div class="container-fluid">
    <div class="mb-3">
        <h3 class="text-dark">
            <i class="fas fa-chart-line"></i> Manage Placement Updates
        </h3>
        <p class="text-muted">Manage latest placement updates shown on homepage</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- ADD PLACEMENT UPDATE FORM -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="fas fa-plus-circle"></i> Add New Placement Update</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('placement-updates.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Title <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control" placeholder="e.g., Amazing Placement Opportunity" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Company Name <span class="text-danger">*</span></label>
                        <input type="text" name="company_name" class="form-control" placeholder="e.g., Google, Microsoft" required>
                    </div>
                    
                    <div class="col-md-4 mb-3">
                        <label class="form-label fw-bold">Package</label>
                        <input type="text" name="package" class="form-control" placeholder="e.g., ₹4.53 LPA">
                    </div>
                    
                    <div class="col-md-8 mb-3">
                        <label class="form-label fw-bold">Description</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Brief description about the placement..."></textarea>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Image <span class="text-danger">*</span></label>
                        <input type="file" name="image" class="form-control" accept="image/*" required>
                        <small class="text-muted">Recommended size: 1024x576px (16:9 ratio)</small>
                    </div>
                    
                    <div class="col-md-12">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Save Placement Update
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <i class="fas fa-undo"></i> Reset
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- PLACEMENT UPDATES TABLE -->
    <div class="card">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0"><i class="fas fa-list"></i> All Placement Updates</h5>
        </div>
        <div class="card-body table-responsive">
            <table class="table table-bordered table-hover" id="placementUpdatesTable">
                <thead class="table-dark">
                    <tr>
                        <th width="50">#</th>
                        <th width="80">Order</th>
                        <th width="120">Image</th>
                        <th>Company</th>
                        <th>Package</th>
                        <th>Title</th>
                        <th>Status</th>
                        <th width="100">Actions</th>
                    </tr>
                </thead>
                <tbody id="sortable-placement">
                    @forelse($placementUpdates as $index => $update)
                    <tr data-id="{{ $update->id }}">
                        <td>{{ $index + 1 }}</td>
                        <td class="drag-handle" style="cursor: move;">
                            <i class="fas fa-grip-vertical text-secondary"></i>
                            <input type="hidden" name="order" value="{{ $update->order }}">
                        </td>
                        <td>
                            @if($update->image_path)
                                <img src="{{ asset('/public/' . $update->image_path) }}" 
                                     width="80" 
                                     height="50" 
                                     style="object-fit:cover; border-radius:5px;"
                                     class="hover-zoom-img">
                            @else
                                <span class="badge bg-secondary">No Image</span>
                            @endif
                        </td>
                        <td class="fw-bold">{{ $update->company_name }}</td>
                        <td><span class="badge bg-success">{{ $update->package ?? 'N/A' }}</span></td>
                        <td>{{ Str::limit($update->title, 50) }}</td>
                        <td>
                            <form action="{{ route('placement-updates.toggle-status', $update->id) }}" method="POST" style="display:inline;">
                                @csrf
                                <button type="submit" class="btn btn-sm {{ $update->is_active ? 'btn-success' : 'btn-secondary' }}">
                                    {{ $update->is_active ? 'Active' : 'Inactive' }}
                                </button>
                            </form>
                        </td>
                        <td>
                            <form action="{{ route('placement-updates.destroy', $update->id) }}" 
                                  method="POST" 
                                  style="display:inline-block;"
                                  onsubmit="return confirm('Delete this placement update?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">No placement updates found. Add your first placement update above.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
.hover-zoom-img {
    transition: transform 0.3s ease;
    cursor: pointer;
}

.hover-zoom-img:hover {
    transform: scale(3);
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    z-index: 1000;
    position: relative;
}

#sortable-placement tr {
    transition: background-color 0.3s ease;
}

#sortable-placement tr:hover {
    background-color: #f5f5f5;
}
</style>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<script>
$(document).ready(function() {
    $("#sortable-placement").sortable({
        handle: ".drag-handle",
        update: function(event, ui) {
            var orders = {};
            $('#sortable-placement tr').each(function(index) {
                var id = $(this).data('id');
                orders[index] = id;
            });
            
            $.ajax({
                url: "{{ route('placement-updates.update-order') }}",
                type: "POST",
                data: {
                    orders: orders,
                    _token: "{{ csrf_token() }}"
                },
                success: function(response) {
                    if(response.success) {
                        // Show success message
                        var toast = '<div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3" style="z-index:9999">Order updated successfully!<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
                        $('body').append(toast);
                        setTimeout(function() {
                            $('.alert').fadeOut();
                        }, 2000);
                    }
                },
                error: function() {
                    alert('Failed to update order. Please try again.');
                }
            });
        }
    });
});
</script>
@endsection