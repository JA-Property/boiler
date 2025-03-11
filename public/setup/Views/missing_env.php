<!-- /public/setup/Views/missing_env.php -->

<div class="bg-white shadow-lg rounded-lg p-10 max-w-xl w-full">
    <h1 class="text-3xl font-bold text-red-600 mb-4">Critical Error: .env File Not Found</h1>
    <p class="text-lg text-gray-800 mb-6">
      The system could not locate your environment configuration file <code>.env</code> in the expected project root. This file is essential for configuring your database connections, application environment, and security settings.
    </p>
    <?php if(isset($_SESSION['setup_error'])): ?>
      <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-6" role="alert">
        <strong class="font-bold">Error:</strong>
        <span class="block sm:inline"><?= $_SESSION['setup_error']['message'] ?></span>
      </div>
      <?php unset($_SESSION['setup_error']); ?>
    <?php endif; ?>
    <div class="mb-6">
      <h2 class="text-xl font-semibold text-gray-900 mb-2">Next Steps:</h2>
      <ol class="list-decimal list-inside text-gray-700">
        <li>Click the button below to automatically create a default <code>.env</code> file.</li>
        <li>Review and update the file with your enterprise-specific configuration settings.</li>
        <li>Proceed to the Database Configuration step to establish proper connection settings.</li>
      </ol>
    </div>
    <form method="POST" action="/setup/missing_env">
      <button type="submit"
              class="w-full px-4 py-3 bg-blue-600 text-white font-semibold rounded hover:bg-blue-700 transition duration-200">
        Create Default .env and Continue Setup
      </button>
    </form>
  </div>