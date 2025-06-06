<div class="min-h-screen bg-gray-50 flex items-center justify-center">
    <div class="max-w-md w-full text-center">
        <div class="mb-8">
            <div class="mx-auto w-32 h-32 bg-red-100 rounded-full flex items-center justify-center mb-6">
                <svg class="w-16 h-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
            </div>
            <h1 class="text-6xl font-bold text-gray-900 mb-4">500</h1>
            <h2 class="text-2xl font-semibold text-gray-800 mb-4">Internal Server Error</h2>
            <p class="text-gray-600 mb-8">
                Oops! Something went wrong on our end. We're working to fix this issue. Please try again in a few moments.
            </p>
        </div>

        <div class="space-y-4">
            <button onclick="window.location.reload()" 
                    class="inline-flex items-center px-6 py-3 bg-black text-white font-medium rounded-lg hover:bg-gray-800 transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Try Again
            </button>
            
            <div class="flex flex-col sm:flex-row gap-2 justify-center">
                <a href="/Webgiay/" 
                   class="px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    Go Home
                </a>
                <a href="/Webgiay/contact" 
                   class="px-4 py-2 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition-colors">
                    Report Issue
                </a>
            </div>
        </div>

        <div class="mt-12 text-sm text-gray-500">
            <p>Error Code: 500 - Internal Server Error</p>
            <p>Our team has been notified. If the problem persists, please <a href="/Webgiay/contact" class="text-black hover:underline">contact us</a>.</p>
        </div>
    </div>
</div>
