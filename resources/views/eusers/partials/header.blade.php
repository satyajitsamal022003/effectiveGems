  <!-- Start breadcrumb section -->
  <section class="container">
      <div class="as_breadcrum_wrapper"
          style="background-image: url('assets/images/breadcrum-img-1.jpg');">
          <div class="row">
              <div class="col-lg-12 text-center">
                  <h1>My Account</h1>
                  <ul class="breadcrumb">
                      <li><a href="{{route('euser.dashboard')}}">Home</a></li>
                      <li>My Account</li>
                  </ul>
              </div>
          </div>
      </div>
  </section>
  <!-- End breadcrumb section -->

  <!--account header start-->
  <section class="section-padding pb-0">
      <div class="container">
          <div class="account-header">
              <ul>
                  <li><a href="{{route('euser.dashboard')}}" class="active"><i class="fa-solid fa-solid fa-gauge"></i> <span>Dashboard</span></a></li>
                  <li><a href="{{route('euser.myorderlist')}}"><i class="fa-solid fa-box"></i> <span>My Orders</span></a></li>
                  <li><a href="{{route('euser.wishlist')}}"><i class="fa-solid fa-heart"></i> <span>My Wishlist</span></a></li>
                  <li><a href="manage-address.html"><i class="fa-solid fa-circle-location-arrow"></i> <span>Manage Address</span></a></li>
                  <li><a href="my-profile.html"><i class="fa-solid fa-address-card"></i> <span> My Profile</span></a></li>
                  <li><a href="setting.html"><i class="fa-solid fa-gear"></i> <span>Settings</span></a></li>
                  <li>
                      <form action="{{ route('euser.logout') }}" method="POST" style="display: inline;">
                          @csrf
                          <button type="submit" style="background: none; border: none; color: blue; cursor: pointer;">
                              <i class="fa-solid fa-right-from-bracket"></i> <span>Logout</span>
                          </button>
                      </form>
                  </li>
              </ul>
          </div>
      </div>
  </section>
  <!--account header end-->