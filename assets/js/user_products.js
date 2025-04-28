$(document).ready(function() {
    // Xử lý nút thêm vào giỏ hàng
    $('.add-to-cart').on('click', function() {
        var button = $(this);
        var productId = button.data('product-id');
        var csrfToken = button.data('csrf-token');
        var quantityInput = button.closest('.product-actions').find('.quantity-input');
        var quantity = quantityInput.length ? parseInt(quantityInput.val()) : 1;

        if (isNaN(quantity) || quantity < 1) {
            showToast('error', 'Lỗi', 'Vui lòng nhập số lượng hợp lệ');
            return;
        }

        // Hiển thị trạng thái loading
        button.prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Đang thêm...');

        // Gửi yêu cầu AJAX
        $.ajax({
            url: '../../includes/logic/add_to_cart.php',
            type: 'POST',
            data: {
                product_id: productId,
                quantity: quantity,
                csrf_token: csrfToken
            },
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    showToast('success', 'Thành công', data.productName + ' đã được thêm vào giỏ hàng');
                    updateCartCount(data.totalItems);
                } else {
                    showToast('error', 'Lỗi', data.message || 'Có lỗi xảy ra');
                }
            },
            error: function(xhr) {
                showToast('error', 'Lỗi', 'Có lỗi xảy ra khi thêm vào giỏ hàng');
                console.error('Error:', xhr.responseText);
            },
            complete: function() {
                button.prop('disabled', false).html('<i class="fas fa-cart-plus"></i> Thêm');
            }
        });
    });

    // Hàm hiển thị toast thông báo
    function showToast(type, title, message) {
        // Sử dụng toast notification của Bootstrap
        const toast = new bootstrap.Toast(document.getElementById('liveToast'));
        $('#toast-title').text(title);
        $('#toast-message').html(message);
        $('.toast').removeClass('bg-success bg-danger bg-warning')
            .addClass('bg-' + (type === 'success' ? 'success' : type === 'error' ? 'danger' : 'warning'));
        toast.show();
    }

    // Hàm cập nhật số lượng giỏ hàng
    function updateCartCount(count) {
        $('.cart-count').text(count);
    }
});