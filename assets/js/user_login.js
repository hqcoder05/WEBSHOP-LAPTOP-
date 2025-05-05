document.addEventListener("DOMContentLoaded", () => {
    const loginForm = document.getElementById("loginForm");
    loginForm.addEventListener("submit", function(e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch("../../includes/logic/user_functions.php?action=login", {
            method: "POST",
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                document.getElementById("loginMessage").textContent = data.message;
                if (data.success) {
                    window.location.href = data.redirect;
                }
            })
            .catch(err => {
                console.error("Lỗi:", err);
            });
    });
});
