<!-- /public/setup/Views/database.php -->

  <div class="bg-white shadow-lg rounded-lg p-8 max-w-xl w-full">
    <div class="flex items-center mb-6">
      <i class="fas fa-database text-3xl text-blue-600 mr-4"></i>
      <h2 class="text-2xl font-bold text-gray-800">Database Setup</h2>
    </div>
    <p class="text-gray-700 mb-6">
      Configure your database connection details below. Use the <strong>Test Connection</strong> button to verify your settings before proceeding.
    </p>

    <!-- Error Messages -->
    <?php if (!empty($dbPassError)): ?>
      <p class="text-red-600 font-semibold mb-4">
        <i class="fas fa-exclamation-triangle mr-2"></i>
        Error: DB_PASS cannot be blank unless “Allow empty DB_PASS” is checked.
      </p>
    <?php endif; ?>
    <?php if (!empty($dbErrorMessage)): ?>
      <p class="text-red-600 font-semibold mb-4">
        <i class="fas fa-exclamation-circle mr-2"></i>
        Database error: <?= htmlspecialchars($dbErrorMessage); ?>
      </p>
    <?php endif; ?>

    <form method="POST" class="space-y-6">
      <div>
        <label for="DB_HOST" class="block text-sm font-medium text-gray-700">
          <i class="fas fa-server mr-1"></i> DB Host:
        </label>
        <input type="text" id="DB_HOST" name="DB_HOST" class="mt-1 block w-full p-2 border rounded focus:ring-blue-500 focus:border-blue-500"
               value="<?= htmlspecialchars($existing['DB_HOST'] ?? 'localhost'); ?>" />
      </div>
      <div>
        <label for="DB_NAME" class="block text-sm font-medium text-gray-700">
          <i class="fas fa-database mr-1"></i> DB Name:
        </label>
        <input type="text" id="DB_NAME" name="DB_NAME" class="mt-1 block w-full p-2 border rounded focus:ring-blue-500 focus:border-blue-500"
               value="<?= htmlspecialchars($existing['DB_NAME'] ?? ''); ?>" />
      </div>
      <div>
        <label for="DB_USER" class="block text-sm font-medium text-gray-700">
          <i class="fas fa-user mr-1"></i> DB User:
        </label>
        <input type="text" id="DB_USER" name="DB_USER" class="mt-1 block w-full p-2 border rounded focus:ring-blue-500 focus:border-blue-500"
               value="<?= htmlspecialchars($existing['DB_USER'] ?? ''); ?>" />
      </div>
      <div>
        <label for="DB_PASS" class="block text-sm font-medium text-gray-700">
          <i class="fas fa-lock mr-1"></i> DB Pass:
        </label>
        <input type="text" id="DB_PASS" name="DB_PASS" class="mt-1 block w-full p-2 border rounded focus:ring-blue-500 focus:border-blue-500"
               value="<?= htmlspecialchars($existing['DB_PASS'] ?? ''); ?>" />
        <div class="mt-2 flex items-center">
          <input type="checkbox" id="allow_empty_password" name="allow_empty_password"
            <?php if (!empty($existing['ALLOW_EMPTY_DB_PASS']) && strtolower($existing['ALLOW_EMPTY_DB_PASS']) === 'true'): ?>
              checked
            <?php endif; ?>
            class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <label for="allow_empty_password" class="ml-2 text-sm text-gray-600">Allow empty DB_PASS</label>
        </div>
      </div>
      <div>
        <label class="inline-flex items-center">
          <input type="checkbox" name="create_db_if_missing" value="1" class="h-4 w-4 text-blue-600 border-gray-300 rounded" />
          <span class="ml-2 text-gray-700">Create DB if missing</span>
        </label>
      </div>
      <div class="flex space-x-4 items-center">
        <button type="button" id="testConnectionButton" onclick="testConnection()"
                class="flex-1 px-4 py-2 bg-green-600 text-white font-semibold rounded hover:bg-green-700 transition duration-200 focus:outline-none">
          <i id="testIcon" class="fas fa-plug mr-2"></i>
          Test Connection
        </button>
        <button type="submit" id="saveButton" disabled
                class="flex-1 px-4 py-2 bg-blue-600 text-white font-semibold rounded opacity-50 cursor-not-allowed transition duration-200 focus:outline-none">
          <i class="fas fa-save mr-2"></i>
          Save &amp; Continue
        </button>
      </div>
      <div id="testMessage" class="mt-4"></div>
    </form>
  </div>

  <script>
    async function testConnection() {
      // Get references to buttons, icons, and messages.
      const testBtn = document.getElementById("testConnectionButton");
      const saveBtn = document.getElementById("saveButton");
      const testIcon = document.getElementById("testIcon");
      const testMessage = document.getElementById("testMessage");

      // Disable test button while processing.
      testBtn.disabled = true;
      testIcon.className = "fas fa-spinner fa-spin mr-2";
      testMessage.textContent = "";

      // Gather the form values.
      const payload = {
        DB_HOST: document.getElementById("DB_HOST").value,
        DB_NAME: document.getElementById("DB_NAME").value,
        DB_USER: document.getElementById("DB_USER").value,
        DB_PASS: document.getElementById("DB_PASS").value,
        allow_empty_password: document.getElementById("allow_empty_password").checked,
        create_db_if_missing: document.querySelector("input[name='create_db_if_missing']").checked
      };

      try {
        // Send the AJAX request to the MVC test connection endpoint.
        const response = await fetch("/setup/test_connection", {
          method: "POST",
          headers: { "Content-Type": "application/json" },
          body: JSON.stringify(payload)
        });
        const result = await response.json();

        if (result.success) {
          // On success, update icon and message, and enable the Save button.
          testIcon.className = "fas fa-check-circle mr-2 text-green-600";
          testMessage.textContent = "Connection successful!";
          testMessage.className = "text-green-600 font-semibold mt-2";
          saveBtn.disabled = false;
          saveBtn.classList.remove("opacity-50", "cursor-not-allowed");
        } else {
          // On failure, update icon and show error message.
          testIcon.className = "fas fa-times-circle mr-2 text-red-600";
          testMessage.textContent = result.message || "Connection failed. Please verify your credentials.";
          testMessage.className = "text-red-600 font-semibold mt-2";
        }
      } catch (error) {
        testIcon.className = "fas fa-times-circle mr-2 text-red-600";
        testMessage.textContent = "Error testing connection. Please try again.";
        testMessage.className = "text-red-600 font-semibold mt-2";
      }
      // Re-enable the test connection button.
      testBtn.disabled = false;
    }
  </script>
</body>
</html>
