<?php
include 'header.php';
?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Change Password</h1>
        <ol class="breadcrumb">
            <li><a href="dashboard.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Change Password</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Change Password</h3>
            </div>
            <div class="box-body">
                <div class="alert alert-danger" id="errors" style="display: none;"></div>
                <form id="changedPasswordForm">
                    <div class="form-group">
                        <label for="old_password">Current Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Enter current password">
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="password" name="password" placeholder="Enter new password">
                    </div>
                    <div class="form-group">
                        <label for="confirm_password">Confirm New Password <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm new password">
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" id="submitProfileBtn">
                            <i class="fa fa-save"></i> Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<?php include 'footer.php'; ?>
<script src="dist/js/change_password.js"></script>

