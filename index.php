<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>SEO Login - Parcel Horse</title>
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="stylesheet" href="./bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <link rel="stylesheet" href="./dist/css/AdminLTE.min.css">
  <style>
    .login-page {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      min-height: 100vh;
    }

    .login-box {
      width: 360px;
      margin: 7% auto;
    }

    .login-logo {
      margin-bottom: 20px;
    }

    .login-logo a {
      color: #fff;
      font-size: 28px;
      font-weight: 600;
    }

    .login-box-body {
      background: #fff;
      padding: 20px;
      border-top: 0;
      color: #666;
      border-radius: 8px;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }

    .has-password-toggle {
      position: relative;
    }

    .password-toggle {
      position: absolute;
      right: 35px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: #999;
      cursor: pointer;
      padding: 5px;
      z-index: 10;
    }

    .password-toggle:hover {
      color: #333;
    }

    .form-control-feedback {
      pointer-events: none;
    }
  </style>
</head>

<body class="hold-transition login-page">
  <div class="login-box">
    <div class="login-logo">
      <a href="index.php"><b>SEO Portal</b></a>
    </div>
    <div class="login-box-body">
      <?php
      if (isset($_GET['error'])) {
        echo '<div class="alert alert-danger">' . htmlspecialchars($_GET['error']) . '</div>';
      }
      if (isset($_GET['message'])) {
        echo '<div class="alert alert-success">' . htmlspecialchars($_GET['message']) . '</div>';
      }
      ?>
      <div class="alert alert-danger" id="errors" style="display: none;"></div>
      <form id="loginForm">
        <div style="text-align: center; margin-bottom: 20px;">
          <img src="../parcelhorse-admin/images/parcel-horse-vertical-logo.png" alt="Parcel Horse"
            style="max-height: 60px; margin-bottom: 10px;">
        </div>
        <div class="form-group has-feedback">
          <input type="text" name="email" id="email" class="form-control" placeholder="Email"
            autocomplete="email">
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback has-password-toggle">
          <input type="password" name="password" id="password" class="form-control" placeholder="Password"
            autocomplete="current-password">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
          <button type="button" class="password-toggle" id="togglePassword" tabindex="-1"
            aria-label="Toggle password visibility">
            <i class="fa fa-eye" id="eyeIcon"></i>
          </button>
        </div>
        <div id="message" class="text-danger" style="margin-top: 10px;"></div>
        <div class="row" style="margin-bottom: 15px;">
          <div class="col-xs-12">
            <button type="submit" id="submitBtn" class="btn btn-primary btn-block btn-flat">
              <i class="fa fa-sign-in"></i> Sign In
            </button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <script src="./bower_components/jquery/dist/jquery.min.js"></script>
  <script src="./bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <script>
    $(function () {
      // Password toggle
      $('#togglePassword').on('click', function () {
        const passwordField = $('#password');
        const eyeIcon = $('#eyeIcon');
        const type = passwordField.attr('type') === 'password' ? 'text' : 'password';
        passwordField.attr('type', type);
        eyeIcon.toggleClass('fa-eye fa-eye-slash');
      });

      // Login form submission
      $('#loginForm').on('submit', async function (e) {
        e.preventDefault();

        const email = $('#email').val().trim();
        const password = $('#password').val();

        $('#errors').html("").hide();
        $('#message').html("");

        if (!email || !password) {
          $('#errors').html('Please fill in all fields.').show();
          return;
        }

        const submitBtn = $('#submitBtn');
        const originalText = submitBtn.html();
        submitBtn.html('<i class="fa fa-spinner fa-spin"></i> Signing In...').prop('disabled', true);

        $('#loginForm').addClass('loading');

        try {
          const response = await fetch(`bridge.php?mode=login`, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({
              email: email,
              password: password
            })
          });

          if (response.ok) {
            window.location.href = "dashboard.php";
          } else {
            const res = await response.json();
            if (res.error) {
              $('#errors').html(res.error).show();
            } else if (res.errors) {
              $('#errors').html("<ul>");
              for (let err of res.errors) {
                $('#errors').append(`<li>${err}</li>`);
              }
              $('#errors').append("</ul>").show();
            }
          }
        } catch (err) {
          console.error("Error:", err);
          $('#errors').html("Network error or server unreachable.").show();
        } finally {
          submitBtn.html(originalText).prop("disabled", false);
          $('#loginForm').removeClass('loading');
        }
      });
    });
  </script>
</body>

</html>