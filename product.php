<?php
session_start();
require_once 'config/conexion.php';

// Obtener el ID del producto de la URL
$producto_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Consultar el producto específico
$sql = "SELECT * FROM productos WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $producto_id);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows == 0) {
    header("Location: index.php");
    exit();
}

$producto = $resultado->fetch_assoc();
$tallas = json_decode($producto['tallas'], true);
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <title><?php echo htmlspecialchars($producto['nombre']); ?> - Clothing Store</title>
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

    <a href="index.php" class="back-button">
        <i class="fas fa-chevron-left"></i>
        <span>Volver al catálogo</span>
    </a>

    <div class="product-detail-section">
        <div class="product-review-section">
            <img src="<?php echo htmlspecialchars($producto['imagen']); ?>"
                alt="<?php echo htmlspecialchars($producto['nombre']); ?>">
        </div>
        <div class="detail-section">
            <div class="detalle-producto">
                <h2><?php echo htmlspecialchars($producto['nombre']); ?></h2>
                <p class="precio-detalle-producto">$<?php echo number_format($producto['precio'], 2); ?></p>
            </div>
            <div class="seleccion-talla-producto">
                <p>Talla</p>
                <form id="talla-form">
                    <input type="hidden" name="producto_id" value="<?php echo $producto['id']; ?>">
                    <ul class="cajas-talla-lista">
                        <?php foreach ($tallas as $index => $talla): ?>
                            <li>
                                <div class="talla-item" data-talla="<?php echo htmlspecialchars($talla); ?>"
                                    onclick="seleccionarTalla(this)">
                                    <?php echo htmlspecialchars($talla); ?>
                                </div>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <input type="hidden" name="talla_seleccionada" id="talla_seleccionada" value="">
                </form>
                <div class="guia-tallas">
                    <i class="far fa-ruler"></i> Guía De Tallas
                </div>
            </div>
            <div class="agregar-bolsa" onclick="agregarABolsa()">Agregar a la bolsa</div>
            <div id="mensaje-talla" style="color: red; margin-top: 10px; display: none;">Por favor selecciona una talla
            </div>
            <script>
                let tallaSeleccionada = null;

                function seleccionarTalla(elemento) {
                    // Quitar selección previa
                    document.querySelectorAll('.talla-item').forEach(item => {
                        item.classList.remove('talla-seleccionada');
                    });

                    // Agregar selección nueva
                    elemento.classList.add('talla-seleccionada');
                    tallaSeleccionada = elemento.getAttribute('data-talla');
                    document.getElementById('talla_seleccionada').value = tallaSeleccionada;
                    document.getElementById('mensaje-talla').style.display = 'none';
                }

                function agregarABolsa() {
                    if (!tallaSeleccionada) {
                        document.getElementById('mensaje-talla').style.display = 'block';
                        return;
                    }

                    // Agregar al carrito usando la función del cart-sidebar
                    addToCart(
                        <?php echo $producto['id']; ?>,
                        '<?php echo addslashes($producto['nombre']); ?>',
                        <?php echo $producto['precio']; ?>,
                        '<?php echo $producto['imagen']; ?>',
                        tallaSeleccionada
                    );

                    // Mostrar el carrito
                    toggleCart();
                }
            </script>
            <div class="descripcion">
                <?php echo nl2br(htmlspecialchars($producto['descripcion'])); ?>
            </div>
            <div class="cajas-informacion">
                <h3>ENVÍO</h3>
                <p>Recibe tu pedido en 3-5 días hábiles. Envío gratis en
                    pedidos superiores a $50.</p>
            </div>
            <div class="cajas-informacion">
                <h3>PAGO</h3>
                <p>Tu información de pago se procesa de forma segura. No almacenamos
                    los detalles de la tarjeta de crédito ni tenemos acceso a la
                    información de tu tarjeta de crédito.</p>
                <div class="payment-icons">
                    <i class="fab fa-cc-visa"></i>
                    <i class="fab fa-cc-mastercard"></i>
                    <i class="fab fa-cc-amex"></i>
                    <i class="fab fa-cc-paypal"></i>
                    <i class="fab fa-cc-apple-pay"></i>
                </div>
            </div>
        </div>
    </div>
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
<?php
$stmt->close();
$conexion->close();
?>