<?php
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
        <link rel="stylesheet" href="styles.css">
        <link rel="stylesheet"
            href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
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
                <div id="search">
                    <i class="far fa-search"></i>
                </div>
                <div id="user-info">
                    <i class="far fa-user"></i>
                </div>
                <div id="shopping-bag">
                    <i class="far fa-shopping-bag"></i>
                </div>
            </div>
        </div>
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
                            <?php foreach($tallas as $index => $talla): ?>
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
                <div id="mensaje-talla" style="color: red; margin-top: 10px; display: none;">Por favor selecciona una talla</div>
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
                        
                        alert('Producto agregado a la bolsa\nTalla: ' + tallaSeleccionada);
                        // Aquí puedes agregar la lógica para añadir al carrito
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
    </body>
</html>
<?php
$stmt->close();
$conexion->close();
?>
