<?php
$title = 'Chat';
include('testFunctions.php');

if (!isLoggedIn()) {
    $_SESSION['msg'] = "You must log in first";
    header('location: login');
}

function funLocation()
{
    $option = '';
    global $db;
    $sql1 = "SELECT * FROM location";
    $result1 = mysqli_query($db, $sql1);
    while ($row1 = mysqli_fetch_array($result1)) {
        $LocationId = $row1['LocationId'];
        $LocationName = $row1['LocationName'];
        $option .= "<option value='$LocationId'>$LocationName</option>";
    }
    return $option;
}

include('inc_header.php');
?>

</head>

<body class="pace-done" data-new-gr-c-s-check-loaded="8.904.0" data-gr-ext-installed="" data-sidebar="brand">
    <div class="pace pace-inactive">
        <div class="pace-progress" style="transform: translate3d(100%, 0px, 0px);" data-progress-text="100%"
            data-progress="99">
            <div class="pace-progress-inner"></div>
        </div>
        <div class="pace-activity"></div>
    </div>

    <!-- <body data-layout="horizontal"> -->

    <!-- Begin page -->
    <div id="layout-wrapper">

        <!-- ========== Left Navbar Start ========== -->
        <?php include('inc_navbar.php'); ?>
        <!-- Left Navbar End -->
        <!-- ========== Left Sidebar Start ========== -->
        <?php include('inc_sidebar.php'); ?>
        <!-- Left Sidebar End -->



        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0 font-size-18"><?php echo $title; ?></h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item">Home</li>
                                        <li class="breadcrumb-item active">ChatBot</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->

                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="p-3 px-lg-4 border-bottom">
                                    <div class="row">
                                        <div class="col-xl-4 col-7">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0 avatar-sm me-3 d-sm-block d-none">
                                                    <img src="img/blank.jpg" alt=""
                                                        class="img-fluid d-block rounded-circle">
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h5 class="font-size-14 mb-1 text-capitalize text-truncate"><a
                                                            href="#" class="text-dark">
                                                            <?php echo $_SESSION['user']['Name']; ?>
                                                        </a></h5>
                                                    <p class="text-muted text-truncate mb-0">Online</p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xl-8 col-5">
                                            <ul class="list-inline user-chat-nav text-end mb-0">
                                                <li class="list-inline-item">
                                                    <div class="dropdown">
                                                        <button class="btn nav-btn dropdown-toggle" type="button"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="fa-solid fa-magnifying-glass"></i>
                                                        </button>
                                                        <div
                                                            class="dropdown-menu dropdown-menu-end dropdown-menu-md p-2">
                                                            <form class="px-2">
                                                                <div>
                                                                    <input type="text"
                                                                        class="form-control border bg-light-subtle"
                                                                        placeholder="Search...">
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </li>

                                                <li class="list-inline-item">
                                                    <div class="dropdown">
                                                        <button class="btn nav-btn dropdown-toggle" type="button"
                                                            data-bs-toggle="dropdown" aria-haspopup="true"
                                                            aria-expanded="false">
                                                            <i class="fa-solid fa-ellipsis"></i>
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item"
                                                                href="#">Profile</a>
                                                            <a class="dropdown-item"
                                                                href="default.php?page=admin-chat-responses" target="_blank">Responses</a>
                                                            <a class="dropdown-item"
                                                                href="default.php?page=admin-chat-unanswered" target="_blank">Unanswered</a>
                                                        </div>
                                                    </div>
                                                </li>
                                                <ul>
                                        </div>
                                    </div>
                                </div>

                                <div class="chat-conversation p-3 px-2" data-simplebar="init">
                                    <div class="simplebar-wrapper" style="margin: -16px -8px;">
                                        <div class="simplebar-height-auto-observer-wrapper">
                                            <div class="simplebar-height-auto-observer"></div>
                                        </div>
                                        <div class="simplebar-mask">
                                            <div class="simplebar-offset" style="right: -16.8px; bottom: 0px;">
                                                <div class="simplebar-content-wrapper card" id="chat_convo" style="height: 100%; overflow: hidden scroll;">
                                                    <div class="simplebar-content card-body" style="padding: 16px 8px;">
                                                        <ul class="list-unstyled mb-0 ">
                                                            <li class="chat-day-title">
                                                                <span class="title">Today</span>
                                                            </li>
                                                            <li class="right direct-chat-messages">
                                                                <div class="conversation-list ">
                                                                    <div class="ctext-wrap">
                                                                        <div class="ctext-wrap-content">
                                                                            <h5 class="conversation-name"><a href="#"
                                                                                    class="user-name">Eva</a></h5>
                                                                            <p class="mb-0">Hi! I'm Eva, a ChatBot of
                                                                                this application. Type UCUA to proceed.
                                                                            </p>
                                                                        </div>
                                                                        <div class="dropdown align-self-start">

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>

                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="simplebar-placeholder" style="width: auto; height: 824px;"></div>
                                    </div>
                                    <div class="simplebar-track simplebar-horizontal" style="visibility: hidden;">
                                        <div class="simplebar-scrollbar"
                                            style="transform: translate3d(0px, 0px, 0px); display: none;"></div>
                                    </div>
                                    <div class="simplebar-track simplebar-vertical" style="visibility: visible;">
                                        <div class="simplebar-scrollbar"
                                            style="height: 197px; transform: translate3d(0px, 0px, 0px); display: block;">
                                        </div>
                                    </div>
                                </div>

                                <div class="p-3 border-top">
                                    <form id="send_chat" method="post">
                                        <div class="row">
                                            <div class="col">
                                                <div class="position-relative">
                                                    <input type="text" name="message"
                                                        class="form-control border bg-light-subtle"
                                                        placeholder="Enter Message...">
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <button type="submit"
                                                    class="btn btn-primary chat-send waves-effect waves-light">
                                                    <i class="mdi mdi-send float-end"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div><!--end card-->
                        </div>
                        <!-- end col -->
                    </div>
                    <!-- end row -->
                </div> <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            <ul class="hide">
                <div id="user_chat">
                    <li class="right">
                        <div class="conversation-list ">
                            <div class="ctext-wrap">
                                <div class="ctext-wrap-content">
                                    <h5 class="conversation-name"><a href="#" class="user-name text-capitalize">
                                            <?php echo $_SESSION['user']['Name']; ?>
                                        </a></h5>
                                    <p class="mb-0 direct-chat-text">Hi! I'm Eva, a ChatBot of this application. Type
                                        UCUA to proceed.</p>
                                </div>
                                <div class="dropdown align-self-start">

                                </div>
                            </div>
                        </div>
                    </li>
                </div>
                <div id="bot_chat">
                    <li class="right">
                        <div class="conversation-list">
                            <div class="ctext-wrap">
                                <div class="ctext-wrap-content">
                                    <h5 class="conversation-name"><a href="#" class="user-name">Eva</a></h5>
                                    <p class="mb-0 direct-chat-text">Hi! I'm Eva, a ChatBot of this application. Type
                                        UCUA to proceed.</p>
                                </div>
                                <div class="dropdown align-self-start">

                                </div>
                            </div>
                        </div>
                    </li>
                </div>
            </ul>

            <?php include('inc_footer.php'); ?>

        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->


    <!-- Right Sidebar -->
    <?php include('inc_rightbar.php'); ?>
    <!-- /Right-bar -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <?php include('inc_scripts.php'); ?>
    <script type="text/javascript">
        $('.hide').hide();

        function myFunction() {
            var height = 0;
            $("#chat_convo .card-body").each(function(i, value) {
                height += parseInt($(this).height());
            });

            height += '';
            $("#chat_convo .card-body").animate({
                scrollTop: height
            });
        }

        $(document).ready(function() {
            $('[name="message"]').keypress(function(e) {
                console.log()
                if (e.which === 13 && e.originalEvent.shiftKey == false) {
                    $('#send_chat').submit()
                    return false;
                }
            })
            $('#send_chat').submit(function(e) {
                e.preventDefault();
                var message = $('[name="message"]').val();
                if (message == '' || message == null) return false;
                var uchat = $('#user_chat').clone();
                uchat.find('.direct-chat-text').html(message);
                $('#chat_convo .direct-chat-messages').append(uchat.html());
                $('[name="message"]').val('');
                $("#chat_convo .card-body").animate({
                    scrollTop: $("#chat_convo .card-body").prop('scrollHeight')
                }, "fast");
                myFunction();
                $("#chat_convo .card-body").scrollTop($("#chat_convo .card-body")[0].scrollHeight);

                $.ajax({
                    url: "../chat/chatbot/classes/Master.php?f=get_response",
                    method: 'POST',
                    data: {
                        message: message
                    },
                    error: err => {
                        console.log(err)
                        alert_toast("An error occured.", 'error');
                        end_loader();
                    },
                    success: function(resp) {
                        if (resp) {
                            resp = JSON.parse(resp)
                            if (resp.status == 'success') {
                                var bot_chat = $('#bot_chat').clone();
                                bot_chat.find('.direct-chat-text').html(resp.message);
                                $('#chat_convo .direct-chat-messages').append(bot_chat.html());
                                $("#chat_convo .card-body").animate({
                                    scrollTop: $("#chat_convo .card-body").prop('scrollHeight')
                                }, "fast");
                                myFunction();
                                $("#chat_convo .card-body").scrollTop($("#chat_convo .card-body")[0].scrollHeight);
                            }
                        }
                    }
                })
            })


            window.viewer_modal = function($src = '') {
                start_loader()
                var t = $src.split('.')
                t = t[1]
                if (t == 'mp4') {
                    var view = $("<video src='" + $src + "' controls autoplay></video>")
                } else {
                    var view = $("<img src='" + $src + "' />")
                }
                $('#viewer_modal .modal-content video,#viewer_modal .modal-content img').remove()
                $('#viewer_modal .modal-content').append(view)
                $('#viewer_modal').modal({
                    show: true,
                    backdrop: 'static',
                    keyboard: false,
                    focus: true
                })
                end_loader()

            }
            window.uni_modal = function($title = '', $url = '', $size = "") {
                start_loader()
                $.ajax({
                    url: $url,
                    error: err => {
                        console.log()
                        alert("An error occured")
                    },
                    success: function(resp) {
                        if (resp) {
                            $('#uni_modal .modal-title').html($title)
                            $('#uni_modal .modal-body').html(resp)
                            if ($size != '') {
                                $('#uni_modal .modal-dialog').addClass($size + '  modal-dialog-centered')
                            } else {
                                $('#uni_modal .modal-dialog').removeAttr("class").addClass("modal-dialog modal-md modal-dialog-centered")
                            }
                            $('#uni_modal').modal({
                                show: true,
                                backdrop: 'static',
                                keyboard: false,
                                focus: true
                            })
                            end_loader()
                        }
                    }
                })
            }
            window._conf = function($msg = '', $func = '', $params = []) {
                $('#confirm_modal #confirm').attr('onclick', $func + "(" + $params.join(',') + ")")
                $('#confirm_modal .modal-body').html($msg)
                $('#confirm_modal').modal('show')
            }
        })
    </script>
</body>

</html>