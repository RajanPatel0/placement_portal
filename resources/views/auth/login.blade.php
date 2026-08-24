@extends('layouts.base')
@section('content')
<div class="center-wrapper">

    {{-- Notifications --}}
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

    {{-- Login Card --}}
    <div class="login-card">
        <h2>Login</h2>
        <form action="{{ route('logined') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <button type="submit" class="btn w-100">Login</button>
        </form>

        <div class="mt-3 text-center">
            <a href="{{ route('forget.password.request') }}">Forget Password?</a>
        </div>

        <p class="text-center mt-3">
            Don't have an account? <a href="{{ route('otp.request') }}">Register here</a>
        </p>
    </div>
</div>

{{-- Styles --}}
<style>
    .center-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
        padding: 2px;
    }

    /* Notification styles (same as your previous card) */
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
        right: .5px;
        background: transparent;
        border: none;
        font-size: 1.2rem;
        font-weight: bold;
        color: #fff;
        cursor: pointer;
    }

    /* Login Card */
    .login-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fc 100%);
        border-radius: 16px;
        padding: 40px 30px;
        max-width: 450px;
        width: 100%;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        animation: fadeIn 0.6s ease-out;
        border-left: 5px solid #4361ee;
    }

    .login-card h2 {
        text-align: center;
        margin-bottom: 30px;
        font-size: 2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #3a0ca3 0%, #4361ee 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .login-card .form-group {
        margin-bottom: 20px;
    }

    .login-card label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #2d3748;
    }

    .login-card .form-control {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background-color: #f8f9fc;
    }

    .login-card .form-control:focus {
        outline: none;
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        background-color: #ffffff;
    }

    .login-card .btn {
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

    .login-card .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(67, 97, 238, 0.4);
    }

    .login-card .btn:active {
        transform: translateY(0);
    }

    /* Animations */
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

    /* Responsive */
    @media (max-width: 480px) {
        .login-card {
            padding: 25px;
            margin: 20px;
        }

        .login-card h2 {
            font-size: 1.6rem;
        }
    }
</style>

{{-- Scripts --}}
<script>
    setTimeout(() => {
        document.querySelectorAll('.notification').forEach(notification => {
            notification.style.opacity = '0';
            setTimeout(() => {
                notification.style.display = 'none';
            }, 1000);
        });
    }, 10000);

    function closeNotification(id) {
        const element = document.getElementById(id);
        if (element) {
            element.style.opacity = '0';
            setTimeout(() => {
                element.style.display = 'none';
            }, 1000);
        }
    }
</script>
@endsection