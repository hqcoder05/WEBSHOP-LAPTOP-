document.addEventListener("DOMContentLoaded", function() {
    // Lắng nghe sự kiện khi người dùng click nút thanh toán
    const checkoutForm = document.getElementById("checkout-form");

    if (checkoutForm) {
        checkoutForm.addEventListener("submit", function(event) {
            event.preventDefault();  // Ngừng hành động mặc định của form (không tải lại trang)

            // Lấy thông tin từ các trường input
            const fullName = document.getElementById("full_name").value.trim();
            const phone = document.getElementById("phone").value.trim();
            const address = document.getElementById("address").value.trim();
            const note = document.getElementById("note").value.trim();

            // Kiểm tra tính hợp lệ của các trường thông tin
            if (!fullName || !phone || !address) {
                alert("Vui lòng điền đầy đủ thông tin (Tên, Số điện thoại và Địa chỉ).");
                return;
            }

            // Cấu trúc dữ liệu cần gửi
            const data = {
                full_name: fullName,
                phone: phone,
                address: address,
                note: note
            };

            // Gửi yêu cầu AJAX để xử lý checkout
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "process_checkout.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            // Chuẩn bị dữ liệu gửi đi
            const params = new URLSearchParams(data).toString();

            // Xử lý kết quả từ server
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);

                    // Kiểm tra kết quả từ server
                    if (response.success) {
                        // Thanh toán thành công, chuyển hướng đến trang cảm ơn hoặc đơn hàng
                        alert("Đặt hàng thành công! Cảm ơn bạn đã mua sắm tại cửa hàng.");
                        window.location.href = "orders.php"; // Chuyển đến trang đơn hàng
                    } else {
                        // Hiển thị thông báo lỗi
                        alert("Đã có lỗi xảy ra trong quá trình thanh toán. Vui lòng thử lại.");
                    }
                }
            };

            // Gửi yêu cầu
            xhr.send(params);
        });
    }
});
