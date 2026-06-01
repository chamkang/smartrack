</main>
<footer class="bg-dark text-white py-4">
  <div class="container">
    <div class="row">
      <div class="col-md-6">
        <p>&copy; <?php echo date('Y'); ?> Smartrack. All rights reserved.</p>
      </div>
      <div class="col-md-6 text-md-end">
        <a href="<?php echo escape(site_url('contact.php')); ?>" class="text-white text-decoration-none me-3"><?php echo escape(get_translation('n4')); ?></a>
        <a href="<?php echo escape(site_url('admin/login.php')); ?>" class="text-white text-decoration-none"><?php echo escape(get_translation('terms')); ?></a>
      </div>
    </div>
  </div>
</footer>
<script src="<?php echo escape(site_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
<script src="<?php echo escape(site_url('assets/vendor/aos/aos.js')); ?>"></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    AOS.init({ duration: 600 });
  });
</script>
</body>
</html>