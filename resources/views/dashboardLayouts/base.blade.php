@include('dashboardLayouts.meta')
@include('dashboardLayouts.navbar')
@include('dashboardLayouts.sidebar')

<div class="content-wrapper">
    @yield('content')
</div>

@include('dashboardLayouts.footer')

