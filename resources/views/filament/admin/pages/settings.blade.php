<x-filament-panels::page>
    <div class="space-y-6">
        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Application Settings</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Application Name
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ config('app.name') }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Environment
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ config('app.env') }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Debug Mode
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ config('app.debug') ? 'Enabled' : 'Disabled' }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Laravel Version
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ app()->version() }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Database Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Connection
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ config('database.default') }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Database
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ config('database.connections.' . config('database.default') . '.database') }}
                    </p>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">System Information</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        PHP Version
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ PHP_VERSION }}
                    </p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Timezone
                    </label>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        {{ config('app.timezone') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
