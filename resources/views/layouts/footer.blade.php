<style>
    .footer-text {
        font-weight: 500;
        color: #5a5a5a;
    }

    .footer-text a {
        text-decoration: none;
        transition: color 0.3s ease;
    }

    .footer-text .ptu {
        color: #0077b6;
    }

    .footer-text .dev {
        color: #009688;
    }

    .footer-text a:hover {
        opacity: 0.8;
    }
</style>



<nav class="bottom-nav">
    <a href="{{ route('home') }}" class="nav-item " style="text-decoration:none;">
        <i class="fas fa-home nav-icon"></i>
        <span>Home</span>
    </a>
    <a href="{{ route('index') }}" class="nav-item" style="text-decoration:none;">
        <i class="fas fa-graduation-cap nav-icon"></i>
        <span>Drive</span>
    </a>

    @if (Auth::check())
        <a href="{{ route('applied.placements') }}" class="nav-item" style="text-decoration:none;">
            <i class="fas fa-file-alt nav-icon"></i>
            <span>Applications</span>
        </a>
    @else
        <a href="{{ route('login') }}" class="nav-item" style="text-decoration:none;">
            <i class="fas fa-file-alt nav-icon"></i>
            <span>Applications</span>
        </a>
    @endif
    @if (Auth::check())
        <a href="{{ route('accounts') }}" class="nav-item" style="text-decoration:none;">
            <i class="fas fa-user nav-icon"></i>
            <span>Account</span>
        </a>
    @else
        <a href="{{ route('login') }}" class="nav-item" style="text-decoration:none;">
            <i class="fas fa-user nav-icon"></i>
            <span>Account</span>
        </a>
    @endif
</nav>
<style>
    /* Bottom Navigation with Gradient */
    .bottom-nav {
        position: fixed;
        left: 0;
        bottom: 0;
        width: 100%;
        background: linear-gradient(135deg, #ffffff 0%, #f8f9fc 100%);
        display: flex;
        justify-content: space-around;
        padding: 6px 0;
        border-top: 1px solid #e2e8f0;
        box-shadow: 0 -4px 12px rgba(0, 0, 0, 0.05);
        z-index: 1000;
    }

    .nav-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 5px;
        color: #6c757d;
        font-size: 0.9rem;
        cursor: pointer;
        transition: color 0.3s;
    }

    .nav-item.active {
        color: #4361ee;
    }

    .nav-icon {
        font-size: 1.2rem;
    }
</style>
