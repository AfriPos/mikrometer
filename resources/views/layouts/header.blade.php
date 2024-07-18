<!-- Navbar -->
<nav class="card navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-sm border-radius-xl position-sticky mt-4 left-auto top-1 z-index-sticky"
    id="navbarBlur" navbar-scroll="true">
    <div class="container-fluid py-1 px-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;">Pages</a></li>
                @php
                    $segments = request()->segments();
                @endphp
                @foreach ($segments as $segment)
                    <li class="breadcrumb-item text-sm text-dark">{{ ucfirst(str_replace('pppoe', 'PPPoE', $segment)) }}
                    </li>
                @endforeach
            </ol>

        </nav>

        <div class="navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
            <div class="ms-md-auto pe-md-3 d-flex align-items-center">
            </div>
            <ul class="navbar-nav  justify-content-end">
                <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                        <div class="sidenav-toggler-inner">
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                            <i class="sidenav-toggler-line"></i>
                        </div>
                    </a>
                </li>
                <li class="nav-item px-3 d-flex align-items-center">
                    <a href="javascript:;" class="nav-link text-body p-0" data-bs-toggle="offcanvas"
                        data-bs-target="#offcanvasRight" aria-controls="offcanvasRight" data-bs-toggle="tooltip"
                        data-bs-placement="bottom" title="Notifications">
                        <i class="fa-solid fa-bell"></i>
                    </a>
                </li>
                <li class="nav-item px-3 d-flex align-items-center">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <a href="{{ route('logout') }}"
                           onclick="event.preventDefault(); this.closest('form').submit();"
                           class="nav-link text-body p-0"
                           data-bs-toggle="tooltip"
                           data-bs-placement="bottom"
                           title="Logout">
                            <i class="fa-solid fa-right-from-bracket"></i>
                        </a>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasRightLabel">Notifications</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <div class="notification-item mb-3">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-info-circle text-info me-3"></i>
                <div>
                    <h6 class="mb-1">System Update</h6>
                    <p class="text-sm text-muted mb-0">A new system update is available. Please restart your device.</p>
                    <small class="text-muted">2 hours ago</small>
                </div>
            </div>
        </div>
        <div class="notification-item mb-3">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-exclamation-triangle text-warning me-3"></i>
                <div>
                    <h6 class="mb-1">Account Security</h6>
                    <p class="text-sm text-muted mb-0">We detected a login attempt from a new device. Was this you?</p>
                    <small class="text-muted">5 hours ago</small>
                </div>
            </div>
        </div>
        <div class="notification-item mb-3">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-check-circle text-success me-3"></i>
                <div>
                    <h6 class="mb-1">Payment Successful</h6>
                    <p class="text-sm text-muted mb-0">Your payment of $99.99 has been processed successfully.</p>
                    <small class="text-muted">1 day ago</small>
                </div>
            </div>
        </div>
        <div class="notification-item">
            <div class="d-flex align-items-center">
                <i class="fa-solid fa-envelope text-primary me-3"></i>
                <div>
                    <h6 class="mb-1">New Message</h6>
                    <p class="text-sm text-muted mb-0">You have received a new message from John Doe.</p>
                    <small class="text-muted">3 days ago</small>
                </div>
            </div>
        </div>
    </div>
</div>
