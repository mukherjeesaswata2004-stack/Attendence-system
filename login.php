<?php
session_start();

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

    $sql = "SELECT * FROM users WHERE id_no = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $id_no);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['password'])) {
            $_SESSION['id_no'] = $row['id_no'];
            header("Location: main.php"); // ✅ Redirect to main page
            exit();
        } else {
            $msg = "<div class='alert alert-danger text-center'>❌ Invalid Password</div>";
        }
    } else {
        $msg = "<div class='alert alert-danger text-center'>❌ ID Not Found</div>";
    }

    $stmt->close();
}
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Rural Attendance System - Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
  <div class="container mt-5">
    <div class="card shadow p-4">
      <h2 class="text-center mb-3">🌾 Rural Attendance System</h2>
      <h4 class="text-center mb-4">Login</h4>
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
        <button type="submit" class="btn btn-success w-100">Login</button>
        <p class="text-center">crreate new account! <a href="signup.php">signup</a></p>
      </form>
    </div>
  </div>
</body>
</html>