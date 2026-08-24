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
        <div class="row">
            <div class="col-md-8 mx-auto">
                <div class="card">
                    <div class="card-header">
                        <h4>Edit Placement Officer</h4>
                    </div>
                    <div class="card-body">
                       

                        <form action="{{ route('admin.placement-officers.update', $officer->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <div class="form-group">
                                <label>Full Name *</label>
                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $officer->name) }}" required>
                            </div>

                            <div class="form-group">
                                <label>Email Address *</label>
                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $officer->email) }}" required>
                            </div>

                            <div class="form-group">
                                <label>Phone Number *</label>
                                <input type="text" name="phone" class="form-control"
                                    value="{{ old('phone', $officer->phone) }}" required>
                            </div>

                            <div class="form-group">
                                <label>Assign College *</label>
                                <select name="college_id" class="form-control" required>
                                    <option value="">Select College</option>
                                    @foreach ($colleges as $college)
                                        <option value="{{ $college->id }}"
                                            {{ old('college_id', $officer->college_id) == $college->id ? 'selected' : '' }}>
                                            {{ $college->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>


                            <div class="mt-3">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update Placement Officer
                                </button>
                                <a href="{{ route('admin.placement-officers.index') }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
@endsection
