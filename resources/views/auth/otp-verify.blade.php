@extends('layouts.base')

@section('content')

<div class="otp-verify-card-bg">
    <div class="otp-verify-card">
        <h2 class="text-center">Verify OTP & Complete Registration</h2>

        @if (session('error'))
        <div class="alert">{{ session('error') }}</div>
        @endif

        <form action="{{ route('otp.verifed') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="otp">Enter Your OTP</label>
                <input type="text" name="otp" id="otp" class="form-control" required>
                @error('otp')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="name">Enter Your Full Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
                @error('name')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone">Enter Your Phone</label>
                <input type="text" name="phone" id="phone" class="form-control" required>
                @error('phone')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group password-wrapper">
                <label for="password">Enter Your Password</label>
                <div class="password-field">
                    <input type="password" name="password" id="password" class="form-control" required>
                    <span class="toggle-password" onclick="togglePassword()">
                        <i class="fa fa-eye" id="eyeIcon"></i>
                    </span>
                </div>
                @error('password')
                <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn w-100">Complete Registration</button>
        </form>
    </div>
</div>

<style>
    .otp-verify-card-bg {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 80vh;
        background: linear-gradient(135deg, #f6f9ff 0%, #eef2fb 100%);
    }

    .otp-verify-card {
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fc 100%);
        border-radius: 16px;
        padding: 35px;
        max-width: 480px;
        width: 100%;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        border-left: 5px solid #4361ee;
        animation: fadeIn 0.6s ease-out;
    }

    .otp-verify-card h2 {
        text-align: center;
        margin-bottom: 25px;
        font-size: 1.8rem;
        font-weight: 700;
        background: linear-gradient(135deg, #3a0ca3 0%, #4361ee 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .otp-verify-card .form-group {
        margin-bottom: 20px;
        position: relative;
    }

    .otp-verify-card label {
        display: block;
        margin-bottom: 8px;
        font-weight: 600;
        color: #2d3748;
    }

    .otp-verify-card .form-control {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background-color: #f8f9fc;
    }

    .otp-verify-card .form-control:focus {
        outline: none;
        border-color: #4361ee;
        box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        background-color: #ffffff;
    }

    /* Password eye icon */
    .password-field {
        position: relative;
        display: flex;
        align-items: center;
    }

    .toggle-password {
        position: absolute;
        right: 14px;
        cursor: pointer;
        color: #718096;
        font-size: 1.1rem;
        transition: color 0.3s ease;
    }

    .toggle-password:hover {
        color: #4361ee;
    }

    .otp-verify-card .btn {
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

    .otp-verify-card .btn:hover {
        transform: translateY(-2px);
        box-shadow: 0 6px 15px rgba(67, 97, 238, 0.4);
    }

    .otp-verify-card .btn:active {
        transform: translateY(0);
    }

    .otp-verify-card .w-100 {
        width: 100%;
    }

    .alert {
        background: linear-gradient(135deg, #e53935, #ef5350);
        color: white;
        padding: 12px 15px;
        border-radius: 8px;
        text-align: center;
        margin-bottom: 15px;
        font-weight: 500;
        box-shadow: 0 2px 6px rgba(229, 57, 53, 0.3);
    }

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

    @media (max-width: 480px) {
        .otp-verify-card {
            margin: 20px;
            padding: 25px;
        }

        .otp-verify-card h2 {
            font-size: 1.5rem;
        }
    }
</style>

<!-- Font Awesome -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<script>
    function togglePassword() {
        const passwordInput = document.getElementById("password");
        const eyeIcon = document.getElementById("eyeIcon");
        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            eyeIcon.classList.remove("fa-eye");
            eyeIcon.classList.add("fa-eye-slash");
        } else {
            passwordInput.type = "password";
            eyeIcon.classList.remove("fa-eye-slash");
            eyeIcon.classList.add("fa-eye");
        }
    }

       // Phone validation - accepts +91XXXXXXXXXX or XXXXXXXXXX
    document.querySelector('form')?.addEventListener('submit', function(e) {
        const phoneInput = document.getElementById('phone');
        let phoneNumber = phoneInput.value.replace(/\D/g, ''); // Remove all non-digits
        
        // Remove country code if present (91 or 91 after +)
        if (phoneNumber.startsWith('91') && phoneNumber.length === 12) {
            phoneNumber = phoneNumber.substring(2); // Remove "91"
        }
        
        // Validate: Should be exactly 10 digits starting with 6-9
        const isValid = /^[6-9]\d{9}$/.test(phoneNumber);
        
        if (!isValid) {
            e.preventDefault();
            alert('Please enter a valid Indian phone number (10 digits starting with 6-9)\nExamples: 9876543210 or +919876543210');
            phoneInput.focus();
            phoneInput.select();
        }
    });

</script>

@endsection
