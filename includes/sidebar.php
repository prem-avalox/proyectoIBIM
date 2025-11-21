<!-- Sidebar -->
<aside id="sidebar" class="sidebar">
    <div class="sidebar-header" style="margin-top: 70px;">
        <h2>CATEGORÍAS</h2>
        <i class="fas fa-times close-btn" onclick="toggleSidebar()"></i>
    </div>
    <ul class="sidebar-menu">
        <li><a href="index.php" class="<?php echo empty($_GET['categoria']) ? 'active-category' : ''; ?>">Ver Todo</a></li>
        <li><a href="index.php?categoria=camisas" class="<?php echo (isset($_GET['categoria']) && $_GET['categoria'] == 'camisas') ? 'active-category' : ''; ?>">Camisas</a></li>
        <li><a href="index.php?categoria=pantalones" class="<?php echo (isset($_GET['categoria']) && $_GET['categoria'] == 'pantalones') ? 'active-category' : ''; ?>">Pantalones</a></li>
        <li><a href="index.php?categoria=calzado" class="<?php echo (isset($_GET['categoria']) && $_GET['categoria'] == 'calzado') ? 'active-category' : ''; ?>">Calzado</a></li>
        <li><a href="index.php?categoria=accesorios" class="<?php echo (isset($_GET['categoria']) && $_GET['categoria'] == 'accesorios') ? 'active-category' : ''; ?>">Accesorios</a></li>
    </ul>
</aside>

<!-- Overlay -->
<div id="overlay" class="overlay" onclick="toggleSidebar()"></div>

<script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        
        sidebar.classList.toggle('active');
        overlay.classList.toggle('active');
    }

    // Agregar evento al icono de barras
    document.querySelector('.filter-bar').addEventListener('click', toggleSidebar);
</script>
