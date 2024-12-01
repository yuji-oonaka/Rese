<script>
function toggleMenu() {
    const menuOverlay = document.getElementById('menu-overlay');
    menuOverlay.classList.toggle('active');
}

document.addEventListener('click', function(event) {
    const menuOverlay = document.getElementById('menu-overlay');
    if (menuOverlay.classList.contains('active') && event.target === menuOverlay) {
        menuOverlay.classList.remove('active');
    }
});
</script>