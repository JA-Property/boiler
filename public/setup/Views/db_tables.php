<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Enterprise Setup: Create Database Tables</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@latest/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>
  <script>
    function runMigrations() {
      const btn = document.getElementById("runMigrationsButton");
      // Disable the button and show a spinner icon
      btn.disabled = true;
      btn.innerHTML = `<i class="fas fa-spinner fa-spin mr-2"></i> Creating Tables...`;
      // Submit the form (controller will process the POST and eventually redirect)
      document.getElementById("migrationsForm").submit();
    }
  </script>
</head>
<body class="bg-gray-100 flex items-center justify-center py-8">
  <div class="bg-white shadow-lg rounded-lg p-8 max-w-xl w-full">
    <div class="flex items-center mb-6">
      <i class="fas fa-table text-3xl text-green-600 mr-4"></i>
      <h2 class="text-2xl font-bold text-gray-800">Database Tables Setup</h2>
    </div>
    <p class="text-gray-700 mb-6">
      Your database connection has been successfully configured. Now it's time to create the necessary tables for your CRM system. This process may take a few moments. Please click the button below to initiate the table creation process.
    </p>

    <!-- Optional: Display any migration-related error messages -->
    <?php if (!empty($migrationError)): ?>
      <p class="text-red-600 font-semibold mb-4">
        <i class="fas fa-exclamation-circle mr-2"></i>
        Error: <?= htmlspecialchars($migrationError); ?>
      </p>
    <?php endif; ?>

    <form method="POST" id="migrationsForm" class="space-y-6">
      <div class="flex justify-center">
        <button type="button" id="runMigrationsButton" onclick="runMigrations()"
                class="px-6 py-3 bg-green-600 text-white font-semibold rounded hover:bg-green-700 transition duration-200 focus:outline-none">
          <i class="fas fa-play mr-2"></i>
          Run Migrations &amp; Create Tables
        </button>
      </div>
    </form>
  </div>
</body>
</html>
