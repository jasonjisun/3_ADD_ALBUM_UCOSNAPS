<?php
require_once 'core/dbConfig.php'; // Include dbConfig to establish database connection

if (isset($_POST['submit'])) {
  // Retrieve the title and description from the form input
  $photoDescription = $_POST['description'];
  $photoTitle = $_POST['title'];

  // Get the uploaded file's name and temporary location
  $uploadedFileName = $_FILES['photo']['name'];
  $temporaryFileName = $_FILES['photo']['tmp_name'];

  // Extract file extension
  $fileExtension = pathinfo($uploadedFileName, PATHINFO_EXTENSION);

  // Create a unique identifier for the image file name
  $uniqueIdentifier = sha1(md5(rand(1, 9999999)));

  // Concatenate the unique ID with the file extension to generate a new unique file name
  $newImageName = $uniqueIdentifier . "." . $fileExtension;

  try {
    // Insert photo details into the database
    $insertQuery = "INSERT INTO photos (photo_name, description, title) VALUES (:photo_name, :description, :title)";
    $insertStmt = $pdo->prepare($insertQuery);
    $insertStmt->bindParam(':photo_name', $newImageName);
    $insertStmt->bindParam(':description', $photoDescription);
    $insertStmt->bindParam(':title', $photoTitle);
    $insertStmt->execute();

    // Start session and retrieve user ID (assuming the user is logged in)
    session_start();
    $userId = $_SESSION['user_id'] ?? null;

    // Log the user's action if they are logged in
    if ($userId) {
      $logInsertQuery = "INSERT INTO activity_log (user_id, action, record_id) VALUES (?, ?, ?)";
      $logInsertStmt = $pdo->prepare($logInsertQuery);
      $logInsertStmt->execute([$userId, 'Added New Photo', $pdo->lastInsertId()]);
    }

    // Ensure the uploads folder exists and is writable
    $uploadDirectory = "uploaded_photos/" . $newImageName;

    if (!is_dir('uploads')) {
      mkdir('uploaded_photos', 0755, true); // Create uploads folder if it doesn't exist
    }

    // Move the uploaded file to the uploads directory
    if (move_uploaded_file($temporaryFileName, $uploadDirectory)) {
      header("Location: index.php"); // Redirect to homepage after successful upload
      exit();
    } else {
      echo "File upload failed. Please check the permissions of the uploads folder.";
    }
  } catch (PDOException $e) {
    echo "Error saving the photo to the database: " . $e->getMessage();
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Upload Photo</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex justify-center items-center min-h-screen">

  <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-md">
    <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">Upload Photo</h2>

    <form action="" method="POST" enctype="multipart/form-data">

      <div class="mb-4">
        <label for="title" class="block text-sm font-medium text-gray-700">Title:</label>
        <textarea name="title" id="title" rows="4"
          class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
          placeholder="What's on your mind?" required></textarea>
      </div>

      <div class="mb-4">
        <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
        <textarea name="description" id="description" rows="4"
          class="w-full px-4 py-2 mt-1 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
          required></textarea>
      </div>

      <div class="mb-4">
        <label for="photo" class="block text-sm font-medium text-gray-700">Choose Image:</label>
        <input type="file" name="photo" id="photo" accept="image/*"
          class="w-full text-gray-500 font-medium text-sm bg-gray-100 file:cursor-pointer cursor-pointer file:border-0 file:py-2 file:px-4 file:mr-4 file:bg-gray-800 file:hover:bg-gray-700 file:text-white rounded"
          required>
      </div>

      <div class="text-center">
        <input type="submit" name="submit" value="Post Photo"
          class="w-full py-2 px-4 bg-blue-500 text-white font-semibold rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
      </div>
    </form>
  </div>

</body>

</html>
