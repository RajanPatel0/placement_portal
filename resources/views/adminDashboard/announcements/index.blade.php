@extends('dashboardLayouts.base')

@section('title', 'Announcement')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Announcements & Notices</h3>
                        <a href="{{ route('admin.announcements.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus"></i> Add New
                        </a>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Type</th>
                                        <th>Priority</th>
                                        <th>Status</th>
                                        <th>Publish Date</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($announcements as $announcement)
                                        <tr>
                                            <td>{{ $announcement->id }}</td>
                                            <td>{{ Str::limit($announcement->title, 50) }}</td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $announcement->type == 'announcement' ? 'info' : 'warning' }}">
                                                    {{ ucfirst($announcement->type) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $announcement->priority == 'urgent' ? 'danger' : ($announcement->priority == 'important' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($announcement->priority) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span
                                                    class="badge badge-{{ $announcement->is_active ? 'success' : 'danger' }}">
                                                    {{ $announcement->is_active ? 'Active' : 'Inactive' }}
                                                </span>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($announcement->publish_date)->format('M d, Y') }}
                                            </td>
                                            <td>
                                                <div class="btn-group">
                                                    <a href="{{ route('admin.announcements.edit', $announcement->id) }}"
                                                        class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <form
                                                        action="{{ route('admin.announcements.toggle', $announcement->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-sm btn-{{ $announcement->is_active ? 'secondary' : 'success' }}"
                                                            title="{{ $announcement->is_active ? 'Deactivate' : 'Activate' }}">
                                                            <i
                                                                class="fas fa-{{ $announcement->is_active ? 'eye-slash' : 'eye' }}"></i>
                                                        </button>
                                                    </form>
                                                    <form
                                                        action="{{ route('admin.announcements.destroy', $announcement->id) }}"
                                                        method="POST" class="d-inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger"
                                                            onclick="return confirm('Are you sure?')" title="Delete">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="text-center">No announcements found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $announcements->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
