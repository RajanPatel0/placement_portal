@extends('dashboardLayouts.base')

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
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Placement Officers</h2>
            <a href="{{ route('admin.placement-officers.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Add New Officer
            </a>
        </div>

        

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>College</th>
                                <th>Status</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($placementOfficers as $officer)
                                <tr>
                                    <td>{{ $officer->id }}</td>
                                    <td>{{ $officer->name }}</td>
                                    <td>{{ $officer->email }}</td>
                                    <td>{{ $officer->phone }}</td>
                                    <td>{{ $officer->college_name ?? 'Not Assigned' }}</td>
                                    <td>
                                        @if ($officer->is_active)
                                            <span class="badge badge-success">Active</span>
                                        @else
                                            <span class="badge badge-danger">Inactive</span>
                                        @endif
                                    </td>
                                    <td>{{ date('d-m-Y', strtotime($officer->created_at)) }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('admin.placement-officers.edit', $officer->id) }}"
                                                class="btn btn-sm btn-info">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('admin.placement-officers.destroy', $officer->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Are you sure?')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            <form
                                                action="{{ route('admin.placement-officers.toggle-status', $officer->id) }}"
                                                method="POST" class="d-inline">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm btn-{{ $officer->is_active ? 'warning' : 'success' }}">
                                                    @if ($officer->is_active)
                                                        <i class="fas fa-ban"></i>
                                                    @else
                                                        <i class="fas fa-check"></i>
                                                    @endif
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    {{ $placementOfficers->links() }}
                </div>
            </div>
        </div>
    </div>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection
