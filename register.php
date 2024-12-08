<?php
require_once 'core/dbConfig.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Collect and sanitize user input
  $username = htmlspecialchars($_POST['username']);
  $first_name = htmlspecialchars($_POST['first_name']);
  $last_name = htmlspecialchars($_POST['last_name']);
  $email = htmlspecialchars($_POST['email']);
  $password = htmlspecialchars($_POST['password']);
  $confirm_password = htmlspecialchars($_POST['confirm_password']);

  // Check if passwords match
  if ($password !== $confirm_password) {
    $error = "Passwords do not match.";
  } else {
    // Hash the password for security
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query to insert the new user
    $query = "INSERT INTO user (username, first_name, last_name, email, password) 
                  VALUES (:username, :first_name, :last_name, :email, :password)";
    $stmt = $pdo->prepare($query);

    // Bind the parameters
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':first_name', $first_name);
    $stmt->bindParam(':last_name', $last_name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $hashed_password);

    // Execute the query
    if ($stmt->execute()) {
      header("Location: login.php"); // Redirect to login page after successful registration
      exit();
    } else {
      $error = "Error: Could not register user.";
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-900">
  <div class="flex items-center justify-center min-h-screen px-4 py-12">
    <div class="border border-gray-700 rounded-lg p-8 max-w-md w-full bg-gray-800 shadow-xl">
      <form action="register.php" method="POST" class="space-y-6">
        <div class="mb-6">
          <h3 class="text-white text-3xl font-extrabold text-center">Create Your Account</h3>
        </div>
        <?php if (isset($error)) {
          echo "<p class='text-red-500 text-center'>$error</p>";
        } ?>
        <div>
          <label for="username" class="text-gray-300 text-sm mb-2 block">Username</label>
          <input type="text" name="username" id="username" required
            class="w-full text-sm text-white bg-gray-700 border border-gray-600 px-4 py-3 rounded-lg focus:ring-2 focus:ring-indigo-500"
            placeholder="Enter username">
        </div>
        <div>
          <label for="first_name" class="text-gray-300 text-sm mb-2 block">First Name</label>
          <input type="text" name="first_name" id="first_name" required
            class="w-full text-sm text-white bg-gray-700 border border-gray-600 px-4 py-3 rounded-lg focus:ring-2 focus:ring-indigo-500"
            placeholder="Enter first name">
        </div>
        <div>
          <label for="last_name" class="text-gray-300 text-sm mb-2 block">Last Name</label>
          <input type="text" name="last_name" id="last_name" required
            class="w-full text-sm text-white bg-gray-700 border border-gray-600 px-4 py-3 rounded-lg focus:ring-2 focus:ring-indigo-500"
            placeholder="Enter last name">
        </div>
        <div>
          <label for="email" class="text-gray-300 text-sm mb-2 block">Email</label>
          <input type="email" name="email" id="email" required
            class="w-full text-sm text-white bg-gray-700 border border-gray-600 px-4 py-3 rounded-lg focus:ring-2 focus:ring-indigo-500"
            placeholder="Enter email">
        </div>
        <div>
          <label for="password" class="text-gray-300 text-sm mb-2 block">Password</label>
          <input type="password" name="password" id="password" required
            class="w-full text-sm text-white bg-gray-700 border border-gray-600 px-4 py-3 rounded-lg focus:ring-2 focus:ring-indigo-500"
            placeholder="Enter password">
        </div>
        <div>
          <label for="confirm_password" class="text-gray-300 text-sm mb-2 block">Confirm Password</label>
          <input type="password" name="confirm_password" id="confirm_password" required
            class="w-full text-sm text-white bg-gray-700 border border-gray-600 px-4 py-3 rounded-lg focus:ring-2 focus:ring-indigo-500"
            placeholder="Confirm password">
        </div>
        <div>
          <button type="submit"
            class="w-full py-3 px-4 text-sm tracking-wide rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none transition duration-300 ease-in-out">
            Register
          </button>
        </div>
        <p class="text-sm text-center text-gray-300">
          Already have an account? <a href="login.php" class="text-indigo-500 font-semibold hover:underline">Login here</a>
        </p>
      </form>
    </div>
  </div>
</body>

</html>
