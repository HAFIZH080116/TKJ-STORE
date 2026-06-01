<script>
    const getStoredTheme = () => localStorage.getItem('theme');
    const setStoredTheme = theme => localStorage.setItem('theme', theme);

    const getPreferredTheme = () => {
        const storedTheme = getStoredTheme();
        if (storedTheme) {
            return storedTheme;
        }
        return 'light';
    };

    const setTheme = theme => {
        document.documentElement.setAttribute('data-bs-theme', theme);
        const toggleBtn = document.getElementById('themeToggle');
        if (toggleBtn) {
            const icon = toggleBtn.querySelector('i');
            if (icon) {
                if (theme === 'dark') {
                    icon.className = 'bi bi-sun-fill text-warning';
                } else {
                    icon.className = 'bi bi-moon-stars-fill text-secondary';
                }
            }
        }
    };

    // Apply on load
    setTheme(getPreferredTheme());

    window.addEventListener('DOMContentLoaded', () => {
        setTheme(getPreferredTheme());
        
        document.getElementById('themeToggle')?.addEventListener('click', () => {
            const theme = document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            setStoredTheme(theme);
            setTheme(theme);
        });
    });
</script>
