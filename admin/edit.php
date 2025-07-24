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


if ($_POST) 
{
  if (empty($_POST['title']) || empty($_POST['content'])) {
    
    if (empty($_POST['title'])) {
      $titleError = 'Title field is require';
    }
    if (empty($_POST['content'])) {
      $contentError = 'Content field is require';
    }
  }else {
    $id = $_POST['id'];
  $title = $_POST['title'];
  $content = $_POST['content'];

  if ($_FILES['image']['name'] != null) 
  {
    $file = 'images/'.($_FILES['image']['name']);
    $imageType = pathinfo($file,PATHINFO_EXTENSION);


    if ($imageType != 'png' && $imageType != 'jpg' && $imageType != 'jpeg') 
    {
      echo "<script>alert('Image must be png, jpg, jpeg')</script>";
    }
    else 
    {
      $image = $_FILES['image']['name'];
      move_uploaded_file($_FILES['image']['tmp_name'],$file);

    // update get_data into posts table 
      $stmt = $db->prepare("UPDATE posts SET title='$title', content='$content', image='$image' WHERE id='$id'");
      $result = $stmt->execute();
      if ($result) 
      {
        echo "<script>
                alert('Blog is  successfully updated');
                window.location.href = 'index.php';
              </script>";
      }
    }
  }
  else 
  {
    $stmt = $db->prepare("UPDATE posts SET title = :title, content = :content WHERE id = :id");
    $stmt->bindParam(':title', $title);
    $stmt->bindParam(':content', $content);
    $stmt->bindParam(':id', $id);
    $result = $stmt->execute();

      if ($result) 
      {
        echo "<script>
                alert('Blog is  successfully updated');
                window.location.href = 'index.php';
              </script>";
      }
    }
  }
 
}

?>

<?php
include('header.php');


// To show update data from posts table
$stmt = $db->prepare("SELECT * FROM posts WHERE id=".$_GET['id']);
$stmt->execute();
$result = $stmt->fetchAll();

?>

<!-- Main content -->
<div class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">Blog Table</h3>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <form action="" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <input type="hidden" name="id" value="<?php echo $result[0]['id']; ?>">
                <label>Title</label>
                <p style="color: red;"><?php echo empty($titleError) ? '' : '*'.$titleError; ?></p>
                <input class="form-control" type="text" name="title" value="<?php echo $result[0]['title']; ?>">
              </div>
              <div class="form-group">
                <label>Content</label>
                <p style="color: red;"><?php echo empty($contentError) ? '' : '*'.$contentError; ?></p>
                <textarea class="form-control" name="content" rows="4" cols="50"><?php echo $result[0]['content']; ?></textarea>
              </div><br>
              <div class="form-group">
                <label>Image</label>
                <img src="images/<?php echo $result[0]['image']; ?>" width="150" height="150" alt=""><br><br>
                <input class="form-control" type="file" name="image">
              </div>
              <div class="form-group">
                <input class="btn btn-success" type="submit" value="Update">
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
