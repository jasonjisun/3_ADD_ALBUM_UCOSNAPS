<?php
require_once 'core/dbConfig.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
  // Redirect to login page if not logged in
  header("Location: login.php");
  exit();
}

// Query to get all the photos from the database
$query = "SELECT * FROM photos";
$stmt = $pdo->prepare($query);
$stmt->execute();

// Fetch the result
$result = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Home</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-blue-100 to-purple-100">

  <?php include 'navbar.php'; ?>

  <div class="container mx-auto px-6 py-10">
    <h1 class="text-4xl font-extrabold mb-10 text-center text-blue-900">
      <?php if (empty($result)) {
        echo 'No photos uploaded, be the first one!';
      } else {
        echo 'Uploaded Photos';
      } ?>
    </h1>

    <!-- Photo grid layout -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-12">
      <?php foreach ($result as $row) { ?>
        <div class="bg-white rounded-lg shadow-xl overflow-hidden transform hover:scale-105 transition-all duration-500 hover:shadow-2xl">
          <img src="uploaded_photos/<?php echo htmlspecialchars($row['photo_name']); ?>" alt="Photo"
            class="w-full h-56 object-cover mb-5 rounded-lg transition-transform duration-300">
          
          <div class="p-5">
            <h3 class="text-xl font-semibold text-gray-900 mb-3"><?php echo htmlspecialchars($row['title']); ?></h3>
            <p class="text-gray-700 text-sm mb-5"><?php echo htmlspecialchars($row['description']); ?></p>
            <div class="flex justify-between items-center text-sm">
              <!-- Edit Button -->
              <a href="edit_photo.php?id=<?php echo $row['photo_id']; ?>"
                class="text-indigo-600 hover:text-indigo-800 transition duration-300">Edit</a>
              <!-- Delete Button -->
              <a href="delete_photo.php?id=<?php echo $row['photo_id']; ?>"
                class="text-red-600 hover:text-red-800 transition duration-300">Delete</a>
            </div>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
</body>

</html>
