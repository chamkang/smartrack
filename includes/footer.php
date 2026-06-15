  </main>

  <footer id="footer" class="footer dark-background">
    <div class="container footer-top">
      <div class="row gy-4">

        <!-- About column -->
        <div class="col-lg-4 col-md-6 footer-about">
          <a href="<?php echo escape(site_url('index.php')); ?>" class="logo d-flex align-items-center">
            <img src="<?php echo escape($baseUrl ?? ''); ?>/assets/img/st logo.png" alt="">
            <span class="sitename">Smar<span class="text-danger">track</span></span>
          </a>
          <?php $footerLang = function_exists('current_language') ? current_language() : 'en'; ?>
          <div class="footer-contact pt-3">
            <p>Smartrack Africa</p>
            <?php
            $footerAddr = !empty($contact['address_' . $footerLang])
                ? $contact['address_' . $footerLang]
                : ($contact['address_en'] ?? 'Suite 019, Immeuble Axia Avenue de Gaulle, B.P 13255 Douala-Bonanjo');
            ?>
            <p><?php echo escape($footerAddr); ?></p>
            <p class="mt-3">
              <strong><?php echo escape(get_translation('footer_phone_label')); ?></strong>
              <span><?php echo escape($contact['phone'] ?? '+237 691 415 588'); ?></span>
            </p>
            <p>
              <strong><?php echo escape(get_translation('footer_email_label')); ?></strong>
              <span><?php echo escape($contact['email'] ?? 'info@smartrackafrica.com'); ?></span>
            </p>
          </div>
          <div class="social-links d-flex mt-4">
            <a href="<?php echo escape($contact['twitter']  ?? '#'); ?>"><i class="bi bi-twitter-x"></i></a>
            <a href="<?php echo escape($contact['facebook'] ?? '#'); ?>"><i class="bi bi-facebook"></i></a>
            <a href="<?php echo escape($contact['instagram']?? '#'); ?>"><i class="bi bi-instagram"></i></a>
            <a href="<?php echo escape($contact['linkedin'] ?? '#'); ?>"><i class="bi bi-linkedin"></i></a>
          </div>
        </div>

        <!-- Company links -->
        <div class="col-lg-2 col-md-3 footer-links">
          <h4><?php echo escape(get_translation('footer_company')); ?></h4>
          <ul>
            <li><a href="<?php echo escape(site_url('index.php')); ?>"><?php echo escape(get_translation('footer_home')); ?></a></li>
            <li><a href="<?php echo escape(site_url('about.php')); ?>"><?php echo escape(get_translation('footer_about')); ?></a></li>
            <li><a href="<?php echo escape(site_url('career.php')); ?>"><?php echo escape(get_translation('footer_career')); ?></a></li>
            <li><a href="<?php echo escape(site_url('contact.php')); ?>"><?php echo escape(get_translation('footer_contact_link')); ?></a></li>
            <li><a href="#"><?php echo escape(get_translation('footer_terms')); ?></a></li>
            <li><a href="#"><?php echo escape(get_translation('footer_privacy')); ?></a></li>
          </ul>
        </div>

        <!-- SmartFleet links -->
        <div class="col-lg-2 col-md-3 footer-links">
          <h4><?php echo escape(get_translation('footer_sf_title')); ?></h4>
          <ul>
            <li><a href="<?php echo escape(site_url('SmartFleet.php')); ?>"><?php echo escape(get_translation('footer_sf_fleet')); ?></a></li>
            <li><a href="<?php echo escape(site_url('SmartFleet.php')); ?>"><?php echo escape(get_translation('footer_sf_fuel')); ?></a></li>
            <li><a href="<?php echo escape(site_url('SmartFleet.php')); ?>"><?php echo escape(get_translation('footer_sf_tracking')); ?></a></li>
          </ul>
        </div>

        <!-- SmartSolution links -->
        <div class="col-lg-2 col-md-3 footer-links">
          <h4><?php echo escape(get_translation('footer_ss_title')); ?></h4>
          <ul>
            <li><a href="<?php echo escape(site_url('SmartSolution.php')); ?>"><?php echo escape(get_translation('footer_ss_fire')); ?></a></li>
            <li><a href="<?php echo escape(site_url('SmartSolution.php')); ?>"><?php echo escape(get_translation('footer_ss_network')); ?></a></li>
            <li><a href="<?php echo escape(site_url('SmartSolution.php')); ?>"><?php echo escape(get_translation('footer_ss_video')); ?></a></li>
            <li><a href="<?php echo escape(site_url('SmartSolution.php')); ?>"><?php echo escape(get_translation('footer_ss_time')); ?></a></li>
            <li><a href="<?php echo escape(site_url('SmartSolution.php')); ?>"><?php echo escape(get_translation('footer_ss_access')); ?></a></li>
          </ul>
        </div>

      </div>
    </div>

    <div class="container copyright text-center mt-4">
      <p>© <span><?php echo t('Copyright','Copyright'); ?></span> <strong class="px-1 sitename">Smartrack</strong> <span><?php echo escape(get_translation('footer_copyright')); ?></span></p>
    </div>
  </footer>

  <!-- Scroll Top -->
  <a href="#" id="scroll-top" class="scroll-top d-flex align-items-center justify-content-center">
    <i class="bi bi-arrow-up-short"></i>
  </a>

  <!-- Preloader -->
  <div id="preloader"></div>

  <!-- Vendor JS Files -->
  <script src="<?php echo escape($baseUrl ?? ''); ?>/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?php echo escape($baseUrl ?? ''); ?>/assets/vendor/aos/aos.js"></script>
  <script src="<?php echo escape($baseUrl ?? ''); ?>/assets/vendor/glightbox/js/glightbox.min.js"></script>
  <script src="<?php echo escape($baseUrl ?? ''); ?>/assets/vendor/imagesloaded/imagesloaded.pkgd.min.js"></script>
  <script src="<?php echo escape($baseUrl ?? ''); ?>/assets/vendor/isotope-layout/isotope.pkgd.min.js"></script>
  <script src="<?php echo escape($baseUrl ?? ''); ?>/assets/vendor/swiper/swiper-bundle.min.js"></script>
  <script src="<?php echo escape($baseUrl ?? ''); ?>/assets/vendor/purecounter/purecounter_vanilla.js"></script>
  <script src="<?php echo escape($baseUrl ?? ''); ?>/assets/js/jquery.min.js"></script>
  <script src="<?php echo escape($baseUrl ?? ''); ?>/assets/js/translation.js"></script>
  <script src="<?php echo escape($baseUrl ?? ''); ?>/assets/js/main.js"></script>
</body>
</html>
