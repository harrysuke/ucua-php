<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <p class="col-md-12">&copy; <?php echo date('Y'); ?> <?php echo $_SESSION['company']['brandName']; ?>. All rights reserved.</p>
            </div>
            <div class="col-sm-6">
                <ul class="nav col-md-12 justify-content-end">
                    <li class="nav-item">
                        <a href="default.php?page=workspaces" class="nav-link px-2 ">
                            <span class="btn btn-sm btn-soft-light waves-effect waves-light text-secondary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="<?php echo $_SESSION['company']['brandName']; ?>">
                                <?php echo $_SESSION['company']['brandName']; ?>
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="default.php?page=admin_company" class="nav-link px-2 ">
                            <span class="btn btn-sm btn-soft-light waves-effect waves-light text-secondary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="<?php echo ucwords(strtolower($_SESSION['company']['companyName'])); ?>">
                                <?php echo $_SESSION['user']['companyCode']; ?>
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="default.php?page=admin_company_view&id=<?php echo $_SESSION['user']['companyCode']; ?>" class="nav-link px-2 ">
                            <span class="btn btn-sm btn-soft-light waves-effect waves-light text-secondary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="<?php echo $_SESSION['company']['branchName']; ?>">
                                <?php echo $_SESSION['user']['branchCode']; ?>
                            </span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="default.php?page=learn" class="nav-link px-2">
                            <span class="btn btn-sm btn-soft-link waves-effect waves-light text-secondary" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip" data-bs-title="Help">
                                Help
                            </span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</footer>