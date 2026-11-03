<div class="vertical-menu">
    <div data-simplebar="init" class="h-100">
        <div class="simplebar-wrapper" style="margin: 0px;">
            <div class="simplebar-height-auto-observer-wrapper">
                <div class="simplebar-height-auto-observer"></div>
            </div>
            <div class="simplebar-mask">
                <div class="simplebar-offset" style="right: -16.8px; bottom: 0px;">
                    <div class="simplebar-content-wrapper" style="height: 100%; overflow: hidden scroll;">
                        <div class="simplebar-content" style="padding: 0px;">
                            <!--- Sidemenu -->
                            <div id="sidebar-menu" class="mm-active">
                                <!-- Left Menu Start -->
                                <ul class="metismenu list-unstyled mm-show" id="side-menu">
                                    <li>
                                        <a href="default.php?page=dashboard">
                                            <i data-feather="arrow-left" class="icon-lg"></i>
                                            <span data-key="t-dash">Back to Dashboard</span>
                                        </a>
                                    </li>
                                    <?php if ($_SESSION['user']['AccessLevel'] == 9) { ?>
                                        <li class="menu-title" data-key="t-menu">SETTINGS</li>
                                        <li>
                                            <a href="default.php?page=settings_overview">
                                                <i data-feather="settings" class="icon-lg"></i>
                                                <span data-key="t-dash">Overview</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="javascript: void(0);" class="has-arrow">
                                                <i data-feather="briefcase" class="icon-lg"></i>
                                                <span data-key="t-Submit">General</span>
                                            </a>
                                            <ul class="sub-menu mm-collapse" aria-expanded="false">
                                                <!--li><a href="default.php?page=chat" data-key="t-eva">UCUA Eva</a></li-->
                                                <li><a href="default.php?page=admin_company" data-key="t-suc">Company & Branch</a></li>
                                                <li><a href="default.php?page=admin_company_branding" data-key="t-sua">Branding</a></li>
                                                <li><a href="default.php?page=appearance" data-key="t-sua">Appearance</a></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="javascript: void(0);" class="has-arrow">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round"
                                                    class="feather feather-activity">
                                                    <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                                    <circle cx="9" cy="7" r="4"></circle>
                                                    <path d="M23 21v-2a4 4 0 0 0-3-3.87"></path>
                                                    <path d="M16 3.13a4 4 0 0 1 0 7.75"></path>
                                                </svg>
                                                <span data-key="t-Admin">Users</span>
                                            </a>
                                            <ul class="sub-menu mm-collapse" aria-expanded="false">
                                                <li><a href="default.php?page=admin_user_management" data-key="t-uc">User Management</a></li>
                                                <li><a href="default.php?page=admin_user_roles" data-key="t-ur">User Roles</a></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="javascript: void(0);" class="has-arrow">
                                                <i data-feather="edit" class="icon-lg"></i>
                                                <span data-key="t-Config">Management</span>
                                            </a>
                                            <ul class="sub-menu mm-collapse" aria-expanded="false">
                                                <li><a href="default.php?page=manage_company" data-key="t-c">Companies</a></li>
                                                <li><a href="default.php?page=manage_location" data-key="t-l">Locations</a>
                                                </li>
                                                <?php if ($_SESSION['user']['AccessLevel'] > 2) { ?>
                                                    <li><a href="default.php?page=manage_department" data-key="t-d">Departments</a></li>
                                                    <li><a href="#" data-key="t-d">Section </a></li>
                                                    <li><a href="default.php?page=manage_offence" data-key="t-o">Offenses</a></li>
                                                    <li><a href="default.php?page=manage_email" data-key="t-e">Emails</a></li>
                                                    <li><a href="#" data-key="t-e">Email templates</a></li>
                                                    <li><a href="#" data-key="t-e">Modules</a></li>
                                                    <!--li><a href="default.php?page=admin_email_template&id=1" data-key="t-e">Email Template</a></li-->
                                                <?php } ?>
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="javascript: void(0);" class="has-arrow">
                                                <i data-feather="shield" class="icon-lg"></i>
                                                <span data-key="t-Admin">Monitoring</span>
                                            </a>
                                            <ul class="sub-menu mm-collapse" aria-expanded="false">
                                                <li><a href="default.php?page=audit-log" data-key="t-uc">Audit logs</a></li>
                                                <li><a href="default.php?page=audit-login" data-key="t-ur">User sessions</a></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="javascript: void(0);" class="has-arrow">
                                                <i data-feather="tool" class="icon-lg"></i>
                                                <span data-key="t-Admin">Tools</span>
                                            </a>
                                            <ul class="sub-menu mm-collapse" aria-expanded="false">
                                                <li><a href="default.php?page=admin_impersonate" data-key="t-uc">Impersonate</a></li>
                                                <li><a href="default.php?page=md5" data-key="t-ur">Encryption</a></li>
                                                <li><a href="default.php?page=sessions" data-key="t-ur">Sessions</a></li>
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="javascript: void(0);" class="has-arrow">
                                                <i data-feather="terminal" class="icon-lg"></i>
                                                <span data-key="t-Admin">Developer</span>
                                            </a>
                                            <ul class="sub-menu mm-collapse" aria-expanded="false">
                                                <li><a href="default.php?page=app_center" data-key="t-uc">App Center</a></li>
                                                <li><a href="default.php?page=admin_apps" data-key="t-uc">App Manager</a></li>
                                                <li><a href="default.php?page=manage_category" data-key="t-uc">App Category</a></li>
                                                <li><a href="default.php?page=integration" data-key="t-ur">REST API</a></li>
                                            </ul>
                                        </li>
                                    <?php } ?>

                                    <li><a class="dropdown-item " href="default.php?page=logout"><i data-feather="power" class="icon-lg"></i> <span data-key="t-logout">Log Out</span></a></li>

                                    <?php if ($_SESSION['user']['AccessLevel'] < 2) { ?>
                                        <div class="card sidebar-alert border-0 mx-3 mb-0 mt-5">
                                            <div class="card-body">
                                                <img src="assets/images/giftbox.png" alt="">
                                                <div class="mt-0">
                                                    <h5 class="alertcard-title font-size-16">Welcome</h5>
                                                    <p class="font-size-13">Get to know the <?php echo $_SESSION['company']['brandName']; ?> web app with our short
                                                        video tutorials.</p>
                                                    <a href="default.php?page=svideos&s="
                                                        class="btn btn-link text-light mt-2"><i
                                                            class="fa-brands fa-youtube"></i> Watch Tutorial</a>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </ul>
                            </div>
                            <!-- Sidebar -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="simplebar-placeholder" style="width: auto; height: 1227px;"></div>
        </div>
        <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
            <div class="simplebar-scrollbar" style="transform: translate3d(0px, 0px, 0px); display: none;"></div>
        </div>
        <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
            <div class="simplebar-scrollbar"
                style="height: 375px; transform: translate3d(0px, 0px, 0px); display: block;"></div>
        </div>
    </div>
</div>