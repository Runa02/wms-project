<button x-data="{ theme: localStorage.getItem('theme') ?? 'light' }" x-init="if (theme === 'dark') {
    document.documentElement.classList.add('dark');
} else {
    document.documentElement.classList.remove('dark');
}"
    @click="
        theme = theme === 'light' ? 'dark' : 'light';
        localStorage.setItem('theme', theme);

        document.documentElement.classList.toggle('dark', theme === 'dark');
    "
    class="relative flex items-center justify-center text-gray-500 transition-colors bg-white border border-gray-200 rounded-full hover:text-dark-900 h-11 w-11 hover:bg-gray-100 hover:text-gray-700 dark:border-gray-800 dark:bg-gray-900 dark:text-gray-400 dark:hover:bg-gray-800 dark:hover:text-white">
    <!-- Dark Icon -->
    <svg x-show="theme === 'dark'" x-transition xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"
        width="20" height="20">
        <!-- moon icon -->
        <path fill="currentColor"
            d="M9.99998 1.5415C10.4142 1.5415 10.75 1.87729 10.75 2.2915V3.5415C10.75 3.95572 10.4142 4.2915 9.99998 4.2915Z" />
    </svg>

    <!-- Light Icon -->
    <svg x-show="theme === 'light'" x-transition xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20"
        width="20" height="20">
        <!-- sun icon -->
        <path fill="currentColor" d="M17.4547 11.97L18.1799 12.1611C18.265 11.8383 18.1265 11.4982 17.8401 11.3266Z" />
    </svg>
</button>
