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
                                    <li class="menu-title" data-key="t-menu">MAIN MENU</li>
                                    <li>
                                        <a href="javascript: void(0);" class="has-arrow">
                                            <i data-feather="home" class="icon-lg"></i>
                                            <span data-key="t-Config">Home</span>
                                        </a>
                                        <ul class="sub-menu mm-collapse" aria-expanded="false">
                                            <?php if ($_SESSION['user']['AccessLevel'] == 9) { ?>
                                                <!--li><a href="default.php?page=get-started" data-key="t-l">Get Started </a></li-->
                                                <li><a href="default.php?page=feeds" data-key="t-l">Feeds </a></li>
                                                <li><a href="default.php?page=leaderboard" data-key="t-l">Leaderboard </a></li>
                                            <?php } ?>
                                            <li><a href="default.php?page=dashboard" data-key="t-l">Dashboard </a></li>
                                            <li><a href="default.php?page=workspaces" data-key="t-l">Workspaces </a></li>
                                        </ul>
                                    </li>
                                    <!--li>
                                        <a href="default.php?page=quickstart">
                                            <i data-feather="bookmark" class="icon-lg"></i>
                                            <span data-key="t-dash">Quickstart</span>
                                        </a>
                                        </li>
                                        <li>
                                            <a href="default.php?page=comments">
                                                <i data-feather="message-square" class="icon-lg"></i>
                                                <span data-key="t-dash">Comments</span>
                                            </a>
                                    </li-->
                                    <li>
                                        <a href="javascript: void(0);" class="has-arrow">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round"
                                                class="feather feather-activity">
                                                <line x1="12" y1="5" x2="12" y2="19"></line>
                                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                            </svg>
                                            <span data-key="t-Submit">Submit</span>
                                        </a>
                                        <ul class="sub-menu mm-collapse" aria-expanded="false">
                                            <!--li><a href="default.php?page=chat" data-key="t-eva">UCUA Eva</a></li-->
                                            <li><a href="default.php?page=uc_form" data-key="t-suc">Unsafe condition</a>
                                            </li>
                                            <li><a href="default.php?page=ua_form" data-key="t-sua">Unsafe action</a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a href="default.php?page=reports_all">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity">
                                                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                <polyline points="14 2 14 8 20 8"></polyline>
                                                <line x1="16" y1="13" x2="8" y2="13"></line>
                                                <line x1="16" y1="17" x2="8" y2="17"></line>
                                                <polyline points="10 9 9 9 8 9"></polyline>
                                            </svg>
                                            <span data-key="t-dash">Reports</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="default.php?s=&submit=&page=search">
                                            <i data-feather="search" class="icon-lg"></i>
                                            <span data-key="t-dash">Search</span>
                                        </a>
                                    </li>
                                    <?php if ($_SESSION['user']['AccessLevel'] == 9 || $_SESSION['user']['AccessLevel'] == 4 || $_SESSION['user']['AccessLevel'] == 3) { ?>
                                        <li class="menu-title mt-2" data-key="t-components">MANAGEMENT</li>
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
                                                <li><a href="default.php?page=admin_user_management" data-key="t-uc">All Users</a></li>
                                                <li><a href="default.php?page=admin_user_roles" data-key="t-ur">All Roles</a></li>
                                                <!--li><a href="#" data-key="t-ut"> Invite Teammates</a></li-->
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="javascript: void(0);" class="has-arrow">
                                                <i data-feather="edit" class="icon-lg"></i>
                                                <span data-key="t-Config">Manage</span>
                                            </a>
                                            <ul class="sub-menu mm-collapse" aria-expanded="false">
                                                <li><a href="default.php?page=manage_company" data-key="t-c">Companies </a></li>
                                                <li><a href="default.php?page=manage_location" data-key="t-l">Locations </a>
                                                </li>
                                                <?php if ($_SESSION['user']['AccessLevel'] > 2) { ?>
                                                    <li><a href="default.php?page=manage_offence" data-key="t-o">Offenses </a></li>
													<li><a href="default.php?page=manage_department" data-key="t-d">Departments </a></li>
                                                    <li><a href="#" data-key="t-d">Section </a></li>
                                                    <li><a href="default.php?page=manage_prefix" data-key="t-e">Prefix </a></li>
                                                    <li><a href="default.php?page=manage_email" data-key="t-e">Emails </a></li>
                                                    <li><a href="#" data-key="t-e">Email templates</a></li>
                                                    <li><a href="#" data-key="t-e">Modules </a></li>
                                                    <li><a href="default.php?page=cms" data-key="t-p">Contents </a></li>
                                                    <!--li><a href="default.php?page=admin_email_template&id=1" data-key="t-e">Email Template</a></li-->
                                                <?php } ?>
                                            </ul>
                                        </li>
                                        <li>
                                            <a href="default.php?page=app_center">
                                                <i data-feather="grid" class="icon-lg"></i>
                                                <span data-key="t-dash">Apps</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="default.php?page=settings_overview">
                                                <i data-feather="settings" class="icon-lg fa-spin"></i>
                                                <span data-key="t-dash">Settings</span>
                                            </a>
                                        </li>

                                        <!--li>
                                            <a href="javascript: void(0);" class="has-arrow">
                                                <i data-feather="shield" class="icon-lg"></i>
                                                <span data-key="t-Admin">Security</span>
                                            </a>
                                            <ul class="sub-menu mm-collapse" aria-expanded="false">
                                                <li><a href="default.php?page=audit-log" data-key="t-uc">Audit logs</a></li>
                                                <li><a href="default.php?page=audit-login" data-key="t-ur">Login sessions</a></li>
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
                                        </li-->

                                        <!--li>
                                            <a href="javascript: void(0);" class="has-arrow">
                                                <i class="fa-solid fa-comments"></i>
                                                <span data-key="t-chatbot">Chatbot</span>
                                            </a>
                                            <ul class="sub-menu mm-collapse" aria-expanded="false">
                                                <li><a href="default.php?page=admin-chat-responses"
                                                        data-key="t-responses">Responses</a></li>
                                                <li><a href="default.php?page=admin-chat-unanswered"
                                                        data-key="t-unanswered">Unanswered</a></li>
                                            </ul>
                                        </li-->
                                    <?php } ?>

                                    <li class="menu-title mt-2" data-key="t-resources">RESOURCES</li>
                                    <li>
                                        <a href="javascript: void(0);" class="has-arrow">
                                            <i data-feather="book-open" class="icon-lg"></i>
                                            <span data-key="t-more">Learn more</span>
                                        </a>
                                        <ul class="sub-menu mm-collapse" aria-expanded="false">
                                            <li><a href="default.php?page=learn" data-key="t-uc">Knowledge base</a></li>
                                            <li><a href="default.php?page=privacy" target="_blank" data-key="t-ur">Privacy policy</a></li>
                                            <li><a href="default.php?page=terms" target="_blank" data-key="t-ur">Term of service</a></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#mailto:icthelpdesk@johorport.com.my?subject=Support&body=Support"><i data-feather="life-buoy" class="icon-lg"></i> <span data-key="t-support">Support</span></a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#mailto:icthelpdesk@johorport.com.my?subject=Feedback&body=Feedback">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="16" height="16"
                                                class="main-grid-item-icon" fill="none" stroke="currentColor" stroke-linecap="round"
                                                stroke-linejoin="round" stroke-width="2">
                                                <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                                            </svg>
                                            <span data-key="t-support">Feedback</span></a>
                                    </li>

                                    <li><a class="dropdown-item " href="default.php?page=logout"><i data-feather="power" class="icon-lg"></i> <span data-key="t-logout">Log Out</span></a></li>

                                    <?php if ($_SESSION['user']['AccessLevel'] < 2) { ?>
                                        <div class="card sidebar-alert border-0 mx-3 mb-0 mt-5">
                                            <div class="card-body">
                                                <img src="assets/images/giftbox.png" alt="">
                                                <div class="mt-0">
                                                    <h5 class="alertcard-title font-size-16">Welcome</h5>
                                                    <p class="font-size-13">Get to know the UCUA web app with our short
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