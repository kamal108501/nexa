<x-filament::section>
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
        <!-- Active Tips -->
        <div class="rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="px-6 py-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Tips</p>
                        <p class="mt-3 text-3xl font-semibold tracking-tight text-gray-950 dark:text-white">{{ $active }}</p>
                    </div>
                    <div class="inline-flex rounded-lg bg-blue-50 p-2 dark:bg-blue-500/10">
                        <svg class="h-4 w-4 text-blue-600 dark:text-blue-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                </div>
                <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">Currently active</p>
            </div>
        </div>

        <!-- Completed -->
        <div class="rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="px-6 py-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Completed</p>
                        <p class="mt-3 text-3xl font-semibold tracking-tight text-gray-950 dark:text-white">{{ $completed }}</p>
                    </div>
                    <div class="inline-flex rounded-lg bg-green-50 p-2 dark:bg-green-500/10">
                        <svg class="h-4 w-4 text-green-600 dark:text-green-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">Target achieved</p>
            </div>
        </div>

        <!-- SL Hit -->
        <div class="rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="px-6 py-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">SL Hit</p>
                        <p class="mt-3 text-3xl font-semibold tracking-tight text-gray-950 dark:text-white">{{ $slHit }}</p>
                    </div>
                    <div class="inline-flex rounded-lg bg-red-50 p-2 dark:bg-red-500/10">
                        <svg class="h-4 w-4 text-red-600 dark:text-red-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                    </div>
                </div>
                <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">Stop loss triggered</p>
            </div>
        </div>

        <!-- Expired -->
        <div class="rounded-lg border border-gray-200 bg-white shadow-sm dark:border-gray-700 dark:bg-gray-800">
            <div class="px-6 py-6">
                <div class="flex items-start justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Expired</p>
                        <p class="mt-3 text-3xl font-semibold tracking-tight text-gray-950 dark:text-white">{{ $expired }}</p>
                    </div>
                    <div class="inline-flex rounded-lg bg-amber-50 p-2 dark:bg-amber-500/10">
                        <svg class="h-4 w-4 text-amber-600 dark:text-amber-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5-15a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                </div>
                <p class="mt-2 text-xs text-gray-600 dark:text-gray-400">Expired tips</p>
            </div>
        </div>
    </div>
</x-filament::section>