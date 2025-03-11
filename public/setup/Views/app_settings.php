<div class="bg-white shadow-lg rounded-lg p-8 max-w-xl w-full">
  <div class="flex items-center mb-6">
    <i class="fas fa-cogs text-3xl text-indigo-600 mr-4"></i>
    <h2 class="text-2xl font-bold text-gray-800">Application Settings</h2>
  </div>
  <p class="text-gray-700 mb-6">
    Configure your application environment below. Set the correct environment mode and debug settings to match your enterprise requirements.
  </p>
  <form method="POST" class="space-y-6">
    <div>
      <label for="APP_ENV" class="block text-sm font-medium text-gray-700">
        <i class="fas fa-adjust mr-1"></i> Application Environment:
      </label>
      <input type="text" id="APP_ENV" name="APP_ENV" class="mt-1 block w-full p-2 border rounded focus:ring-indigo-500 focus:border-indigo-500"
             value="<?= htmlspecialchars($existing['APP_ENV'] ?? 'production'); ?>" />
    </div>
    <div>
      <label for="APP_DEBUG" class="block text-sm font-medium text-gray-700">
        <i class="fas fa-bug mr-1"></i> Application Debug Mode:
      </label>
      <select id="APP_DEBUG" name="APP_DEBUG" class="mt-1 block w-full p-2 border rounded focus:ring-indigo-500 focus:border-indigo-500">
        <option value="true" <?= (isset($existing['APP_DEBUG']) && strtolower($existing['APP_DEBUG']) === 'true') ? 'selected' : ''; ?>>True</option>
        <option value="false" <?= (isset($existing['APP_DEBUG']) && strtolower($existing['APP_DEBUG']) === 'false') ? 'selected' : ''; ?>>False</option>
      </select>
    </div>
    <div class="flex justify-end">
      <button type="submit" class="px-4 py-2 bg-indigo-600 text-white font-semibold rounded hover:bg-indigo-700 transition duration-200">
        <i class="fas fa-save mr-2"></i> Save &amp; Continue
      </button>
    </div>
  </form>
</div>
