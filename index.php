<?php
session_start();
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

// Ordenar por categoría: Camisas, Pantalones, Calzado, Accesorios
$sql .= " ORDER BY 
    CASE categoria 
        WHEN 'Camisas' THEN 1 
        WHEN 'Pantalones' THEN 2 
        WHEN 'Calzado' THEN 3 
        WHEN 'Accesorios' THEN 4 
        ELSE 5 
    END,
    id DESC";
$resultado = $conexion->query($sql);

// Obtener valores únicos para los filtros
$colores = $conexion->query("SELECT DISTINCT color FROM productos WHERE color IS NOT NULL ORDER BY color");
$cortes = $conexion->query("SELECT DISTINCT corte FROM productos WHERE corte IS NOT NULL ORDER BY corte");

// Obtener todas las tallas únicas de todos los productos
$tallas_query = $conexion->query("SELECT DISTINCT tallas FROM productos");
$tallas_disponibles = [];
while($row = $tallas_query->fetch_assoc()) {
    $tallas_array = json_decode($row['tallas'], true);
    if ($tallas_array) {
        $tallas_disponibles = array_merge($tallas_disponibles, $tallas_array);
    }
}
$tallas_disponibles = array_unique($tallas_disponibles);

// Ordenar tallas: primero letras (XS, S, M, L, XL, XXL), luego números
usort($tallas_disponibles, function($a, $b) {
    // Tallas de letras en orden específico
    $orden_letras = ['XS' => 1, 'S' => 2, 'M' => 3, 'L' => 4, 'XL' => 5, 'XXL' => 6, 'Única' => 999];
    
    // Si ambos son letras conocidas
    if (isset($orden_letras[$a]) && isset($orden_letras[$b])) {
        return $orden_letras[$a] - $orden_letras[$b];
    }
    
    // Si solo uno es letra
    if (isset($orden_letras[$a])) return -1;
    if (isset($orden_letras[$b])) return 1;
    
    // Si ambos son números
    if (is_numeric($a) && is_numeric($b)) {
        return intval($a) - intval($b);
    }
    
    // Comparación por defecto
    return strcmp($a, $b);
});
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
        <link rel="stylesheet" href="styles.css?v=<?php echo time(); ?>">
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
                <div id="search" class="search-container">
                    <i class="far fa-search"></i>
                    <form method="GET" action="index.php" id="search-form" class="search-form">
                        <div style="display: flex; flex-direction: column; gap: 10px;">
                            <input type="text" name="busqueda" placeholder="Buscar producto..." 
                                   value="<?php echo htmlspecialchars($busqueda); ?>"
                                   style="padding: 10px 15px; border: 1px solid var(--color-acento); border-radius: 0; width: 250px; font-family: var(--fuente-texto); font-size: 14px; color: var(--color-primario); outline: none; box-sizing: border-box;">
                            <button type="submit" style="padding: 10px 20px; background-color: var(--color-primario); color: white; border: none; border-radius: 0; cursor: pointer; font-family: var(--fuente-texto); font-size: 14px; font-weight: 600; letter-spacing: 1px; transition: background-color 0.3s; width: 100%; box-sizing: border-box;">
                                BUSCAR
                            </button>
                        </div>
                    </form>
                </div>
                <div id="user-info">
                    <?php if (isset($_SESSION['usuario_id'])): ?>
                        <div class="user-dropdown">
                            <i class="far fa-user"></i>
                            <div class="user-menu">
                                <p class="user-name"><?php echo htmlspecialchars($_SESSION['usuario_nombre']); ?></p>
                                <a href="logout.php">Cerrar Sesión</a>
                            </div>
                        </div>
                    <?php else: ?>
                        <a href="login.php"><i class="far fa-user"></i></a>
                    <?php endif; ?>
                </div>
                <div id="shopping-bag">
                    <i class="far fa-shopping-bag"></i>
                </div>
            </div>
        </div>
        
        <?php include 'includes/sidebar.php'; ?>
        <?php include 'includes/cart-sidebar.php'; ?>
        
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
        
        <footer class="footer">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>SERVICIO AL CLIENTE</h3>
                    <ul>
                        <li><a href="#">Contáctanos</a></li>
                        <li><a href="#">Envío Internacional</a></li>
                        <li><a href="#">Devoluciones Elegantes</a></li>
                        <li><a href="#">Guía de Tallas Premium</a></li>
                        <li><a href="#">Servicios VIP</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>LA MARCA</h3>
                    <ul>
                        <li><a href="#">Nuestra Filosofía</a></li>
                        <li><a href="#">Artesanía y Calidad</a></li>
                        <li><a href="#">Colecciones Exclusivas</a></li>
                        <li><a href="#">Colaboraciones</a></li>
                        <li><a href="#">Eventos Privados</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>EXPERIENCIA</h3>
                    <ul>
                        <li><a href="#">Personal Shopper</a></li>
                        <li><a href="#">Membresía Exclusiva</a></li>
                        <li><a href="#">Tarjeta de Regalo</a></li>
                        <li><a href="#">Servicio de Alteraciones</a></li>
                        <li><a href="#">Citas Privadas</a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h3>CONECTA</h3>
                    <div class="social-links">
                        <a href="#" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
                        <a href="#" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
                        <a href="#" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
                        <a href="#" aria-label="Pinterest"><i class="fab fa-pinterest-p"></i></a>
                        <a href="#" aria-label="YouTube"><i class="fab fa-youtube"></i></a>
                    </div>
                    <div class="newsletter">
                        <h4>ÚNETE A NUESTRA COMUNIDAD</h4>
                        <p>Acceso anticipado a nuevas colecciones</p>
                        <form class="newsletter-form">
                            <input type="email" placeholder="Correo electrónico" required>
                            <button type="submit">SUSCRIBIRSE</button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="footer-bottom">
                <div class="footer-legal">
                    <p>&copy; 2025 Clothing Store. Todos los derechos reservados.</p>
                    <div class="legal-links">
                        <a href="#">Política de Privacidad</a>
                        <span>|</span>
                        <a href="#">Términos y Condiciones</a>
                        <span>|</span>
                        <a href="#">Configuración de Cookies</a>
                    </div>
                    <p class="image-credits">Imágenes de producto cortesía de H&M. Sitio creado con fines educativos.</p>
                </div>
                <div class="payment-methods">
                    <i class="fab fa-cc-visa"></i>
                    <i class="fab fa-cc-mastercard"></i>
                    <i class="fab fa-cc-amex"></i>
                    <i class="fab fa-cc-paypal"></i>
                    <i class="fab fa-apple-pay"></i>
                </div>
            </div>
            
            <!-- Disclaimer oculto para propósitos educativos -->
            <div style="display: none;" aria-hidden="true">
                Las imágenes utilizadas en este sitio web son propiedad de H&M Hennes & Mauritz AB. 
                Este proyecto es únicamente con fines educativos y no tiene ninguna afiliación comercial 
                con H&M. No se pretende infringir ningún derecho de autor.
            </div>
        </footer>
    </body>
</html>
