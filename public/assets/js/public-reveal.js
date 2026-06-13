(function () {
    'use strict';

    function isPublicPage() {
        return document.body && (
            document.body.classList.contains('public-site') ||
            document.body.classList.contains('um6p-site')
        );
    }

    function collectRevealItems(root) {
        if (!isPublicPage()) {
            return [];
        }

        var scope = root && root.querySelectorAll ? root : document;
        var selector = [
            'main > section',
            '.salon-hero-card',
            '.salon-side',
            '.top-liked-shell',
            '.ai-launcher',
            '.filters',
            '.mini-card',
            '.project-card-modern',
            '.news-card',
            '.home-department-card',
            '.department-stat',
            '.department-post-card',
            '.department-card',
            '.publication-card',
            '.product-card',
            '.pd-card',
            '.pd-side',
            '.pd-section',
            '.modern-card',
            '.common-card',
            '.empty-projects',
            '.footer-item'
        ].join(',');

        return Array.prototype.slice.call(scope.querySelectorAll(selector)).filter(function (item) {
            return !item.classList.contains('loader-mask') &&
                !item.classList.contains('mobile-menu') &&
                !item.closest('.mobile-menu') &&
                !item.closest('.ai-modal') &&
                !item.dataset.publicRevealBound;
        });
    }

    function setupReveal() {
        if (!isPublicPage()) {
            return;
        }

        document.body.classList.add('public-reveal-ready');

        var observer = null;
        if ('IntersectionObserver' in window) {
            observer = new IntersectionObserver(function (entries) {
                entries.forEach(function (entry) {
                    if (!entry.isIntersecting) {
                        return;
                    }

                    entry.target.classList.add('is-visible');
                    observer.unobserve(entry.target);
                });
            }, {
                threshold: 0.12,
                rootMargin: '0px 0px -8% 0px'
            });
        }

        function bind(root) {
            var items = collectRevealItems(root);
            items.forEach(function (item, index) {
                item.dataset.publicRevealBound = 'true';
                item.classList.add('public-reveal');
                item.style.setProperty('--reveal-delay', Math.min(index % 6, 5) * 70 + 'ms');

                if (observer) {
                    observer.observe(item);
                } else {
                    item.classList.add('is-visible');
                }
            });
        }

        bind(document);

        if ('MutationObserver' in window) {
            var mutationObserver = new MutationObserver(function (mutations) {
                mutations.forEach(function (mutation) {
                    mutation.addedNodes.forEach(function (node) {
                        if (node.nodeType === 1) {
                            bind(node);
                        }
                    });
                });
            });

            mutationObserver.observe(document.body, {
                childList: true,
                subtree: true
            });
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupReveal);
    } else {
        setupReveal();
    }
})();
