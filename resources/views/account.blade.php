@extends('layouts.base')
@section('content')

<style>
    /* Anchor menu item styles */
    .menu-item {
        display: flex;
        align-items: center;
        gap: 14px;
        padding: 14px 24px;
        text-decoration: none;
        color: inherit;
        transition: background-color .18s ease, color .18s ease;
        border-left: 4px solid transparent;
        outline: none;
    }

    .menu-item:hover {
        background-color: rgba(0, 0, 0, 0.04);
    }



    /* keyboard focus */
    .menu-item:focus {
        box-shadow: 0 0 0 3px rgba(61, 107, 105, 0.12);
    }

    .menu-item svg {
        width: 22px;
        height: 22px;
        stroke: #3d6b69;
        flex-shrink: 0;
    }

    .menu-item[aria-current="page"] svg {
        stroke: #1e4b48;
    }

    /* push logout to bottom */
    .logout {
        margin-top: auto;
        border-top: 1px solid rgba(0, 0, 0, 0.05);
        padding-top: 10px;
    }

    .logout svg {
        stroke: #d55b4a;
    }

    .logout {
        color: #d55b4a;
    }
</style>

<div style="text-decoration: none; padding-top: 30px;">
    <h1 style="text-align: center; color: #1e3f4bff; ">Account</h1>
    <a class="menu-item" href="/profile" aria-current="page" style="text-decoration: none;">
        <!-- profile icon -->
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
            <circle cx="12" cy="7" r="4"></circle>
        </svg>
        <span>My Profile</span>
    </a>

    <!-- <a class="menu-item" href="/settings" style="text-decoration: none;">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
                <circle cx="12" cy="12" r="3"></circle>
                <path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 ..."></path>
            </svg>
            <span>Settings</span>
        </a> -->
 <a class="menu-item" href="{{ route('forget.password.request') }}" style="text-decoration: none;">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2"
        stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
        <path d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
    </svg>
    <span>Reset Password</span>
</a>

    <a class="menu-item" href="/privacy" style="text-decoration: none;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="16" x2="12" y2="12"></line>
            <line x1="12" y1="8" x2="12.01" y2="8"></line>
        </svg>
        <span>Privacy Policy</span>
    </a>

   

    <a class="menu-item" href="{{route('helpCenter');}}" style="text-decoration: none;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
            <polyline points="15 3 21 3 21 9"></polyline>
            <line x1="10" y1="14" x2="21" y2="3"></line>
        </svg>
        <span>Help Center</span>
    </a>

    <a class="menu-item" href="https://ptu.ac.in/placements/training-placements-and-industrial-interface/" style="text-decoration: none;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
        </svg>
        <span>About</span>
    </a>



    <a class="menu-item logout" href="/logout" style="text-decoration: none;">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" stroke="currentColor" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24" aria-hidden="true">
            <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
            <polyline points="16 17 21 12 16 7"></polyline>
            <line x1="21" y1="12" x2="9" y2="12"></line>
        </svg>
        <span>Logout</span>
    </a>
</div>


@endsection