import Swal from 'sweetalert2';

/**
 * Intercepte tous les formulaires avec l'attribut data-confirm et affiche
 * une confirmation SweetAlert2 avant soumission, à la place du confirm() natif.
 */
document.addEventListener('submit', function (event) {
    const form = event.target.closest('[data-confirm]');
    if (!form) return;

    event.preventDefault();

    const message = form.dataset.confirm;
    const isDestructive = form.method.toUpperCase() === 'POST' && form.querySelector('input[name="_method"][value="DELETE"]');

    Swal.fire({
        title: message,
        icon: isDestructive ? 'warning' : 'question',
        showCancelButton: true,
        confirmButtonText: isDestructive ? 'Supprimer' : 'Confirmer',
        cancelButtonText: 'Annuler',
        confirmButtonColor: isDestructive ? '#EF4444' : '#4F46E5',
        cancelButtonColor: '#9CA3AF',
        reverseButtons: true,
    }).then((result) => {
        if (result.isConfirmed) {
            form.submit();
        }
    });
});

/**
 * Affiche un toast de succès en haut à droite, à partir d'un message
 * exposé via window.flashSuccess (injecté depuis Blade selon session('success')).
 */
document.addEventListener('DOMContentLoaded', function () {
    if (window.flashSuccess) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: window.flashSuccess,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
        });
    }

    if (window.flashStatus) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'info',
            title: window.flashStatus,
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
        });
    }
});