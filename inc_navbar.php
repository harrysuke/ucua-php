<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="#" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="img/<?php echo $_SESSION['company']['brandLogo']; ?>" alt="" height="24">
                    </span>
                    <span class="logo-lg">
                        <img src="img/<?php echo $_SESSION['company']['brandLogo']; ?>" alt="" height="24"> <span class="logo-txt"><?php echo $_SESSION['company']['brandName']; ?></span>
                    </span>
                </a>

                <a href="#" class="logo logo-light">
                    <span class="logo-sm">
                        <img src="img/<?php echo $_SESSION['company']['brandLogo']; ?>" alt="" height="24">
                    </span>
                    <span class="logo-lg">
                        <img src="img/<?php echo $_SESSION['company']['brandLogo']; ?>" alt="" height="24"> <span class="logo-txt"><?php echo $_SESSION['company']['brandName']; ?></span>
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 header-item" id="vertical-menu-btn">
                <i class="fa fa-fw fa-bars"></i>
            </button>

            <!-- App Search-->
            <?php if ($_SESSION['user']['AccessLevel'] == 9) { ?>
                <div class="app-search dropdown d-none d-lg-block">
                    <form class="" method="GET" action="default.php">
                        <div class="position-relative dropdown-toggle">
                            <input name="s" type="text" class="form-control" placeholder="Search...">
                            <button name="submit" type="submit" class="btn btn-soft-primary waves-effect waves-light">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>
                        </div>
                        <input type="hidden" name="page" value="search" />
                    </form>
                    <div class="dropdown-menu dropdown-menu-animated dropdown-lg" id="search-dropdown">
                        <h6 class="dropdown-header">Dashboard</h6>
                        <!-- item-->
                        <a href="default.php?page=analytics-pie" class="dropdown-item notify-item">
                            <i class="fa-solid fa-chart-pie"></i>
                            <span>Key performance indicators</span>
                        </a>

                        <!-- item-->
                        <a href="default.php?page=analytics-usage" class="dropdown-item notify-item">
                            <i class="fa-solid fa-chart-simple"></i>
                            <span>Drill through report</span>
                        </a>

                        <!-- item-->
                        <a href="default.php?page=leaderboard" class="dropdown-item notify-item">
                            <i class="fa-solid fa-ranking-star"></i>
                            <span>Leaderboard</span>
                        </a>
                        <hr class="dropdown-divider">
                        <h6 class="dropdown-header">User</h6>
                        <!-- item-->
                        <a href="default.php?page=profile&sid=<?php echo base64_encode($_SESSION['user']['staffId']); ?>" class="dropdown-item notify-item">
                            <i class="fa-regular fa-user"></i>
                            <span>User profile</span>
                        </a>
                        <!-- item-->
                        <a href="default.php?page=settings" class="dropdown-item notify-item">
                            <i class="fa-solid fa-sliders"></i>
                            <span>User profile settings</span>
                        </a>
                    </div>
                </div>
            <?php } else { ?>
                <button class="btn header-item navbar-brand fw-bold fs-2" href="#">
                    <?php echo $title; ?>
                </button>
            <?php } ?>
        </div>

        <div class="d-flex">
            <div class="dropdown d-inline-block d-lg-none ms-2">
                <button type="button" class="btn header-item" id="page-header-search-dropdown" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-search icon-lg">
                        <circle cx="11" cy="11" r="8"></circle>
                        <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                    </svg>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-search-dropdown">

                    <form class="p-3">
                        <div class="form-group m-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="Search ..."
                                    aria-label="Search Result">
                                <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item me-2" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i data-feather="plus" class="icon-lg"></i>
                </button>
                <ul class="dropdown-menu">
                    <li class="dropdown-header">Submission</li>
                    <li><a class="dropdown-item" href="default.php?page=uc_form"><i data-feather="file-plus" class="icon-lg"></i> <strong>New Unsafe Condition</strong></a></li>
                    <li><a class="dropdown-item" href="default.php?page=ua_form"><i data-feather="file-plus" class="icon-lg"></i> <strong>New Unsafe Action</strong></a></li>
                    <div class="dropdown-divider"></div>
                    <li class="dropdown-header">Management</li>
                    <li><a class="dropdown-item" href="default.php?page=manage_company"><i data-feather="folder" class="icon-lg"></i> New Company</a></li>
                    <li><a class="dropdown-item" href="default.php?page=manage_location"><i data-feather="map-pin" class="icon-lg"></i> New Location</a></li>
                    <?php if ($_SESSION['user']['AccessLevel'] > 2) { ?>
                        <li><a class="dropdown-item" href="default.php?page=manage_department"><i data-feather="folder" class="icon-lg"></i> New Department</a></li>
                        <li><a class="dropdown-item" href="default.php?page=manage_offence"><i data-feather="x-circle" class="icon-lg"></i> New Offense</a></li>
                    <?php } ?>
                    <?php if ($_SESSION['user']['AccessLevel'] == 9) { ?>
                        <!--div class="dropdown-divider"></div>
                        <li class="dropdown-header">User Management</li>
                        <li><a class="dropdown-item" href="default.php?page=admin_user_management"><i data-feather="user" class="icon-lg"></i> User</a></li>
                        <li><a class="dropdown-item" href="default.php?page=admin_user_roles"><i data-feather="sliders" class="icon-lg"></i> Role</a></li-->
                    <?php } ?>
                </ul>
            </div>
            <?php if ($_SESSION['user']['AccessLevel'] > 2) { ?>
                <div class="nav-link" href="#">
                    <button type="button" class="btn header-item me-2">
                        <a href="default.php?page=favorite" class="btn"><i data-feather="star" class="icon-lg"></i></a>
                    </button>
                </div>
            <?php } ?>
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item me-2" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    <i data-feather="help-circle" class="icon-lg"></i>
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="default.php?page=page&t=what's new"><i data-feather="gift" class="icon-lg"></i> What's New?</a></li>
                    <!--li><a class="dropdown-item" href="default.php?page=page&t=webinars"><i data-feather="video" class="icon-lg"></i> Webinars</a></li-->
                    <li><a class="dropdown-item" href="docs/UCUA_2.5_Guide_v3.pdf" target="_blank"><i data-feather="book-open" class="icon-lg"></i> Guides</a></li>
                    <div class="dropdown-divider"></div>
                    <li><a class="dropdown-item" href="mailto:icthelpdesk@johorport.com.my?subject=Bug&body=bug"><i data-feather="alert-triangle" class="icon-lg"></i> Report a bug</a></li>
                    <a class="dropdown-item" href="mailto:icthelpdesk@johorport.com.my?subject=Feedback&body=Feedback">
                        <i data-feather="mail" class="icon-lg"></i>
                        Send feedback
                    </a>
                    <li><a class="dropdown-item" href="mailto:icthelpdesk@johorport.com.my?subject=Suggest&body=feature"><i data-feather="message-circle" class="icon-lg"></i> Suggest a feature</a></li>
                </ul>
            </div>

            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon position-relative"
                    id="page-header-notifications-dropdown" data-bs-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    <i data-feather="bell" class="icon-lg"></i>
                    <span class="badge bg-danger rounded-pill"></span>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0"
                    aria-labelledby="page-header-notifications-dropdown">
                    <div class="p-3">
                        <div class="row align-items-center">
                            <div class="col">
                                <h6 class="m-0"> Notifications </h6>
                            </div>
                            <div class="col-auto">
                                <a href="#!" class="small text-reset text-decoration-underline"> Unread</a>
                            </div>
                        </div>
                    </div>
                    <div data-simplebar style="max-height: 230px;">
                        <?php echo showNotification($_SESSION['user']['staffId']); ?>
                    </div>
                    <div class="p-2 border-top d-grid">
                        <a class="btn btn-sm btn-link font-size-14 text-center" href="default.php?page=notification">
                            <span>See all notifications</span>
                        </a>
                    </div>
                </div>
            </div>

            <!--div class="dropdown d-none d-sm-inline-block">
                            <button type="button" class="btn header-item" id="mode-setting-btn">
                                <i data-feather="moon" class="icon-lg layout-mode-dark"></i>
                                <i data-feather="sun" class="icon-lg layout-mode-light"></i>
                            </button>
                        </div-->
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item" id="page-header-user-dropdown" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <!--img class="rounded-circle header-profile-user" src="img/blank.jpg" alt="Header Avatar"-->
                    <div class="rounded-circle header-profile-user float-start">
                        <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                            <?php echo substr(trim($_SESSION['user']['Name']), 0, 1); ?>
                        </span>
                    </div>
                    <span class="d-none d-xl-inline-block mt-2 ms-1 fw-medium text-start text-capitalize">
                        <span class="mb-0">
                            <?php echo $_SESSION['user']['Name']; ?>
                        </span>
                    </span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-end">
                    <!-- item-->
                    <div class="dropdown-header">
                        <p class="mb-0">
                            <?php echo $_SESSION['user']['rolename']; ?>
                        </p>
                        <p class="mb-0">
                            <?php echo strtolower($_SESSION['user']['Email']); ?>
                        </p>
                    </div>
                    <!--div class="dropdown-header">Account</div-->
                    <a class="dropdown-item"
                        href="default.php?page=profile&sid=<?php echo base64_encode($_SESSION['user']['staffId']); ?>">
                        <i class="bi bi-person"></i>
                        Profile</a>
                    <a class="dropdown-item" href="default.php?page=settings_user">
                        <i class="bi bi-pencil-square"></i>
                        Account</a>
                    <!--a class="dropdown-item" href="#">
                        <i class="bi bi-person-plus"></i>
                        Invitation</a-->
                    <div class="dropdown-divider"></div>
                    <div class="dropdown-header">Workspaces</div>
                    <a class="dropdown-item"
                        href="default.php?page=dashboard">
                        <i class="bi bi-check-lg"></i>
                        Production
                    </a>
                    <a class="dropdown-item"
                        href="../ucua300/default.php?page=dashboard">
                        <i class="bi bi-database"></i>
                        Staging
                    </a>
                    <div class="dropdown-divider"></div>
                    <div class="dropdown-header">Support</div>
                    <a class="dropdown-item" href="mailto:icthelpdesk@johorport.com.my?subject=Help&body=Please help">
                        <!-- https://feathericons.dev/?search=life-buoy&iconset=feather -->
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"
                            class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2">
                            <circle cx="12" cy="12" r="10" />
                            <circle cx="12" cy="12" r="4" />
                            <line x1="4.93" x2="9.17" y1="4.93" y2="9.17" />
                            <line x1="14.83" x2="19.07" y1="14.83" y2="19.07" />
                            <line x1="14.83" x2="19.07" y1="9.17" y2="4.93" />
                            <line x1="14.83" x2="18.36" y1="9.17" y2="5.64" />
                            <line x1="4.93" x2="9.17" y1="19.07" y2="14.83" />
                        </svg>
                        Get help
                    </a>
                    <a class="dropdown-item" href="mailto:icthelpdesk@johorport.com.my?subject=Feedback&body=Feedback">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"
                            class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round"
                            stroke-linejoin="round" stroke-width="2">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                        </svg>
                        Send feedback
                    </a>
                    <div class="dropdown-divider"></div>
                    <div class="dropdown-header">Download</div>
                    <a class="dropdown-item text-success" href="download/ucua Setup 1.0.0.exe">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity">
                            <path d="M3 17v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3"></path>
                            <polyline points="8 12 12 16 16 12"></polyline>
                            <line x1="12" y1="2" x2="12" y2="16"></line>
                        </svg>
                        Download desktop app</a>
                    <a class="dropdown-item text-success" href="apk/ucua.v012.alpha.apk">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity">
                            <path d="M3 17v3a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-3"></path>
                            <polyline points="8 12 12 16 16 12"></polyline>
                            <line x1="12" y1="2" x2="12" y2="16"></line>
                        </svg>
                        Download mobile app</a>
                    <div class="dropdown-divider"></div>
                    <!--a class="dropdown-item" href="default.php?page=lockScreen&lock=1"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>  Lock Screen</a-->
                    <?php if ($_SESSION['user']['AccessLevel'] == 9) { ?>
                        <a class="dropdown-item" href="default.php?page=settings_overview">
                            <i data-feather="settings" class="icon-sm fa-spin"></i> Settings</a>
                    <?php } ?>
                    <a class="dropdown-item text-danger" href="default.php?page=logout">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            class="feather feather-activity">
                            <path d="M18.36 6.64a9 9 0 1 1-12.73 0"></path>
                            <line x1="12" y1="2" x2="12" y2="12"></line>
                        </svg> Log out</a>
                </div>
            </div>

        </div>
    </div>
</header>