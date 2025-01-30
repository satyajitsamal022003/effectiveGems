  <!-- Start breadcrumb section -->
  @php
  $breadcrumbs = [
  'euser.dashboard' => 'Dashboard',
  'euser.myorderlist' => 'My Orders',
  'euser.wishlist' => 'My Wishlist',
  'euser.manageaddress' => 'Manage Address',
  'euser.myProfile' => 'My Profile',
  'euser.setting' => 'Settings',
  ];

  $currentRoute = Route::currentRouteName();
  $pageTitle = $breadcrumbs[$currentRoute] ?? 'My Account';
  @endphp

  <section class="container">
      <div class="as_breadcrum_wrapper" style="background-image: url('assets/images/breadcrum-img-1.jpg');">
          <div class="row">
              <div class="col-lg-12 text-center">
                  <h1>{{ $pageTitle }}</h1>
                  <ul class="breadcrumb">
                      <li><a href="{{ route('euser.dashboard') }}">Home</a></li>
                      <li>{{ $pageTitle }}</li>
                  </ul>
              </div>
          </div>
      </div>
  </section>

  <!-- End breadcrumb section -->

  <!--account header start-->
  <section class="section-padding pb-0">
      <div class="container">

          <div class="user-menu"><i class="fas fa-bars"></i> Dashboard</div>

          <div class="account-header">
              <ul>
                  <li>
                      <a href="{{ route('euser.dashboard') }}"
                          class="{{ Request::routeIs('euser.dashboard') ? 'active' : '' }}">
                          <i class="fa-solid fa-gauge"></i> <span>Dashboard</span>
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('euser.myorderlist') }}"
                          class="{{ Request::routeIs('euser.myorderlist') ? 'active' : '' }}">
                          <i class="fa-solid fa-box"></i> <span>My Orders</span>
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('euser.wishlist') }}"
                          class="{{ Request::routeIs('euser.wishlist') ? 'active' : '' }}">
                          <i class="fa-solid fa-heart"></i> <span>My Wishlist</span>
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('euser.manageaddress') }}" class="{{ Request::routeIs('euser.manageaddress') ? 'active' : '' }}">
                          <i class="fa-solid fa-circle-location-arrow"></i> <span>Manage Address</span>
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('euser.myProfile') }}"
                          class="{{ Request::routeIs('euser.myProfile') ? 'active' : '' }}">
                          <i class="fa-solid fa-address-card"></i> <span>My Profile</span>
                      </a>
                  </li>
                  <li>
                      <a href="{{ route('euser.setting') }}"
                          class="{{ Request::routeIs('euser.setting') ? 'active' : '' }}">
                          <i class="fa-solid fa-gear"></i> <span>Settings</span>
                      </a>
                  </li>
                  <li>
                      <form action="{{ route('euser.logout') }}" method="POST" style="display: inline;">
                          @csrf
                          <button type="submit" class="logout-btn">
                              <i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span>
                          </button>
                      </form>
                  </li>
              </ul>
          </div>
      </div>
  </section>

  <!--account header end-->