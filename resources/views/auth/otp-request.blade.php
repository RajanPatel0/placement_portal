@extends('layouts.base')
@section('content')
<div class="center-wrapper">
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
    <div class="content pt-5">

        <div class="otp-request-card ">
            <h2>Registration with email</h2>
            <form action="{{ route('otp.registered') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                <button type="submit" class="btn w-100">Send OTP</button>
            </form>
        </div>
    </div>
</div>

<style>
    .otp-request-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fc 100%);
        border-radius: 16px;
        padding: 30px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        max-width: 450px;
        margin: 140px auto;
        border-left: 5px solid #4361ee;
        animation: fadeIn 0.6s ease-out;
    }

    .otp-request-card h2 {
        text-align: center;
        margin-bottom: 25px;
        font-size: 1.8rem;
        font-weight: 700;
        background: linear-gradient(135deg, #3a0ca3 0%, #4361ee 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .otp-request-card .form-group {
        margin-bottom: 20px;
    }

    .otp-request-card label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #2d3748;
    }

    .otp-request-card .form-control {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background-color: #f8f9fc;
    }

    .otp-request-card .form-control:focus {
        outline: none;
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        background-color: #ffffff;
    }

    .otp-request-card .btn {
        background: linear-gradient(135deg, #4361ee 0%, #3a0ca3 100%);
        color: white;
        border: none;
        border-radius: 10px;
        padding: 14px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-top: 10px;
        box-shadow: 0 4px 12px rgba(67, 97, 238, 0.3);
    }

    .otp-request-card .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(67, 97, 238, 0.4);
    }

    .otp-request-card .btn:active {
        transform: translateY(0);
    }

    .otp-request-card .w-100 {
        width: 100%;
    }

    /* Animation */
    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(20px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* Responsive adjustments */
    @media (max-width: 480px) {
        .otp-request-card {
            margin: 20px;
            padding: 20px;
        }

        .otp-request-card h2 {
            font-size: 1.5rem;
        }
    }
</style>

@endsection