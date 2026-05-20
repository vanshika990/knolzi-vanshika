<section class="login-page mt-5 mb-5">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <nav class="card mb-3">
                    <div class="card-body">
                        <div class="left-profile-menu">
                            <ul class="nav flex-column">
                                @can('view-my-created-course')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('viewmycreatedcourse') ? 'active' : '' }}" href="{{ route('viewmycreatedcourse') }}">View Courses</a>
                                </li>
                                @endcan
                                @can('view-own-author')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('myauthor') ? 'active' : '' }}" href="{{ route('myauthor') }}">My Authors</a>
                                </li>
                                @endcan
                                @can('view-subscribe-course-org')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('org-my-course') ? 'active' : '' }}" href="{{ route('org-my-course') }}">View my course</a>
                                </li>
                                @endcan

                                @can('view-my-user-org')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('viewmyuser') ? 'active' : '' }}" href="{{ route('viewmyuser') }}">View my user</a>
                                </li>
                                @endcan

                                @can('view-invitation-org')
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('org-my-invitation') ? 'active' : '' }}" href="{{ route('org-my-invitation') }}">My Invitation</a>
                                </li>
                                @endcan
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
    