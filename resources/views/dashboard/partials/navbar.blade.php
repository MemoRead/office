 <!-- ======= Header ======= -->
 <header id="header" class="header fixed-top d-flex align-items-center">

     <!-- Logo -->
     <div class="d-flex align-items-center justify-content-between">
         <a href="/dashboard" class="logo d-flex align-items-center">
             <img src="/storage/photos/logo.png" alt="SetaraLogo">
         </a>
         <i class="bi bi-list toggle-sidebar-btn"></i>
     </div><!-- End Logo -->

     <!-- Icons Navigation -->
     <nav class="header-nav ms-auto">
         <ul class="d-flex align-items-center">

             @auth
                 <li class="nav-item dropdown pe-5">

                     <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#"
                         data-bs-toggle="dropdown">
                         @if (auth()->user()->photo)
                             <img class="img-fluid rounded-circle" src="/storage/{{ auth()->user()->photo }}"
                                 alt="profile">
                         @else
                             <img class="img-fluid rounded-circle" src="/storage/photos/blank.jpg" alt="profile">
                         @endif
                     </a><!-- End Profile Iamge Icon -->

                     <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
                         <li class="dropdown-header">
                             <h6>{{ auth()->user()->name }}</h6>
                             <span>{{ auth()->user()->role }}</span>
                         </li>
                         <li>
                             <hr class="dropdown-divider">
                         </li>

                         <li>
                             <a class="dropdown-item d-flex align-items-center"
                                 href="/dashboard/profile/{{ auth()->user()->id }}">
                                 <i class="bi bi-person"></i>
                                 <span>My Profile</span>
                             </a>
                         </li>

                         <li>
                             <hr class="dropdown-divider">
                         </li>
                         <li>

                         <li>
                             <form class="d-flex align-items-center" action="/logout" method="post">
                                 @csrf
                                 <button class="dropdown-item " type="submit">
                                     <i class="bi bi-box-arrow-right"></i>Logout
                                 </button>
                             </form>
                         </li>

                     </ul><!-- End Profile Dropdown Items -->
                 </li><!-- End Profile Nav -->
             @endauth

         </ul>
     </nav><!-- End Icons Navigation -->

 </header><!-- End Header -->

 <!-- ======= Sidebar ======= -->
 <aside id="sidebar" class="sidebar">

     <ul class="sidebar-nav" id="sidebar-nav">


         <!-- Dashboard -->
         <li class="nav-item">
             <a class="nav-link {{ Request::is('dashboard') ? '' : 'collapsed' }}" href="/dashboard">
                 <i class="bi bi-grid"></i>
                 <span>Dashboard</span>
             </a>
         </li><!-- End Dashboard Nav -->

         @if (Auth::user()->role == 'admin')
             <!-- Users -->
             <li class="nav-item">
                 <a class="nav-link {{ Request::is('dashboard/users*') ? '' : 'collapsed' }}" href="/dashboard/users">
                     <i class="bi bi-person-gear"></i>
                     <span>Users</span>
                 </a>
             </li><!-- End Users Nav -->
         @endif

         <li class="nav-heading">Office</li>

         @if (Auth::user()->role == 'admin')
             <!-- Members -->
             <li class="nav-item">
                 <a class="nav-link {{ Request::is('dashboard/members*') ? '' : 'collapsed' }}"
                     href="/dashboard/members">
                     <i class="bi bi-people"></i>
                     <span>Members</span>
                 </a>
             </li><!-- End Members Nav -->
         @endif

         <!-- Mails -->
         <li class="nav-item">
             <a class="nav-link {{ Request::is('dashboard/mails*') ? '' : 'collapsed' }}" data-bs-target="#mails-nav"
                 data-bs-toggle="collapse" href="#">
                 <i class="bi bi-mailbox"></i><span>Mails</span><i class="bi bi-chevron-down ms-auto"></i>
             </a>
             <ul id="mails-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                 <li>
                     <a class="nav-link {{ Request::is('dashboard/mails/incoming-mails*') ? '' : 'collapsed' }}"
                         href="/dashboard/mails/incoming-mails">
                         <i class="bi bi-circle"></i><span>Incoming Mails</span>
                     </a>
                 </li>
                 <li>
                     <a class="nav-link {{ Request::is('dashboard/mails/outgoing-mails*') ? '' : 'collapsed' }}"
                         href="/dashboard/mails/outgoing-mails">
                         <i class="bi bi-circle"></i><span>Outgoing Mails</span>
                     </a>
                 </li>
             </ul>
         </li><!-- End Mails Nav -->

         <!-- Archive -->
         <li class="nav-item">
             <a class="nav-link {{ Request::is('dashboard/archive*') ? '' : 'collapsed' }}"
                 data-bs-target="#archive-nav" data-bs-toggle="collapse" href="#">
                 <i class="bi bi-archive"></i><span>Archive</span><i class="bi bi-chevron-down ms-auto"></i>
             </a>
             <ul id="archive-nav" class="nav-content collapse " data-bs-parent="#sidebar-nav">
                 <li>
                     <a class="nav-link {{ Request::is('dashboard/archive/publications*') ? '' : 'collapsed' }}"
                         href="/dashboard/archive/publications">
                         <i class="bi bi-circle"></i><span>Publications</span>
                     </a>
                 </li>
             </ul>
         </li><!-- End Archive Nav -->

         @if (Auth::user()->role == 'admin')
             <!-- Experiences -->
             <li class="nav-item">
                 <a class="nav-link {{ Request::is('dashboard/experiences*') ? '' : 'collapsed' }}"
                     href="/dashboard/experiences">
                     <i class="bi bi-clipboard-check"></i>
                     <span>Comunity Experiences</span>
                 </a>
             </li><!-- End Experiences Page Nav -->
         @endif

         <li class="nav-heading">Pages</li>
        
        @if (Auth::user()->role == 'admin')
            <!-- Mixpost -->
            <li class="nav-item">
                <a class="nav-link {{ Request::is('/mixpost') ? '' : 'collapsed' }}" href="/mixpost" target="_blank">
                    <i class="bi bi-window"></i>
                    <span>Mixpost</span>
                </a>
            </li>
        @else
            <li class="nav-item">
                <a class="nav-link {{ Request::is('dashboard/social') ? '' : 'collapsed' }}" href="/dashboard/social">
                    <i class="bi bi-window"></i>
                    <span>Social CMS</span>
                </a>
            </li>
            <!-- Mixpost -->
        @endif
         <!-- My Profile -->
         <li class="nav-item">
             <a class="nav-link {{ Request::is('dashboard/profile*') ? '' : 'collapsed' }}"
                 href="/dashboard/profile/{{ auth()->user()->id }}">
                 <i class="bi bi-person"></i>
                 <span>Profile</span>
             </a>
         </li>
         <!-- End Profile Page Nav -->

     </ul>

 </aside><!-- End Sidebar-->