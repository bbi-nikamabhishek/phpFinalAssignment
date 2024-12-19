const themeToggle = document.getElementById('theme-toggle');
    
    function updateIcon() {
        if (document.body.classList.contains('dark')) {
            themeToggle.innerHTML = '<i  class="fas fa-sun" ></i>'; 
            themeToggle.title = 'Switch to Light Mode';
        } else {
            themeToggle.innerHTML = '<i class="fas fa-moon"></i>'; 
            themeToggle.title = 'Switch to Dark Mode';
        }
    }

    window.onload = updateIcon;

 
    themeToggle.addEventListener('click', function(event) {
        event.preventDefault(); 
        const currentTheme = document.body.classList.contains('dark') ? 'light' : 'dark';
        document.body.classList.remove('light', 'dark');
        document.body.classList.add(currentTheme);
        document.cookie = `theme=${currentTheme}; path=/; max-age=86400`; 
        updateIcon(); 
    });