$(document).ready(function() {
    $('#registerForm').on('submit', function(e) {
        e.preventDefault(); // Ngăn chặn hành động mặc định của form

        $.ajax({
            type: 'POST',
            url: '', // Gửi đến chính file này
            data: $(this).serialize() + '&ajax=true', // Dữ liệu từ form
            dataType: 'json',
            success: function(response) {
                $('#message').text(response.message); // Hiển thị thông báo
            },
            error: function() {
                $('#message').text('Có lỗi xảy ra, vui lòng thử lại!');
            }
        });
    });
});