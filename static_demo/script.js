// Toggle sidebar de categorías
function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('overlay');
    
    sidebar.classList.toggle('active');
    overlay.classList.toggle('active');
}

// Toggle carrito de compras
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

// Carrito de compras (usando localStorage)
let cart = JSON.parse(localStorage.getItem('cart')) || [];

function renderCart() {
    const cartItemsContainer = document.getElementById('cart-items');
    const cartTotalElement = document.getElementById('cart-total-amount');
    
    if (cart.length === 0) {
        cartItemsContainer.innerHTML = '<div style="text-align: center; padding: 40px 20px; font-family: \'Playfair Display\', serif; font-size: 16px; color: #999999;">Tu bolsa está vacía</div>';
        cartTotalElement.textContent = '$0.00';
        return;
    }
    
    let total = 0;
    cartItemsContainer.innerHTML = '';
    
    cart.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        total += itemTotal;
        
        const cartItemHTML = `
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
        
        cartItemsContainer.innerHTML += cartItemHTML;
    });
    
    cartTotalElement.textContent = '$' + total.toFixed(2);
}

function updateQuantity(index, change) {
    cart[index].quantity += change;
    
    if (cart[index].quantity <= 0) {
        cart.splice(index, 1);
    }
    
    localStorage.setItem('cart', JSON.stringify(cart));
    renderCart();
}

function removeFromCart(index) {
    cart.splice(index, 1);
    localStorage.setItem('cart', JSON.stringify(cart));
    renderCart();
}

function clearCart() {
    if (confirm('¿Estás seguro de que deseas vaciar tu bolsa?')) {
        cart = [];
        localStorage.setItem('cart', JSON.stringify(cart));
        renderCart();
    }
}

function proceedToCheckout() {
    alert('Funcionalidad de pago próximamente');
}

// Función para agregar producto desde página de detalles
function addToCartFromProduct() {
    // Obtener talla seleccionada
    const selectedSize = document.querySelector('.talla-seleccionada');
    if (!selectedSize) {
        alert('Por favor selecciona una talla');
        return;
    }
    
    const size = selectedSize.textContent;
    const name = document.querySelector('.detalle-producto h2').textContent;
    const priceText = document.querySelector('.precio-detalle-producto').textContent;
    const price = parseFloat(priceText.replace('$', ''));
    const image = document.querySelector('.product-review-section img').src;
    
    // Agregar al carrito
    const existingItem = cart.find(item => item.name === name && item.size === size);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            name: name,
            price: price,
            size: size,
            image: image,
            quantity: 1
        });
    }
    
    localStorage.setItem('cart', JSON.stringify(cart));
    
    // Mostrar confirmación
    alert('Producto agregado a tu bolsa');
    
    // Abrir carrito
    toggleCart();
}

// Hacer tallas seleccionables
document.addEventListener('DOMContentLoaded', function() {
    const tallasItems = document.querySelectorAll('.talla-item');
    
    tallasItems.forEach(item => {
        item.addEventListener('click', function() {
            tallasItems.forEach(t => t.classList.remove('talla-seleccionada'));
            this.classList.add('talla-seleccionada');
        });
    });
});

// Toggle dropdowns de filtros
function toggleDropdown(dropdownId) {
    const dropdown = document.getElementById(dropdownId);
    const allDropdowns = document.querySelectorAll('.filter-dropdown');
    
    allDropdowns.forEach(d => {
        if (d.id !== dropdownId) {
            d.style.display = 'none';
        }
    });
    
    dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
}

// Cerrar dropdowns al hacer click fuera
document.addEventListener('click', function(event) {
    if (!event.target.closest('.cajas-filtro-lista')) {
        document.querySelectorAll('.filter-dropdown').forEach(d => {
            d.style.display = 'none';
        });
    }
});

// Event listeners
document.querySelector('.filter-bar').addEventListener('click', toggleSidebar);
document.querySelector('#shopping-bag').addEventListener('click', toggleCart);

// Hover en búsqueda
const searchContainer = document.querySelector('#search');
const searchForm = document.querySelector('.search-form');

if (searchContainer && searchForm) {
    searchContainer.addEventListener('mouseenter', function() {
        searchForm.style.opacity = '1';
        searchForm.style.visibility = 'visible';
    });

    searchContainer.addEventListener('mouseleave', function() {
        searchForm.style.opacity = '0';
        searchForm.style.visibility = 'hidden';
    });

    searchForm.addEventListener('mouseenter', function() {
        this.style.opacity = '1';
        this.style.visibility = 'visible';
    });

    searchForm.addEventListener('mouseleave', function() {
        this.style.opacity = '0';
        this.style.visibility = 'hidden';
    });
}

// Cargar carrito al iniciar
renderCart();
