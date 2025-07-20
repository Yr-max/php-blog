<?php

session_start();
require 'config/config.php';

// Control for access  Login Session 
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'] )) {
  header('location: login.php');
}// Control Login Session
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Blogs</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition sidebar-mini">
  <div class="">
    <!-- Content Wrapper. Contains page content -->
    <div class="contant-wrapper" style="margin-left:0; !important">
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <div class="container-fluid">
          <h1 style="text-align: center;">Widgets</h1>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <?php
       // Display data from posts table 
    $stmt = $db->prepare("SELECT * FROM posts ORDER BY id DESC");
    $stmt->execute();
    $result = $stmt->fetchAll();
    ?>
    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <?php
          if ($result) 
          {
            $i = 1;
            foreach ($result as $value) {
              ?>
              <div class="col-md-4">
                <!-- Box Comment -->
                <div class="card card-widget">
                  <div class="card-header">
                    <div style="text-align: center; float: none;" class="card-title">
                      <h3><?php echo $value['title']; ?></h3>
                    </div>
                    <!-- /.user-block -->
                  </div>
                  <!-- /.card-header -->
                  <div class="card-body">
                    <a href="details.php?id=<?php echo $value['id']; ?>">
                    <img class="img-fluid pad" src="admin/images/<?php echo $value['image']; ?>" style="height: 200px !important;"></a>
                  </div>
                  <!-- /.card-body -->
                </div>
                <!-- /.card -->
              </div>
              <!-- /.col -->
              <?php
              $i++;
            } 
          }
          ?>
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->

    <a id="back-to-top" href="#" class="btn btn-primary back-to-top" role="button" aria-label="Scroll to top">
      <i class="fas fa-chevron-up"></i>
    </a>
  </div>
  <!-- /.content-wrapper -->

  <footer class="main-footer" style="margin-left: 0px;">
  <!-- To the right -->
  <div class="float-right d-none d-sm-inline">
    <a href="logout.php" type="button" class="btn btn-dark">Logout</a>
  </div>
  <!-- Default to the left -->
  <strong>Copyright &copy; 2020-2025 <a href="#">kosoemin</a>.</strong> All rights reserved.
</footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>
</body>
</html>
