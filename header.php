<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

ini_set('session.gc_maxlifetime', 86400); // 24 hours

include("./ApiClient.php");

$client = new ApiClient();
// $response = $client->is_vendor_logged_in();
// $result = json_decode($response, true);
// $name = $result['name'] ?? "";
// $username = $result['username'] ?? "";

$page = basename($_SERVER['PHP_SELF'], ".php");

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>SEO Portal - Parcel Horse</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet" href="./bower_components/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="stylesheet" href="./bower_components/Ionicons/css/ionicons.min.css">
    <link rel="stylesheet" href="./dist/css/AdminLTE.min.css">
    <link rel="stylesheet" href="./dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>

<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
        <header class="main-header">
            <a href="dashboard.php" class="logo">
                <span class="logo-mini"><b>PH</b></span>
                <span class="logo-lg">
                    <img src="./dist/img/parcel-horse-logo.png"
                        style="height: 40px; width: auto; margin-right: 10px; vertical-align: middle;">
                    <b>SEO Portal</b>
                </span>
            </a>
            <nav class="navbar navbar-static-top">
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
            </nav>
        </header>
        <aside class="main-sidebar">
            <section class="sidebar">
                <ul class="sidebar-menu" data-widget="tree">
                    <li class="<?php if ($page == 'dashboard')
                        echo 'active'; ?>">
                        <a href="dashboard.php"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
                    </li>
                    <li class="<?php if ($page == 'create_metadata')
                        echo 'active'; ?>">
                        <a href="create_metadata.php"><i class="fa fa-dashboard"></i> <span>Create Metadata</span></a>
                    </li>
                    <li class="<?php if ($page == 'change_password')
                        echo 'active'; ?>">
                        <a href="change_password.php"><i class="fa fa-lock"></i> <span>Change Password</span></a>
                    </li>
                    <li><a href="logout.php"><i class="fa fa-sign-out"></i> <span>Logout</span></a></li>
                </ul>
            </section>
        </aside>