<script src="<?= ROOT ?>/assets/js/jquery-3.7.1.min.js"></script>
<script src="<?= ROOT ?>/assets/js/boostrap.bundle.min.js"></script>
<!-- <script src="<?= ROOT ?>/assets/js/countdown.js"></script> -->
<script src="<?= ROOT ?>/assets/js/counterup.min.js"></script>
<script src="<?= ROOT ?>/assets/js/slick.min.js"></script>
<script src="<?= ROOT ?>/assets/js/jquery.magnific-popup.js"></script>
<script src="<?= ROOT ?>/assets/js/apexchart.js"></script>
<script src="<?= ROOT ?>/assets/js/main.js"></script>
<script src="<?= ROOT ?>/assets/js/public-reveal.js?v=2"></script>
<script src="<?= ROOT ?>/assets/js/nkadon-chatbot.js"></script>
<script src="https://cdn.jsdelivr.net/npm/quill@1.3.7/dist/quill.min.js"></script>
<script>
    // CSRF : ajoute le jeton a toutes les requetes AJAX jQuery (en-tete X-CSRF-Token).
    if (window.jQuery) {
        jQuery.ajaxSetup({
            headers: { 'X-CSRF-Token': window.CSRF_TOKEN || '' }
        });
    }
</script>
<?php $this->view('Partials/smart-back'); ?>
<?php $this->view('Partials/pwa'); ?>
<?php $this->view('Partials/onboarding'); ?>
