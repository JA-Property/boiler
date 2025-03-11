<?php
/**
 * /public/setup/Views/_layout.php
 */

// The controller’s render() might call `extract($data)` which includes $step
// If not, you can parse PATH_INFO again (but typically you'll pass $step to the view).
// For now let's assume $step is passed in.

function activeClass($current, $target)
{
    return ($current === $target) ? 'bg-gray-200 text-gray-900' : 'text-gray-700 hover:bg-gray-100';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Web App Setup</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@latest/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"/>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">
    <header class="bg-white shadow py-4 px-8">
        <h1 class="text-2xl font-bold text-gray-800">Setup Wizard</h1>
    </header>

    <div class="flex flex-grow overflow-hidden">
        <!-- Sidebar Tabs -->
        <aside class="w-64 bg-white border-r border-gray-200 p-4">
            <nav class="space-y-2">
                <!-- Database Tab -->
                <a href="/setup/database"
                   class="flex items-center px-4 py-2 text-sm font-semibold rounded transition-colors <?= activeClass($step, 'database') ?>">
                   <i class="fas fa-database w-5 mr-2"></i>
                   Database
                </a>

                <!-- DB Tables Tab -->
                <a href="/setup/db_tables"
                   class="flex items-center px-4 py-2 text-sm font-semibold rounded transition-colors <?= activeClass($step, 'db_tables') ?>">
                   <i class="fas fa-table w-5 mr-2"></i>
                   DB Tables
                </a>

                <!-- App Settings Tab -->
                <a href="/setup/app_settings"
                   class="flex items-center px-4 py-2 text-sm font-semibold rounded transition-colors <?= activeClass($step, 'app_settings') ?>">
                   <i class="fas fa-cogs w-5 mr-2"></i>
                   App Settings
                </a>

                <!-- Admin Account Tab -->
                <a href="/setup/admin_account"
                   class="flex items-center px-4 py-2 text-sm font-semibold rounded transition-colors <?= activeClass($step, 'admin_account') ?>">
                   <i class="fas fa-user-shield w-5 mr-2"></i>
                   Admin Account
                </a>

                <!-- Finish Tab -->
                <a href="/setup/finish"
                   class="flex items-center px-4 py-2 text-sm font-semibold rounded transition-colors <?= activeClass($step, 'finish') ?>">
                   <i class="fas fa-check-circle w-5 mr-2"></i>
                   Finish
                </a>
            </nav>
        </aside>
        <main class="flex-grow p-8 overflow-auto bg-gray-50">
            <?= $content; // Step-specific content ?>
        </main>
    </div>

    <footer class="bg-white border-t border-gray-200 py-4 px-8">
        <div class="text-sm text-gray-500">&copy; <?= date('Y'); ?> My Web App</div>
    </footer>
</body>
</html>
