<button x-data="{
        theme: localStorage.theme || (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light'),
        toggleTheme() {
            this.theme = this.theme === 'dark' ? 'light' : 'dark';
            localStorage.theme = this.theme;
            if (this.theme === 'dark') {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        }
    }" 
    @click="toggleTheme()" 
    {{ $attributes->merge(['class' => 'p-2 text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 dark:text-gray-400 dark:hover:text-white focus:outline-none focus:ring-2 focus:ring-teal-500 rounded-lg transition-colors']) }}
    title="Toggle Dark Mode">
    
    <i class="fas fa-sun text-lg" x-show="theme === 'dark'" x-cloak></i>
    <i class="fas fa-moon text-lg" x-show="theme === 'light'" x-cloak></i>
</button>
