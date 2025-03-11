<div class="bg-white shadow-lg rounded-lg p-8 max-w-xl w-full">
  <div class="flex items-center mb-6">
    <i class="fas fa-user-shield text-3xl text-purple-600 mr-4"></i>
    <h2 class="text-2xl font-bold text-gray-800">Administrator Account Setup</h2>
  </div>
  <p class="text-gray-700 mb-6">
    Create an administrator account to manage your CRM. This account will have full access to system configurations and data.
  </p>
  <form method="POST" class="space-y-6">
    <div>
      <label for="ADMIN_USERNAME" class="block text-sm font-medium text-gray-700">
        <i class="fas fa-user mr-1"></i> Username:
      </label>
      <input type="text" id="ADMIN_USERNAME" name="ADMIN_USERNAME" class="mt-1 block w-full p-2 border rounded focus:ring-purple-500 focus:border-purple-500"
             placeholder="Enter your admin username" required />
    </div>
    <div>
      <label for="ADMIN_EMAIL" class="block text-sm font-medium text-gray-700">
        <i class="fas fa-envelope mr-1"></i> Email Address:
      </label>
      <input type="email" id="ADMIN_EMAIL" name="ADMIN_EMAIL" class="mt-1 block w-full p-2 border rounded focus:ring-purple-500 focus:border-purple-500"
             placeholder="admin@example.com" required />
    </div>
    <div>
      <label for="ADMIN_PASSWORD" class="block text-sm font-medium text-gray-700">
        <i class="fas fa-lock mr-1"></i> Password:
      </label>
      <input type="password" id="ADMIN_PASSWORD" name="ADMIN_PASSWORD" class="mt-1 block w-full p-2 border rounded focus:ring-purple-500 focus:border-purple-500"
             placeholder="Enter a strong password" required />
    </div>
    <div>
      <label for="ADMIN_CONFIRM_PASSWORD" class="block text-sm font-medium text-gray-700">
        <i class="fas fa-lock mr-1"></i> Confirm Password:
      </label>
      <input type="password" id="ADMIN_CONFIRM_PASSWORD" name="ADMIN_CONFIRM_PASSWORD" class="mt-1 block w-full p-2 border rounded focus:ring-purple-500 focus:border-purple-500"
             placeholder="Re-enter your password" required />
    </div>
    <div class="flex justify-end">
      <button type="submit" class="px-4 py-2 bg-purple-600 text-white font-semibold rounded hover:bg-purple-700 transition duration-200">
        <i class="fas fa-user-plus mr-2"></i> Create Admin Account
      </button>
    </div>
  </form>
</div>
