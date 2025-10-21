// Lógica para alternar el menú lateral (Sidebar) en dispositivos móviles
document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const mainContent = document.getElementById('main-content');

    if (!sidebar || !sidebarToggle || !mainContent) {
        console.error("No se encontraron todos los elementos necesarios para el sidebar.");
        return;
    }

    // Función para alternar la visibilidad de la barra lateral
    function toggleSidebar() {
        sidebar.classList.toggle('-translate-x-full');

        // La lógica de margen es principalmente estética para entornos con scroll. 
        // En este layout flexible, la clave es la clase -translate-x-full
        if (window.innerWidth >= 1024) {
            // Esto solo se aplica si la barra lateral tiene una forma de empujar el contenido
            // (que en este caso, lo estamos manejando con la clase lg:ml-0 en main-content)
        }
    }

    sidebarToggle.addEventListener('click', toggleSidebar);

    // Ocultar el sidebar en móvil al hacer clic fuera de él
    document.addEventListener('click', (e) => {
        // Solo aplica si estamos en móvil y el sidebar está abierto
        if (window.innerWidth < 1024 && !sidebar.contains(e.target) && !sidebarToggle.contains(e.target) && !sidebar.classList.contains('-translate-x-full')) {
            toggleSidebar();
        }
    });

    // Asegurar que el sidebar se muestre por defecto en desktop al redimensionar
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            sidebar.classList.remove('-translate-x-full');
        }
    });
});
