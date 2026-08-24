@extends('dashboardLayouts.base')

@section('title', 'Announcement')

@section('content')


    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Edit Announcement/Notice</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.announcements.update', $announcement->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">Title *</label>
                                        <input type="text" name="title" id="title" class="form-control"
                                            value="{{ $announcement->title }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type">Type *</label>
                                        <select name="type" id="type" class="form-control" required>
                                            <option value="announcement"
                                                {{ $announcement->type == 'announcement' ? 'selected' : '' }}>Announcement
                                            </option>
                                            <option value="notice" {{ $announcement->type == 'notice' ? 'selected' : '' }}>
                                                Notice</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="priority">Priority *</label>
                                        <select name="priority" id="priority" class="form-control" required>
                                            <option value="normal"
                                                {{ $announcement->priority == 'normal' ? 'selected' : '' }}>Normal</option>
                                            <option value="important"
                                                {{ $announcement->priority == 'important' ? 'selected' : '' }}>Important
                                            </option>
                                            <option value="urgent"
                                                {{ $announcement->priority == 'urgent' ? 'selected' : '' }}>Urgent</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="external_link">External Link</label>
                                        <input type="url" name="external_link" id="external_link" class="form-control"
                                            value="{{ $announcement->external_link }}" placeholder="https://example.com">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="publish_date">Publish Date *</label>
                                        <input type="datetime-local" name="publish_date" id="publish_date"
                                            class="form-control"
                                            value="{{ \Carbon\Carbon::parse($announcement->publish_date)->format('Y-m-d\TH:i') }}"
                                            required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="expiry_date">Expiry Date</label>
                                        <input type="datetime-local" name="expiry_date" id="expiry_date"
                                            class="form-control"
                                            value="{{ $announcement->expiry_date ? \Carbon\Carbon::parse($announcement->expiry_date)->format('Y-m-d\TH:i') : '' }}">
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="content">Content</label>
                                <textarea name="content" id="content" class="form-control" rows="5">{{ $announcement->content }}</textarea>
                            </div>

                            <div class="form-group">
                                <label for="file">Attachment (PDF/DOC)</label>
                                <input type="file" name="file" id="file" class="form-control-file">
                                @if ($announcement->file_path)
                                    <div class="mt-2">
                                        <small>Current file:
                                            <a href="{{ asset($announcement->file_path) }}" target="_blank"
                                                class="text-primary">
                                                View File
                                            </a>
                                        </small>
                                    </div>
                                @endif
                            </div>

                            <div class="form-group form-check">
                                <input type="checkbox" name="is_active" id="is_active" class="form-check-input"
                                    {{ $announcement->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Update Announcement</button>
                                <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
