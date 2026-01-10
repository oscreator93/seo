$(function () {

    // Form submission
    $('#changedPasswordForm').on('submit', async function (e) {
        e.preventDefault();

        const formData = {
            old_password: $('#old_password').val(),
            password: $('#password').val(),
            confirmPassword: $('#confirm_password').val()
        };

        // Show loading state
        const submitBtn = $('.signup-btn');
        const originalText = submitBtn.html();
        submitBtn.html('Processing...').prop('disabled', true);

        try {
            const response = await fetch(`bridge.php?mode=change_password`, {
                method: "POST",
                body: JSON.stringify(formData),
                headers: { "Content-Type": "application/json" },
            });
            if (response.ok) {
                alert("Password has been changed successfully");
                window.location.href = "logout.php";
            } else {
                const res = await response.json();
                if (res.error) {
                    $('#errors').html(res.error).show();
                } else if (res.errors) {
                    let error = `<ul>` + res.errors.map((err) => `<li>${err}</li>`).join('') + `</ul>`;
                    $('#errors').html(error).show();
                } else {
                    $('#errors').html("Unknown error occurred.").show();
                }
            }
        } catch (err) {
            console.error("Error:", err);
            $('#errors').html("Network error or server unreachable.").show();
        } finally {
            submitBtn.html(originalText).prop('disabled', false);
        }
    });
});