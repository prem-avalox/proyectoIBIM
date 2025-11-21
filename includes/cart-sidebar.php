<!-- Cart Sidebar -->
<div id="cart-sidebar" class="cart-sidebar" style="position: fixed; top: 0; right: -450px; left: auto !important; width: 450px; height: 100vh; background-color: white; box-shadow: -2px 0 10px rgba(0,0,0,0.1); transition: right 0.3s ease; z-index: 1001; display: flex; flex-direction: column;">
    <div class="sidebar-header" style="margin-top: 70px;">
        <h2>MI BOLSA</h2>
        <i class="fas fa-times close-btn" onclick="toggleCart()"></i>
    </div>
    
    <div id="cart-items" class="cart-items" style="flex: 1; overflow-y: auto; padding: 20px;">
        <!-- Los productos se agregarán aquí dinámicamente -->
    </div>
    
    <div class="cart-footer" style="border-top: 2px solid #e0e0e0; padding: 20px; background-color: #f9f9f9;">
        <div class="cart-total" style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px; font-family: 'Playfair Display', serif; font-size: 20px; font-weight: 700;">
            <span>Total:</span>
            <span id="cart-total-amount">$0.00</span>
        </div>
        <div class="cart-checkout-btn" onclick="proceedToCheckout()" style="width: 100%; padding: 15px 20px; cursor: pointer; font-family: 'Playfair Display', serif; font-size: 16px; font-weight: 600; transition: background-color 0.3s; margin-bottom: 10px; text-align: center; background-color: #124a7e; color: white; border: none; box-sizing: border-box;">
            Proceder al Pago
        </div>
        <div class="cart-clear-btn" onclick="clearCart()" style="width: 100%; padding: 15px 20px; cursor: pointer; font-family: 'Playfair Display', serif; font-size: 16px; font-weight: 600; transition: background-color 0.3s; margin-bottom: 10px; text-align: center; background-color: white; color: #575757; border: 1px solid #e0e0e0; box-sizing: border-box;">
            Vaciar Bolsa
        </div>
    </div>
</div>

<!-- Overlay para el carrito -->
<div id="cart-overlay" class="overlay" onclick="toggleCart()"></div>

<script>
    // Carrito de compras (usando localStorage)
    let cart = JSON.parse(localStorage.getItem('cart')) || [];

    function toggleCart() {
        const cartSidebar = document.getElementById('cart-sidebar');
        const cartOverlay = document.getElementById('cart-overlay');
        
        if (cartSidebar.classList.contains('active')) {
            cartSidebar.classList.remove('active');
            cartSidebar.style.right = '-450px';
            cartOverlay.classList.remove('active');
        } else {
            cartSidebar.classList.add('active');
            cartSidebar.style.right = '0';
            cartOverlay.classList.add('active');
            renderCart();
        }
    }

    function addToCart(productId, productName, productPrice, productImage, productSize) {
        // Asegurar que el ID sea número y el precio sea float
        productId = parseInt(productId);
        productPrice = parseFloat(productPrice);
        
        // Buscar si el producto ya existe en el carrito
        const existingItemIndex = cart.findIndex(i => 
            parseInt(i.id) === productId && i.size === productSize
        );
        
        if (existingItemIndex > -1) {
            // Si existe, solo incrementar la cantidad
            cart[existingItemIndex].quantity++;
        } else {
            // Si no existe, agregar nuevo item
            const item = {
                id: productId,
                name: productName,
                price: productPrice,
                image: productImage,
                size: productSize,
                quantity: 1
            };
            cart.push(item);
        }

        localStorage.setItem('cart', JSON.stringify(cart));
        updateCartCount();
        
        // Mostrar mensaje de éxito
        showCartMessage('Producto agregado a la bolsa');
    }

    function removeFromCart(index) {
        cart.splice(index, 1);
        localStorage.setItem('cart', JSON.stringify(cart));
        renderCart();
        updateCartCount();
    }

    function updateQuantity(index, change) {
        cart[index].quantity += change;
        
        if (cart[index].quantity <= 0) {
            removeFromCart(index);
        } else {
            localStorage.setItem('cart', JSON.stringify(cart));
            renderCart();
        }
    }

    function renderCart() {
        const cartItemsContainer = document.getElementById('cart-items');
        
        if (cart.length === 0) {
            cartItemsContainer.innerHTML = '<div style="text-align: center; padding: 40px 20px; font-family: \'Playfair Display\', serif; font-size: 16px; color: #999999;">Tu bolsa está vacía</div>';
            document.getElementById('cart-total-amount').textContent = '$0.00';
            return;
        }

        let html = '';
        let total = 0;

        cart.forEach((item, index) => {
            const itemTotal = item.price * item.quantity;
            total += itemTotal;

            html += `
                <div style="display: flex; gap: 15px; padding: 15px 0; border-bottom: 1px solid #e0e0e0; position: relative;">
                    <img src="${item.image}" alt="${item.name}" style="width: 80px; height: 80px; object-fit: cover; border-radius: 4px;">
                    <div style="flex: 1;">
                        <h4 style="font-family: 'Playfair Display', serif; font-size: 14px; font-weight: 600; margin: 0 0 5px 0;">${item.name}</h4>
                        <p style="font-family: 'Roboto Condensed', sans-serif; font-size: 12px; color: #999999; margin: 0 0 5px 0;">Talla: ${item.size}</p>
                        <p style="font-family: 'Roboto Condensed', sans-serif; font-size: 16px; font-weight: 700; color: #575757; margin: 0 0 10px 0;">$${item.price.toFixed(2)}</p>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <div onclick="updateQuantity(${index}, -1)" style="width: 30px; height: 30px; border: 1px solid #e0e0e0; background-color: white; cursor: pointer; font-size: 16px; display: flex; align-items: center; justify-content: center; font-family: 'Playfair Display', serif; font-weight: 700;">-</div>
                            <span style="font-family: 'Roboto Condensed', sans-serif; font-size: 16px; min-width: 20px; text-align: center;">${item.quantity}</span>
                            <div onclick="updateQuantity(${index}, 1)" style="width: 30px; height: 30px; border: 1px solid #e0e0e0; background-color: white; cursor: pointer; font-size: 16px; display: flex; align-items: center; justify-content: center; font-family: 'Playfair Display', serif; font-weight: 700;">+</div>
                        </div>
                    </div>
                    <div onclick="removeFromCart(${index})" style="cursor: pointer; color: #999999; font-size: 18px; padding: 5px;">
                        <i class="fas fa-trash"></i>
                    </div>
                </div>
            `;
        });

        cartItemsContainer.innerHTML = html;
        document.getElementById('cart-total-amount').textContent = `$${total.toFixed(2)}`;
    }

    function updateCartCount() {
        const cartCount = cart.reduce((sum, item) => sum + item.quantity, 0);
        const badge = document.querySelector('.cart-badge');
        
        if (cartCount > 0) {
            if (!badge) {
                const shoppingBag = document.getElementById('shopping-bag');
                const newBadge = document.createElement('span');
                newBadge.className = 'cart-badge';
                newBadge.textContent = cartCount;
                shoppingBag.appendChild(newBadge);
            } else {
                badge.textContent = cartCount;
            }
        } else if (badge) {
            badge.remove();
        }
    }

    function clearCart() {
        if (confirm('¿Estás seguro de que quieres vaciar tu bolsa?')) {
            cart = [];
            localStorage.removeItem('cart');
            renderCart();
            updateCartCount();
        }
    }

    function proceedToCheckout() {
        if (cart.length === 0) {
            alert('Tu bolsa está vacía');
            return;
        }
        alert('Función de pago en desarrollo');
        // Aquí puedes redirigir a una página de checkout
        // window.location.href = 'checkout.php';
    }

    function showCartMessage(message) {
        const messageDiv = document.createElement('div');
        messageDiv.className = 'cart-message';
        messageDiv.textContent = message;
        document.body.appendChild(messageDiv);
        
        setTimeout(() => {
            messageDiv.remove();
        }, 3000);
    }

    // Agregar evento al icono de la bolsa
    document.getElementById('shopping-bag').addEventListener('click', toggleCart);

    // Actualizar contador al cargar la página
    document.addEventListener('DOMContentLoaded', updateCartCount);
</script>
