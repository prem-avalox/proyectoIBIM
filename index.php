<?php
require_once 'config/conexion.php';

// Obtener parámetros de filtro y búsqueda
$busqueda = isset($_GET['busqueda']) ? limpiar_entrada($_GET['busqueda']) : '';
$filtro_talla = isset($_GET['talla']) ? limpiar_entrada($_GET['talla']) : '';
$filtro_color = isset($_GET['color']) ? limpiar_entrada($_GET['color']) : '';
$filtro_corte = isset($_GET['corte']) ? limpiar_entrada($_GET['corte']) : '';

// Obtener categoría del filtro
$categoria = isset($_GET['categoria']) ? $_GET['categoria'] : '';

// Construir consulta SQL con filtros
$sql = "SELECT * FROM productos WHERE 1=1";

// Filtro de búsqueda por nombre
if (!empty($busqueda)) {
    $sql .= " AND nombre LIKE '%" . $conexion->real_escape_string($busqueda) . "%'";
}

// Filtro de talla (buscar en JSON)
if (!empty($filtro_talla)) {
    $sql .= " AND JSON_CONTAINS(tallas, '\"" . $conexion->real_escape_string($filtro_talla) . "\"')";
}

// Filtro de color
if (!empty($filtro_color)) {
    $sql .= " AND color = '" . $conexion->real_escape_string($filtro_color) . "'";
}

// Filtro de corte
if (!empty($filtro_corte)) {
    $sql .= " AND corte = '" . $conexion->real_escape_string($filtro_corte) . "'";
}

// Filtro de categoría
if ($categoria) {
    $sql .= " AND categoria = '" . $conexion->real_escape_string($categoria) . "'";
}

$sql .= " ORDER BY id DESC";
$resultado = $conexion->query($sql);

// Obtener valores únicos para los filtros
$colores = $conexion->query("SELECT DISTINCT color FROM productos WHERE color IS NOT NULL ORDER BY color");
$cortes = $conexion->query("SELECT DISTINCT corte FROM productos WHERE corte IS NOT NULL ORDER BY corte");
$tallas_disponibles = ['XS', 'S', 'M', 'L', 'XL', 'XXL'];
?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link
            href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@100;200;300;400;500;600;700;800;900&family=Roboto+Condensed:wght@100;200;300;400;500;600;700;800;900&display=swap"
            rel="stylesheet">
        <link rel="stylesheet" href="styles.css">
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <title>Clothing Store - Inicio</title>
    </head>
    <body class="general">
        <div class="header">
            <div class="filter-bar">
                <i class="far fa-bars"></i>
            </div>
            <div class="logo">
                <a href="index.php" style="text-decoration: none; color: inherit;">
                    <h1>CLOTHING STORE</h1>
                </a>
            </div>
            <div class="button-container">
                <div id="search" style="position: relative;">
                    <i class="far fa-search" onclick="toggleSearch()"></i>
                    <form method="GET" action="index.php" id="search-form" style="display: none; position: absolute; top: 100%; right: 0; margin-top: 10px;">
                        <input type="text" name="busqueda" placeholder="Buscar producto..." 
                               value="<?php echo htmlspecialchars($busqueda); ?>"
                               style="padding: 8px; border: 1px solid #ccc; border-radius: 4px; width: 200px;">
                        <button type="submit" style="padding: 8px 12px; background-color: var(--color-primario); color: white; border: none; border-radius: 4px; cursor: pointer; margin-left: 5px;">
                            Buscar
                        </button>
                    </form>
                </div>
                <div id="user-info">
                    <i class="far fa-user"></i>
                </div>
                <div id="shopping-bag">
                    <i class="far fa-shopping-bag"></i>
                </div>
            </div>
        </div>
        <!-- Sidebar -->
        <div id="sidebar" class="sidebar">
            <div class="sidebar-header">
                <h2>CATEGORÍAS</h2>
                <i class="fas fa-times close-btn" onclick="toggleSidebar()"></i>
            </div>
            <ul class="sidebar-menu">
                <li><a href="index.php?categoria=camisas">Camisas</a></li>
                <li><a href="index.php?categoria=pantalones">Pantalones</a></li>
                <li><a href="index.php?categoria=calzado">Calzado</a></li>
                <li><a href="index.php?categoria=accesorios">Accesorios</a></li>
            </ul>
        </div>

        <!-- Overlay -->
        <div id="overlay" class="overlay" onclick="toggleSidebar()"></div>

        <script>
            function toggleSearch() {
                const form = document.getElementById('search-form');
                form.style.display = form.style.display === 'none' ? 'block' : 'none';
            }

            function toggleSidebar() {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('overlay');
                
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            }

            // Agregar evento al icono de barras
            document.querySelector('.filter-bar').addEventListener('click', toggleSidebar);
        </script>
        <nav class="category-nav-bar">
            <form method="GET" action="index.php" id="filter-form">
                <!-- Mantener búsqueda si existe -->
                <?php if (!empty($busqueda)): ?>
                    <input type="hidden" name="busqueda" value="<?php echo htmlspecialchars($busqueda); ?>">
                <?php endif; ?>
                
                <ul class="cajas-filtro-lista">
                    <li>
                        <div onclick="toggleDropdown('talla-dropdown')">
                            TAMAÑO <i class="fas fa-chevron-down"></i>
                        </div>
                        <div id="talla-dropdown" class="filter-dropdown" style="display: none;">
                            <label><input type="radio" name="talla" value="" <?php echo empty($filtro_talla) ? 'checked' : ''; ?> onchange="this.form.submit()"> Todas</label>
                            <?php foreach ($tallas_disponibles as $talla): ?>
                                <label><input type="radio" name="talla" value="<?php echo $talla; ?>" 
                                    <?php echo $filtro_talla === $talla ? 'checked' : ''; ?> 
                                    onchange="this.form.submit()"> <?php echo $talla; ?></label>
                            <?php endforeach; ?>
                        </div>
                    </li>
                    <li>
                        <div onclick="toggleDropdown('color-dropdown')">
                            COLOR <i class="fas fa-chevron-down"></i>
                        </div>
                        <div id="color-dropdown" class="filter-dropdown" style="display: none;">
                            <label><input type="radio" name="color" value="" <?php echo empty($filtro_color) ? 'checked' : ''; ?> onchange="this.form.submit()"> Todos</label>
                            <?php while($color = $colores->fetch_assoc()): ?>
                                <label><input type="radio" name="color" value="<?php echo htmlspecialchars($color['color']); ?>" 
                                    <?php echo $filtro_color === $color['color'] ? 'checked' : ''; ?> 
                                    onchange="this.form.submit()"> <?php echo htmlspecialchars($color['color']); ?></label>
                            <?php endwhile; ?>
                        </div>
                    </li>
                    <li>
                        <div onclick="toggleDropdown('corte-dropdown')">
                            CORTE <i class="fas fa-chevron-down"></i>
                        </div>
                        <div id="corte-dropdown" class="filter-dropdown" style="display: none;">
                            <label><input type="radio" name="corte" value="" <?php echo empty($filtro_corte) ? 'checked' : ''; ?> onchange="this.form.submit()"> Todos</label>
                            <?php while($corte = $cortes->fetch_assoc()): ?>
                                <label><input type="radio" name="corte" value="<?php echo htmlspecialchars($corte['corte']); ?>" 
                                    <?php echo $filtro_corte === $corte['corte'] ? 'checked' : ''; ?> 
                                    onchange="this.form.submit()"> <?php echo htmlspecialchars($corte['corte']); ?></label>
                            <?php endwhile; ?>
                        </div>
                    </li>
                    <li>
                        <div>
                            <button type="button" onclick="limpiarFiltros()" style="background: none; border: none; cursor: pointer; font: inherit; color: inherit;">
                                LIMPIAR FILTROS
                            </button>
                        </div>
                    </li>
                </ul>
            </form>
        </nav>
        <script>
            function toggleDropdown(id) {
                const dropdown = document.getElementById(id);
                // Cerrar otros dropdowns
                document.querySelectorAll('.filter-dropdown').forEach(d => {
                    if (d.id !== id) d.style.display = 'none';
                });
                dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
            }
            
            function limpiarFiltros() {
                window.location.href = 'index.php';
            }
            
            // Cerrar dropdowns al hacer clic fuera
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.cajas-filtro-lista')) {
                    document.querySelectorAll('.filter-dropdown').forEach(d => d.style.display = 'none');
                }
            });
        </script>
        <section class="product-section">
            <?php
            if ($resultado && $resultado->num_rows > 0) {
                while($producto = $resultado->fetch_assoc()) {
            ?>
            <div class="tarjeta-producto">
                <a href="product.php?id=<?php echo $producto['id']; ?>" style="text-decoration: none; color: inherit;">
                    <div class="imagen-producto">
                        <img src="<?php echo htmlspecialchars($producto['imagen']); ?>"
                            alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
                    </div>
                    <div class="info-producto">
                        <h3 class="nombre-producto"><?php echo htmlspecialchars($producto['nombre']); ?></h3>
                        <p class="precio-producto">$<?php echo number_format($producto['precio'], 2); ?></p>
                    </div>
                </a>
            </div>
            <?php
                }
            } else {
                echo '<p>No hay productos disponibles.</p>';
            }
            $conexion->close();
            ?>
        </section>
    </body>
</html>
