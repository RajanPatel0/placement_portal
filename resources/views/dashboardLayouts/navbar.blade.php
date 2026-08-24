  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
          <li class="nav-item">
              <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
          </li>
              </li>
          <li class="nav-item  d-sm-inline-block">
              @if (Auth::user()->role == 'admin')
                  <a href="{{ route('adminDashboard.index') }}" class="nav-link">Home</a>
              @elseif(Auth::user()->role == 'company')
                  <a href="{{ route('company.welcome') }}" class="nav-link">Home</a>
              @elseif (Auth::user()->role == 'user')
                  <a href="{{ route('index') }}" class="nav-link">Home</a>
              @endif
          </li>
          {{-- <li class="nav-item  d-sm-inline-block">
              <a href="{{route('register')}}" class="nav-link">Register</a>
          </li> --}}
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">



          <!-- Notifications Dropdown Menu -->
          {{-- <li class="nav-item dropdown">
        <a class="nav-link" data-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <span class="badge badge-warning navbar-badge">15</span>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
          <span class="dropdown-item dropdown-header">15 Notifications</span>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-envelope mr-2"></i> 4 new messages
            <span class="float-right text-muted text-sm">3 mins</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-users mr-2"></i> 8 friend requests
            <span class="float-right text-muted text-sm">12 hours</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> 3 new reports
            <span class="float-right text-muted text-sm">2 days</span>
          </a>
          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li> --}}

          <li class="nav-item">
              <!-- Profile Dropdown -->
          <li class="nav-item dropdown">
              @auth
                  <!-- Show Profile Dropdown for Authenticated Users -->
                  <a class="nav-link d-flex align-items-center" href="#" id="profileDropdown" role="button"
                      data-bs-toggle="dropdown" aria-expanded="false">

                    @if (Auth::check() && Auth::user()->profile_picture)
                     <img src="{{ asset('/' . Auth::user()->profile_picture) }}" alt="{{ Auth::user()->role }}"
                      class="img-fluid profile-img"
                        style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                    
                @else
                   <i class="bi bi-person-circle img-fluid rounded-circle" 
                    style="font-size: 30px; width: 40px; height: 40px;"></i>

                @endif




                  </a>
                  <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profileDropdown">

                      <a class="dropdown-item" href="">
                          {{ Auth::user()->full_name }}
                          <br>
                          {{ Auth::user()->phone }}
                      </a>
                      <li>
                          <a class="dropdown-item" href="">
                              <i class="bi bi-person-circle me-2"></i>Profile
                          </a>
                      </li>
                      <li>
                          <a class="dropdown-item" href="">
                              <i class="bi bi-gear me-2"></i>Settings
                          </a>
                      </li>
                      <li>
                          <hr class="dropdown-divider">
                      </li>
                      <li>
                          <a class="dropdown-item" href="{{route('logout')}}">
                              <i class="bi bi-gear me-2"></i>Logout
                          </a>


                      </li>
                  </ul>
              @else
                  <!-- Show Login Link for Guests -->
                  <a class="nav-link mr-2" href="{{route('login')}}">
                      <b>Login</b>
                  </a>
              @endauth
          </li>



          <li class="nav-item">
              <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                  <i class="fas fa-expand-arrows-alt"></i>
              </a>
          </li>

      </ul>
  </nav>
  <!-- /.navbar -->
