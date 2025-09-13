<?php
$host = "localhost";
$user = "root";      
$pass = "";          
$db   = "myadmit";   

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id_no = $_POST['id_no'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $msg = "<div class='alert alert-danger text-center'>❌ Passwords do not match!</div>";
    } else {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (id_no, password) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $id_no, $hashed_password);

        if ($stmt->execute()) {
            header("Location: login.php"); // ✅ Redirect to login after signup
            exit();
        } else {
            $msg = "<div class='alert alert-danger text-center'>⚠ Error: " . $stmt->error . "</div>";
        }
        $stmt->close();
    }
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rural Attendance System - Signup</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <div class="card shadow p-4">
      <h2 class="text-center mb-3">🌾 Rural Attendance System</h2>
      <h4 class="text-center mb-4">Signup</h4>
      <?php echo $msg; ?>
      <form method="post" action="">
        <div class="mb-3">
          <label class="form-label">ID No</label>
          <input type="text" class="form-control" name="id_no" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="password" class="form-control" name="password" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Confirm Password</label>
          <input type="password" class="form-control" name="confirm_password" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Signup</button>
        <p class="text-center">if you have an account-><a href="login.php">login</a></p>
      </form>
    </div>
  </div>
</body>
</html>