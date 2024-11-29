let currentIndex = 0;
const images = document.querySelectorAll('.carousel-images img');
const totalImages = images.length;

function showNextImage() {
    currentIndex = (currentIndex + 1) % totalImages; // Chuyển đến ảnh tiếp theo
    updateCarousel();
}

function updateCarousel() {
    const offset = -currentIndex * 100; // Tính toán độ dịch chuyển
    document.querySelector('.carousel-images').style.transform = `translateX(${offset}%)`;
}

// Chỉ gọi hàm setInterval khi có ít nhất một ảnh
if (totalImages > 0) {
    setInterval(showNextImage, 8000); // Chuyển đổi mỗi 3 giây
}




        //Thêm Số Lượng Tăng Giảm Hàng//
        document.querySelectorAll('.increase').forEach(button => {
    button.addEventListener('click', function() {
        const quantityInput = this.previousElementSibling;
        let currentValue = parseInt(quantityInput.value);
        quantityInput.value = currentValue + 1;
    });
    });
    
    document.querySelectorAll('.decrease').forEach(button => {
    button.addEventListener('click', function() {
        const quantityInput = this.nextElementSibling;
        let currentValue = parseInt(quantityInput.value);
        if (currentValue > 1) {
            quantityInput.value = currentValue - 1;
        }
    });
    });
    
    
        let cartCount = 0;
    
    document.querySelectorAll('.add-to-cart').forEach(button => {
    button.addEventListener('click', function() {
        const quantityInput = this.previousElementSibling.querySelector('.quantity');
        const quantity = parseInt(quantityInput.value);
        cartCount += quantity;
        document.getElementById('cart-count').innerText = cartCount;
        alert(`Đã thêm ${quantity} sản phẩm vào giỏ hàng!`);
    });
    });
    
