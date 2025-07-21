<?php

session_start();
require "../config/config.php";

// Control Login Session
if (empty($_SESSION['user_id']) && empty($_SESSION['logged_in'] )) 
{
  header('location: login.php');
}

if ($_SESSION['role'] != 1) {
  header('location: login.php');
}// Control Login Session


// add new user/admin 
if ($_POST) 
{
  $name = $_POST['name'];
  $email = $_POST['email'];
  $password = $_POST['password'];
  $role = empty($_POST['role']) ? 0 : 1;
 
  // Check for existing user
  $stmt = $db->prepare("SELECT * FROM users WHERE email = :email");
  $stmt->bindValue(':email', $email);
  $stmt->execute();
  $user = $stmt->fetch(PDO::FETCH_ASSOC); // ✅ fixed typo (PDO not POD)

  if ($user) {
    echo "<script>
    alert('This email is already used');
    </script>";
  }else {
        // insert get_data into users table 
    $stmt = $db->prepare("INSERT INTO users (name,email,password,role) VALUES (:name,:email,:password,:role)");
    $result = $stmt->execute(
      array(':name' =>$name,':email' =>$email,':password' =>$password,':role' =>$role)
    );
    if ($result) {
      echo "<script>
      alert('New user is successfully added');
      window.location.href = 'user_list.php';
      </script>";
    }
  }
}

?>

<?php
include('header.php');
?>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">User Table</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <form action="" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label>Name</label>
                <input class="form-control" type="text" name="name" required>
              </div>
              <div class="form-group">
                <label>Email</label>
                <input class="form-control" type="email" name="email" required>
              </div>
              <div class="form-group">
                <label>Password</label>
                <input class="form-control" type="text" name="password" required>
              </div>
              <div class="form-group">
                <label for="vehicle3">Role</label><br>
                <input type="checkbox" name="role" value="1">
              </div>
              <div class="form-group">
                <input class="btn btn-success" type="submit" value="Create">
                <a href="user_list.php" class="btn btn-secondary">Back</a>
              </div>
            </form>
          </div>
          <!-- /.card-body -->
        </div>
        <!-- /.card -->
      </div>
    </div>
    <!-- /.row -->
  </div><!-- /.container-fluid -->
</div>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php
include('footer.html');
?>
