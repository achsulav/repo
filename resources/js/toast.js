document.addEventListener('DOMContentLoaded', () => {
    const toasts = document.querySelectorAll('.toast');

    toasts.forEach(toast => {
        // Auto remove after 5 seconds
        const timer = setTimeout(() => {
            removeToast(toast);
        }, 5000);

        const closeBtn = toast.querySelector('.toast-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                clearTimeout(timer);
                removeToast(toast);
            });
        }
    });

    function removeToast(toast) {
        toast.classList.add('toast-removing');

        setTimeout(() => {
            toast.remove();
        }, 400);
    }
});
