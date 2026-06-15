    </main><!-- /.admin-content -->
  </div><!-- /.admin-main -->
</div><!-- /.admin-shell -->

<script src="<?php echo escape(site_url('assets/vendor/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
<script>
  // ── Sidebar mobile toggle ──────────────────────────────────
  const sidebar  = document.getElementById('sidebar');
  const overlay  = document.getElementById('sidebarOverlay');
  const hamburger = document.getElementById('hamburger');

  function openSidebar() {
    sidebar.classList.add('open');
    overlay.classList.add('show');
    document.body.style.overflow = 'hidden';
  }
  function closeSidebar() {
    sidebar.classList.remove('open');
    overlay.classList.remove('show');
    document.body.style.overflow = '';
  }

  hamburger && hamburger.addEventListener('click', openSidebar);
  overlay.addEventListener('click', closeSidebar);

  // Close sidebar on nav link click (mobile)
  document.querySelectorAll('.sidebar-link').forEach(link => {
    link.addEventListener('click', () => {
      if (window.innerWidth <= 768) closeSidebar();
    });
  });

  // ── Dismiss alerts ────────────────────────────────────────
  document.querySelectorAll('.admin-alert[data-dismiss]').forEach(el => {
    el.style.cursor = 'pointer';
    el.addEventListener('click', () => el.remove());
  });
</script>
</body>
</html>
