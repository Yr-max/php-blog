<?php

session_start();
require 'config/config.php';

// Control for access  Login Session 
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'] )) {
  header('location: login.php');
}// Control Login Session

// To show update data from posts table
$stmt = $db->prepare("SELECT * FROM posts WHERE id=".$_GET['id']);
$stmt->execute();
$result = $stmt->fetchAll();


 // submit for comments
$author_id = $_SESSION['user_id'];
$post_id = $_GET['id'];

// To show comments in user interface
$commentStmt = $db->prepare("SELECT * FROM comments WHERE post_id=$post_id");
$commentStmt->execute();
$commentResult = $commentStmt->fetchAll();

// print("<pre>");
// print_r($commentResult);
// exit();

$authorResult = [];
if ($commentResult) {
  foreach ($commentResult as $key => $value) {
    $author_id = $commentResult[$key]['author_id'];

    // To get name user name from users to show comments header
    $authorStmt = $db->prepare("SELECT * FROM users WHERE id=$author_id");
    $authorStmt->execute();
    $authorResult[] = $authorStmt->fetchAll();
  }
}

// print("<pre>");
// print_r($authorResult);

if ($_POST) {
  if (empty($_POST['comments'])) {
    
    if (empty($_POST['title'])) {
      $commentError = 'Comments field is require';
    }
  }else {

    $comments = $_POST['comments'];
    // insert get_data into posts table 
    $stmt = $db->prepare("INSERT INTO comments (content,author_id,post_id) VALUES (:content,:author_id,:post_id)");
    $result = $stmt->execute(
      array(':content' =>$comments,':author_id' =>$author_id,':post_id' =>$post_id)
    );
    if ($result) {
      header('location: details.php?id='.$post_id);
    }
  }
  
}


?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Blogs | Details</title>
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
  <div class="wrapper">
    <!-- Content Wrapper. Contains page content -->
    <div class="contant-wrapper" style="margin-left: 0px;">
      <!-- Content Header (Page header) -->
      <!-- Main content -->
      <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <!-- Box Comment -->
              <div class="card card-widget">
                <div class="card-header">
                  <div style="text-align: center; float: none;" class="card-title">
                    <h3><?php echo $result[0]['title']; ?></h3>
                  </div>
                  <!-- /.user-block -->
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <img class="card-img-top" src="admin/images/<?php echo $result[0]['image']; ?>" alt="Card image cap">
                  <br><br>
                  <p><?php echo $result[0]['content']; ?></p>
                  <a href="/blog" class="btn-group btn btn-success">Go Back</a>
                </div>
                <!-- /.card-body -->
                <hr>
                <div style="margin-left: 25px;" class="card-title">
                  <h3>Comments</h3>
                </div>
                <hr>
                <div class="card-footer card-comments">
                  <div class="card-comment">
                    <?php if ($commentResult) { 
                      ?>
                      <div class="comment-text" style="margin-left: 0 !important;">
                        <?php foreach ($commentResult as $key => $value) { ?>
                      <span class="username">

                        <h4><?php print_r($authorResult[$key][0]['name']); ?></h4>
                        <span class="text-muted float-right"><?php echo $value['created_at']; ?></span>
                      </span><!-- /.username -->
                      <?php echo $value['content']; ?><br><br>
                      <?php
                    }
                      ?>
                    </div>
                    <!-- /.comment-text -->
                    <?php 
                      } 
                    ?>
                  </div>
                  <!-- /.card-comment -->
                </div>
                <!-- /.card-footer -->
                <div class="card-footer">
                  <form action="" method="post">
                    <div class="img-push">
                      <p style="color: red;"><?php echo empty($commentError) ? '' : '*'.$commentError; ?></p>
                      <input type="text" name="comments" class="form-control form-control-sm" placeholder="Press enter to post comment">
                    </div>
                  </form>
                </div>
                <!-- /.card-footer -->
              </div>
              <!-- /.card -->
            </div>
            <!-- /.col -->
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

    <!-- Main Footer -->
<footer class="main-footer" style="margin-left: 0px;">
  <!-- To the right -->
  <div class="float-right d-none d-sm-inline">
    <a href="logout.php" type="button" class="btn btn-dark">Logout</a>
  </div>
  <!-- Default to the left -->
  <strong>Copyright &copy; 2020-2025 <a href="#">kosoemin</a>.</strong> All rights reserved.
</footer>
</div>
<!-- ./wrapper -->

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
