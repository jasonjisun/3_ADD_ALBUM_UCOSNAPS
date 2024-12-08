<?php
require_once 'core/dbConfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Collect and sanitize user input
  $username = htmlspecialchars($_POST['username']);
  $password = htmlspecialchars($_POST['password']);

  // Prepare the SQL query to check the user credentials
  $query = "SELECT * FROM user WHERE username = :username";
  $stmt = $pdo->prepare($query);
  $stmt->bindParam(':username', $username);
  $stmt->execute();

  $user = $stmt->fetch(PDO::FETCH_ASSOC);

  if ($user && password_verify($password, $user['password'])) {
    // If password is correct, start the session and redirect
    session_start();
    $_SESSION['user_id'] = $user['user_id'];
    $_SESSION['username'] = $user['username'];
    header("Location: index.php"); // Redirect to dashboard
    exit();
  } else {
    $error = "Invalid username or password.";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900">
  <div class="flex items-center justify-center min-h-screen px-4 py-12">
    <div class="border border-gray-700 rounded-lg p-8 max-w-md w-full bg-gray-800 shadow-xl">
      <form method="POST" class="space-y-6">
        <div class="mb-6">
          <h3 class="text-white text-3xl font-extrabold text-center">Sign In</h3>
        </div>
        <?php if (isset($error)) {
          echo "<p class='text-red-500 text-center'>$error</p>";
        } ?>
        <div>
          <label for="username" class="text-gray-300 text-sm mb-2 block">Username</label>
          <input id="username" name="username" type="text" required
            class="w-full text-sm text-white bg-gray-700 border border-gray-600 px-4 py-3 rounded-lg focus:ring-2 focus:ring-indigo-500"
            placeholder="Enter username" />
        </div>
        <div>
          <label for="password" class="text-gray-300 text-sm mb-2 block">Password</label>
          <input id="password" name="password" type="password" required
            class="w-full text-sm text-white bg-gray-700 border border-gray-600 px-4 py-3 rounded-lg focus:ring-2 focus:ring-indigo-500"
            placeholder="Enter password" />
        </div>
        <div>
          <button type="submit"
            class="w-full py-3 px-4 text-sm tracking-wide rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition duration-300 ease-in-out">
            Log in
          </button>
        </div>
        <p class="text-sm text-center text-gray-300">
          Don't have an account? <a href="register.php" class="text-indigo-500 font-semibold hover:underline">Register here</a>
        </p>
      </form>
    </div>
  </div>
</body>

</html>
