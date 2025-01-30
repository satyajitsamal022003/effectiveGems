 <!-- Sidebar -->
 <div class="sidebar" id="sidebar">
     <div class="sidebar-inner slimscroll">
         <div id="sidebar-menu" class="sidebar-menu">
             <ul>
                 <li class="active">
                     <a href="{{ route('admin.dashboard') }}"><i class="fa-solid fa-house"></i>
                         <span>Dashboard</span></a>
                 </li>
                 <li class="submenu">
                     <a href="#"><i class="fa-solid fa-passport"></i> <span>CMS</span>
                         <span class="menu-arrow"></span></a>
                     <ul>
                         <li>
                             <a href="{{ route('admin.listactivation') }}">Activations</a>
                         </li>
                         <li>
                             <a href="{{ route('admin.listcouriertype') }}">Courier Types</a>
                         </li>
                         <li>
                             <a href="{{ route('admin.listcertification') }}">Certifications</a>
                         </li>
                     </ul>
                 </li>
                 <li class="">
                     <a href="#"><i class="fa-solid fa-question-circle"></i>
                         <span>FAQ</span></a>
                 </li>
                 <li class="submenu">
                     <a href="product.html"><i class="fa-solid fa-passport"></i> <span>Product</span>
                         <span class="menu-arrow"></span></a>
                     <ul>
                         <li>
                             <a href="{{ route('admin.listcat') }}">Category</a>
                         </li>
                         <li>
                             <a href="{{ route('admin.listsubcat') }}">Sub Category</a>
                         </li>
                         <li>
                             <a href="{{ route('admin.listproduct') }}">Products</a>
                         </li>
                     </ul>
                 </li>

                 <li class="">
                     <a href="{{route('admin.listtestimonial')}}"><i class="fa-solid fa-users-line"></i><span>Testimonial</span></a>
                 </li>
                 <li class="">
                     <a href="/admin/redirects"><i class="fa-solid fa-users-line"></i><span>Redirects</span></a>
                 </li>
                 <li class="">
                    <a href="/admin/coupons"><i class="fa-solid fa-cart-shopping"></i><span>Coupons</span></a>
                </li>
                 <li class="submenu">
                     <a href="product.html"><i class="fa-solid fa-passport"></i> <span>Orders</span>
                         <span class="menu-arrow"></span></a>
                     <ul>

                         <li class="">
                             <a href="/admin/order"><i class="fa-solid fa-cart-shopping"></i><span>Order</span></a>
                         </li>
                         <li>
                             <a href="{{ route('admin.order.pendingOrdersList') }}"><i
                                     class="fa-solid fa-cart-shopping"></i>Payment
                                 failed</a>
                         </li>
                     </ul>
                 </li>


                 <li class="submenu">
                     <a href="#"><i class="fa-solid fa-gear"></i> <span>System</span> <span
                             class="menu-arrow"></span></a>
                     <ul>
                         <li>
                             <a href="{{route('admin.banners.index')}}">Banner</a>
                         </li>
                         <li>
                             <a class="" href="#">Pages</a>
                         </li>
                         <li>
                             <a class="" href="#">Order status</a>
                         </li>
                         <li>
                             <a href="{{ route('admin.settings') }}">Setting</a>
                         </li>
                     </ul>
                 </li>
                 <li>
                     <a href="#" class="dropdown-item"
                         onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                         <i class="fa-solid fa-right-from-bracket"></i>
                         <span>Logout</span>
                     </a>

                     <!-- Hidden form to handle logout -->
                     <form id="logout-form" action="{{ route('logout') }}" method="POST">
                         @csrf
                     </form>
                 </li>
             </ul>
         </div>
     </div>
 </div>
 <!-- /Sidebar -->
