@extends('dashboardLayouts.base')

@section('title', 'Announcement')

@section('content')

    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Create New Announcement/Notice</h3>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('admin.announcements.store') }}" method="POST" enctype="multipart/form-data">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">Title *</label>
                                        <input type="text" name="title" id="title" class="form-control"
                                            value="{{ old('title') }}" required>
                                        @error('title')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="type">Type *</label>
                                        <select name="type" id="type" class="form-control" required>
                                            <option value="announcement"
                                                {{ old('type') == 'announcement' ? 'selected' : '' }}>Announcement</option>
                                            <option value="notice" {{ old('type') == 'notice' ? 'selected' : '' }}>Notice
                                            </option>
                                        </select>
                                        @error('type')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="priority">Priority *</label>
                                        <select name="priority" id="priority" class="form-control" required>
                                            <option value="normal" {{ old('priority') == 'normal' ? 'selected' : '' }}>
                                                Normal</option>
                                            <option value="important"
                                                {{ old('priority') == 'important' ? 'selected' : '' }}>Important</option>
                                            <option value="urgent" {{ old('priority') == 'urgent' ? 'selected' : '' }}>
                                                Urgent</option>
                                        </select>
                                        @error('priority')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="external_link">External Link</label>
                                        <input type="url" name="external_link" id="external_link" class="form-control"
                                            value="{{ old('external_link') }}" placeholder="https://example.com">
                                        @error('external_link')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="publish_date">Publish Date *</label>
                                        <input type="datetime-local" name="publish_date" id="publish_date"
                                            class="form-control"
                                            value="{{ old('publish_date', now()->format('Y-m-d\TH:i')) }}" required>
                                        @error('publish_date')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="expiry_date">Expiry Date (Optinal)</label>
                                        <input type="datetime-local" name="expiry_date" id="expiry_date"
                                            class="form-control" value="{{ old('expiry_date') }}">
                                        @error('expiry_date')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="content">Content</label>
                                <textarea name="content" id="content" class="form-control" rows="5">{{ old('content') }}</textarea>
                                @error('content')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="file">Attachment (PDF/DOC)</label>
                                <input type="file" name="file" id="file" class="form-control-file">
                                <small class="form-text text-muted">Max file size: 10MB. Allowed types: PDF, DOC,
                                    DOCX</small>
                                @error('file')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="form-group form-check">
                                <input type="checkbox" name="is_active" id="is_active" class="form-check-input"
                                    {{ old('is_active', true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">Active</label>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">Create Announcement</button>
                                <a href="{{ route('admin.announcements.index') }}" class="btn btn-secondary">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
