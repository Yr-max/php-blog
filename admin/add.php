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


// Display data from posts table 
$stmt = $db->prepare("SELECT * FROM posts ORDER BY id DESC");
$stmt->execute();
$result = $stmt->fetchAll();


// get type of image from uplode
if ($_POST) 
{
  if (empty($_POST['title']) || empty($_POST['content']) || empty($_FILES['image'])) {
    
    if (empty($_POST['title'])) {
      $titleError = 'Title field is require';
    }
    if (empty($_POST['content'])) {
      $contentError = 'Content field is require';
    }
    if (empty($_FILES['image'])) {
      $imageError = 'Image field is require';
    }
  }else {

  $file = 'images/'.($_FILES['image']['name']);
  $imageType = pathinfo($file,PATHINFO_EXTENSION);


  if ($imageType != 'png' && $imageType != 'jpg' && $imageType != 'jpeg') 
  {
    echo "<script>alert('Image must be png, jpg, jpeg')</script>";
  }
  else 
  {
    $title = $_POST['title'];
    $content = $_POST['content'];
    $image = $_FILES['image']['name'];
    $author_id = $_SESSION['user_id'];
    move_uploaded_file($_FILES['image']['tmp_name'],$file);

    // insert get_data into posts table 
    $stmt = $db->prepare("INSERT INTO posts (title,content,author_id,image) VALUES (:title,:content,:author_id,:image)");
    $result = $stmt->execute(
      array(':title' =>$title,':content' =>$content,':author_id' =>$author_id,':image' =>$image)
    );
    if ($result) {
      echo "<script>
                alert('New blog is successfully added');
                window.location.href = 'index.php';
              </script>";
    }
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
            <h3 class="card-title">Blogs Table</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <form action="" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label>Title</label>
                <p style="color: red;"><?php echo empty($titleError) ? '' : '*'.$titleError; ?></p>
                <input class="form-control" type="text" name="title" >
              </div>
              <div class="form-group">
                <label>Content</label>
                <p style="color: red;"><?php echo empty($contentError) ? '' : '*'.$contentError; ?></p>
                <textarea class="form-control" name="content" rows="4" cols="50"></textarea>
              </div>
              <div class="form-group">
                <label>Image</label>
                <p style="color: red;"><?php echo empty($imageError) ? '' : '*'.$imageError; ?></p>
                <input class="form-control" type="file" name="image">
              </div>
              <div class="form-group">
                <input class="btn btn-success" type="submit" value="Create">
                <a href="index.php" class="btn btn-secondary">Back</a>
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
