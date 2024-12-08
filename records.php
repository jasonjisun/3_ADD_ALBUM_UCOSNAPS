<?php
require_once 'core/dbConfig.php'; // Include your database connection

// Get the search query if set
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Modify the query to filter by student name or course name
$query = "SELECT * FROM collage_records WHERE student_name LIKE :search OR course_name LIKE :search";
$stmt = $pdo->prepare($query);

// Bind the search parameter with wildcard for LIKE search
$stmt->execute(['search' => "%" . $search . "%"]);
$records = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Collage Records</title>
  <link href="https://cdn.jsdelivr.net/npm/flowbite@2.5.2/dist/flowbite.min.css" rel="stylesheet" />
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-r from-teal-100 to-blue-100">

  <?php include 'navbar.php'; ?>

  <div class="container mx-auto py-8 px-4">
    <h1 class="text-4xl font-extrabold mb-8 text-center text-teal-900">
      <?php echo empty($records) ? 'No records found!' : 'Records'; ?>
    </h1>

    <!-- Search Bar -->
    <div class="mb-6 flex justify-center">
      <form action="" method="GET" class="w-full max-w-lg">
        <div class="relative">
          <input type="text" name="search" value="<?php echo htmlspecialchars($search); ?>" 
            class="w-full px-6 py-3 rounded-lg border-2 border-teal-500 focus:outline-none focus:ring-2 focus:ring-teal-600 text-gray-700"
            placeholder="Search by Student Name or Course Name" />
          <button type="submit" class="absolute right-2 top-2 text-teal-600">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </form>
    </div>

    <!-- Table Section -->
    <div class="overflow-x-auto bg-white rounded-lg shadow-xl">
      <table class="w-full bg-white border-collapse border border-gray-300">
        <thead class="bg-teal-500 text-white">
          <tr>
            <th class="py-4 px-6 border-b text-left text-sm font-medium">Record ID</th>
            <th class="py-4 px-6 border-b text-left text-sm font-medium">Student ID</th>
            <th class="py-4 px-6 border-b text-left text-sm font-medium">Student Name</th>
            <th class="py-4 px-6 border-b text-left text-sm font-medium">Course Name</th>
            <th class="py-4 px-6 border-b text-left text-sm font-medium">Date of Enrollment</th>
            <th class="py-4 px-6 border-b text-left text-sm font-medium">Grade</th>
            <th class="py-4 px-6 border-b text-left text-sm font-medium">Status</th>
            <th class="py-4 px-6 border-b text-left text-sm font-medium">Created At</th>
            <th class="py-4 px-6 border-b text-left text-sm font-medium">Updated At</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($records as $record): ?>
            <tr class="hover:bg-teal-50">
              <td class="py-3 px-6 border-b text-sm text-gray-800"><?php echo $record['record_id']; ?></td>
              <td class="py-3 px-6 border-b text-sm text-gray-800"><?php echo $record['student_id']; ?></td>
              <td class="py-3 px-6 border-b text-sm text-gray-800"><?php echo htmlspecialchars($record['student_name']); ?></td>
              <td class="py-3 px-6 border-b text-sm text-gray-800"><?php echo htmlspecialchars($record['course_name']); ?></td>
              <td class="py-3 px-6 border-b text-sm text-gray-800"><?php echo $record['date_of_enrollment']; ?></td>
              <td class="py-3 px-6 border-b text-sm text-gray-800"><?php echo $record['grade']; ?></td>
              <td class="py-3 px-6 border-b text-sm text-gray-800"><?php echo $record['status']; ?></td>
              <td class="py-3 px-6 border-b text-sm text-gray-800"><?php echo $record['created_at']; ?></td>
              <td class="py-3 px-6 border-b text-sm text-gray-800"><?php echo $record['updated_at']; ?></td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>

</body>

</html>
