/**
 * JavaScript principal pour l'application
 */

// Gestion de la sidebar mobile
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('sidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    
    // Toggle sidebar
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
            document.body.style.overflow = sidebar.classList.contains('active') ? 'hidden' : '';
        });
    }
    
    // Fermer la sidebar en cliquant sur l'overlay
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
        });
    }
    
    // Fermer la sidebar en cliquant sur un lien (mobile)
    const navLinks = document.querySelectorAll('.nav-item');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 1024) {
                sidebar.classList.remove('active');
                sidebarOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
    });
    
    // Fermer la sidebar lors du redimensionnement de la fenêtre
    window.addEventListener('resize', function() {
        if (window.innerWidth > 1024) {
            sidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    });
    
    // Gestion des likes avec AJAX
    // Gérer les formulaires de like
    const likeForms = document.querySelectorAll('.like-form');
    
    likeForms.forEach(form => {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const button = form.querySelector('.btn-like');
            const countSpan = form.querySelector('.like-count');
            const postId = formData.get('post_id');
            
            // Désactiver le bouton pendant la requête
            button.disabled = true;
            
            try {
                const response = await fetch('actions/like_post.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                
                if (data.success) {
                    // Mettre à jour l'interface
                    if (data.has_liked) {
                        button.classList.add('liked');
                        button.innerHTML = '❤️ <span class="like-count">' + data.like_count + '</span>';
                    } else {
                        button.classList.remove('liked');
                        button.innerHTML = '🤍 <span class="like-count">' + data.like_count + '</span>';
                    }
                    
                    // Mettre à jour le compteur si présent ailleurs sur la page
                    const otherCounters = document.querySelectorAll(`[data-post-id="${postId}"] .like-count`);
                    otherCounters.forEach(counter => {
                        counter.textContent = data.like_count;
                    });
                } else {
                    alert('Erreur lors du like. Veuillez réessayer.');
                }
            } catch (error) {
                console.error('Erreur:', error);
                alert('Erreur lors de la requête. Veuillez réessayer.');
            } finally {
                button.disabled = false;
            }
        });
    });
    
    // Confirmation avant suppression
    const deleteForms = document.querySelectorAll('form[action*="delete"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cet élément ?')) {
                e.preventDefault();
            }
        });
    });
    
    // Auto-hide des messages flash après 5 secondes
    const alerts = document.querySelectorAll('.alert');
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => {
                alert.remove();
            }, 500);
        }, 5000);
    });
    
    // Validation des formulaires côté client
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const requiredFields = form.querySelectorAll('[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#dc3545';
                    
                    // Retirer le style d'erreur après interaction
                    field.addEventListener('input', function() {
                        this.style.borderColor = '';
                    }, { once: true });
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Veuillez remplir tous les champs requis.');
            }
        });
    });
    
    // Amélioration UX : focus sur le premier champ des formulaires
    const authForms = document.querySelectorAll('.auth-card form, .post-form, .comment-form');
    authForms.forEach(form => {
        const firstInput = form.querySelector('input[type="text"], input[type="email"], textarea');
        if (firstInput && !firstInput.value) {
            setTimeout(() => firstInput.focus(), 100);
        }
    });
});

