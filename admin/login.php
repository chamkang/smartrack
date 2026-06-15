<?php
require_once __DIR__ . '/../includes/functions.php';
init_session();

if (is_logged_in()) {
    redirect(site_url('admin/dashboard.php'));
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!verify_csrf($_POST['csrf_token'] ?? '')) {
        $error = 'Security token expired. Please refresh the page.';
    } else {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        if ($username === '' || $password === '') {
            $error = 'Please enter your username and password.';
        } else {
            $stmt = db()->prepare('SELECT id,username,password_hash,email FROM admins WHERE username=? LIMIT 1');
            $stmt->execute([$username]);
            $admin = $stmt->fetch();
            if ($admin && password_verify($password, $admin['password_hash'])) {
                session_regenerate_id(true);
                $_SESSION['admin_id']   = $admin['id'];
                $_SESSION['admin']      = ['username' => $admin['username'], 'email' => $admin['email']];
                $_SESSION['last_login'] = time();
                redirect(site_url('admin/dashboard.php'));
            }
            $error = 'Incorrect username or password.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sign In — Smartrack Admin</title>
<link href="<?php echo escape(site_url('assets/vendor/bootstrap-icons/bootstrap-icons.css')); ?>" rel="stylesheet">
<style>
/* ── Reset ───────────────────────────────────────────── */
*,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
html,body{height:100%}

/* ── Google Font (Inter) ─────────────────────────────── */
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');

/* ── Root vars ───────────────────────────────────────── */
:root{
  --bg:     #040812;
  --accent: #e53935;
  --accent2:#ff6b35;
  --glow:   rgba(229,57,53,.35);
  --card:   rgba(10,15,30,.82);
  --border: rgba(229,57,53,.22);
  --txt:    #f1f5f9;
  --muted:  rgba(255,255,255,.42);
  --t:      .28s cubic-bezier(.4,0,.2,1);
}

/* ── Full-screen canvas bg ───────────────────────────── */
body{
  font-family:'Inter',system-ui,sans-serif;
  background:var(--bg);
  min-height:100vh;
  overflow:hidden;
  display:flex;
  align-items:center;
  justify-content:center;
  -webkit-font-smoothing:antialiased;
}

#canvas{
  position:fixed;
  inset:0;
  z-index:0;
  pointer-events:none;
}

/* Deep radial centre glow */
.glow-center{
  position:fixed;
  inset:0;
  z-index:1;
  background:
    radial-gradient(ellipse 70% 55% at 38% 50%,rgba(229,57,53,.10) 0%,transparent 65%),
    radial-gradient(ellipse 50% 40% at 70% 55%,rgba(59,130,246,.06) 0%,transparent 60%);
  pointer-events:none;
}

/* ── GPS ping decorations (left half) ───────────────── */
.ping{
  position:fixed;
  z-index:2;
  pointer-events:none;
}
.ping-dot{
  width:8px;height:8px;
  border-radius:50%;
  background:var(--accent);
  box-shadow:0 0 10px 3px var(--glow);
  position:absolute;
  top:50%;left:50%;
  transform:translate(-50%,-50%);
}
.ping-ring{
  position:absolute;
  top:50%;left:50%;
  transform:translate(-50%,-50%);
  border-radius:50%;
  border:1.5px solid var(--accent);
  opacity:0;
  animation:ping-expand 2.8s ease-out infinite;
}
.ping-ring:nth-child(2){animation-delay:.7s;border-color:rgba(229,57,53,.5);}
.ping-ring:nth-child(3){animation-delay:1.4s;border-color:rgba(229,57,53,.25);}
@keyframes ping-expand{
  0%  {width:8px;height:8px;opacity:.9;}
  100%{width:72px;height:72px;opacity:0;}
}

/* ── Main layout ─────────────────────────────────────── */
.page{
  position:relative;
  z-index:10;
  display:flex;
  width:100%;
  height:100vh;
}

/* ── Left brand panel ────────────────────────────────── */
.brand{
  flex:1;
  display:flex;
  flex-direction:column;
  justify-content:center;
  padding:60px 64px;
  position:relative;
}

.brand-logo{
  display:flex;
  align-items:center;
  gap:12px;
  margin-bottom:52px;
}
.brand-logo-icon{
  width:48px;height:48px;
  border-radius:12px;
  background:linear-gradient(135deg,var(--accent),var(--accent2));
  display:flex;align-items:center;justify-content:center;
  font-size:1.5rem;color:#fff;
  box-shadow:0 0 28px var(--glow);
}
.brand-logo-name{
  font-size:1.35rem;font-weight:800;
  color:#fff;letter-spacing:-.02em;
}
.brand-logo-name span{
  background:linear-gradient(90deg,var(--accent),var(--accent2));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}

.brand-headline{
  font-size:clamp(2rem,3.5vw,3.2rem);
  font-weight:900;
  line-height:1.12;
  color:#fff;
  letter-spacing:-.04em;
  margin-bottom:22px;
}
.brand-headline .hl{
  background:linear-gradient(90deg,#ff6b35,var(--accent),#ff6b35);
  background-size:200%;
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
  animation:shimmer-text 4s linear infinite;
}
@keyframes shimmer-text{
  0%{background-position:0%}
  100%{background-position:200%}
}

.brand-sub{
  font-size:1.05rem;
  color:var(--muted);
  line-height:1.7;
  max-width:420px;
  margin-bottom:52px;
}

/* Stat pills */
.stats{
  display:flex;
  gap:14px;
  flex-wrap:wrap;
  margin-bottom:52px;
}
.stat{
  background:rgba(255,255,255,.05);
  border:1px solid rgba(255,255,255,.08);
  border-radius:14px;
  padding:16px 22px;
  backdrop-filter:blur(8px);
  min-width:110px;
}
.stat-num{
  font-size:1.6rem;font-weight:800;
  background:linear-gradient(135deg,#fff,rgba(255,255,255,.7));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
  letter-spacing:-.04em;
}
.stat-lbl{
  font-size:.72rem;font-weight:600;
  color:var(--muted);
  text-transform:uppercase;letter-spacing:.08em;
  margin-top:2px;
}

/* Feature list */
.features{display:flex;flex-direction:column;gap:14px;}
.feature{
  display:flex;align-items:center;gap:14px;
  color:rgba(255,255,255,.65);
  font-size:.875rem;font-weight:500;
}
.feature-icon{
  width:34px;height:34px;border-radius:9px;
  background:rgba(229,57,53,.12);
  border:1px solid rgba(229,57,53,.25);
  display:flex;align-items:center;justify-content:center;
  color:var(--accent);font-size:.9rem;flex-shrink:0;
}

/* ── Right card panel ────────────────────────────────── */
.card-wrap{
  width:480px;
  display:flex;
  align-items:center;
  justify-content:center;
  padding:40px 48px;
  position:relative;
}

/* Vertical separator glow line */
.card-wrap::before{
  content:'';
  position:absolute;
  left:0;top:15%;bottom:15%;
  width:1px;
  background:linear-gradient(to bottom,transparent,rgba(229,57,53,.35),transparent);
}

.card{
  width:100%;
  background:var(--card);
  border:1px solid var(--border);
  border-radius:24px;
  padding:44px 40px;
  backdrop-filter:blur(28px) saturate(1.4);
  box-shadow:
    0 0 0 1px rgba(229,57,53,.08),
    0 32px 80px rgba(0,0,0,.55),
    0 0 60px rgba(229,57,53,.06),
    inset 0 1px 0 rgba(255,255,255,.06);
  animation:card-in .7s cubic-bezier(.22,1,.36,1) both;
}
@keyframes card-in{
  from{opacity:0;transform:translateY(24px);}
  to  {opacity:1;transform:translateY(0);}
}

/* Card top accent line */
.card-accent{
  height:2px;
  background:linear-gradient(90deg,transparent,var(--accent),var(--accent2),transparent);
  border-radius:99px;
  margin-bottom:36px;
  opacity:.7;
}

.card-title{
  font-size:1.6rem;font-weight:800;
  color:#fff;letter-spacing:-.04em;
  margin-bottom:6px;
}
.card-sub{
  font-size:.875rem;color:var(--muted);
  margin-bottom:36px;
  line-height:1.5;
}

/* Error banner */
.err{
  display:flex;align-items:center;gap:10px;
  padding:13px 16px;
  background:rgba(229,57,53,.12);
  border:1px solid rgba(229,57,53,.3);
  border-radius:10px;
  color:#ff8a80;
  font-size:.875rem;font-weight:500;
  margin-bottom:24px;
  animation:shake .4s ease;
}
@keyframes shake{
  0%,100%{transform:translateX(0)}
  20%    {transform:translateX(-6px)}
  40%    {transform:translateX(6px)}
  60%    {transform:translateX(-4px)}
  80%    {transform:translateX(4px)}
}

/* ── Inputs ──────────────────────────────────────────── */
.field{
  margin-bottom:24px;
  position:relative;
}
.field-inner{
  position:relative;
  display:flex;
  align-items:center;
  background:rgba(255,255,255,.04);
  border:1.5px solid rgba(255,255,255,.10);
  border-radius:12px;
  transition:border-color var(--t),box-shadow var(--t),background var(--t);
  overflow:hidden;
}
.field-inner:focus-within{
  border-color:rgba(229,57,53,.7);
  background:rgba(229,57,53,.04);
  box-shadow:0 0 0 4px rgba(229,57,53,.14),0 2px 12px rgba(229,57,53,.12);
}
/* animated bottom line */
.field-inner::after{
  content:'';
  position:absolute;
  bottom:0;left:50%;
  width:0;height:2px;
  background:linear-gradient(90deg,var(--accent),var(--accent2));
  border-radius:99px;
  transition:width var(--t),left var(--t);
}
.field-inner:focus-within::after{
  width:calc(100% - 24px);
  left:12px;
}

.field-icon{
  padding:0 14px;
  color:rgba(255,255,255,.28);
  font-size:1rem;
  flex-shrink:0;
  transition:color var(--t);
  display:flex;align-items:center;
}
.field-inner:focus-within .field-icon{color:var(--accent);}

.field input{
  flex:1;
  background:transparent;
  border:none;outline:none;
  color:#fff;
  font-family:'Inter',sans-serif;
  font-size:.925rem;
  font-weight:500;
  padding:15px 4px 15px 0;
  line-height:1;
}
.field input::placeholder{color:rgba(255,255,255,.28);}

.field-label{
  font-size:.72rem;font-weight:700;
  text-transform:uppercase;letter-spacing:.09em;
  color:rgba(255,255,255,.38);
  margin-bottom:8px;
  display:block;
  transition:color var(--t);
}
.field:focus-within .field-label{color:var(--accent);}

.pwd-btn{
  background:none;border:none;cursor:pointer;
  color:rgba(255,255,255,.3);
  padding:0 14px;
  font-size:1rem;
  display:flex;align-items:center;
  transition:color var(--t);
  flex-shrink:0;
}
.pwd-btn:hover{color:rgba(255,255,255,.7);}

/* ── Submit button ───────────────────────────────────── */
.btn-submit{
  width:100%;
  padding:15px 24px;
  border:none;
  border-radius:12px;
  font-family:'Inter',sans-serif;
  font-size:.975rem;font-weight:700;
  color:#fff;
  cursor:pointer;
  position:relative;
  overflow:hidden;
  letter-spacing:.01em;
  background:linear-gradient(135deg,var(--accent) 0%,#c62828 50%,var(--accent) 100%);
  background-size:200%;
  transition:background-position var(--t),box-shadow var(--t),transform var(--t);
  display:flex;align-items:center;justify-content:center;gap:10px;
  margin-top:32px;
  box-shadow:0 4px 20px rgba(229,57,53,.35);
}
.btn-submit:hover{
  background-position:right center;
  box-shadow:0 8px 32px rgba(229,57,53,.55);
  transform:translateY(-1px);
}
.btn-submit:active{transform:translateY(0);box-shadow:0 2px 10px rgba(229,57,53,.3);}

/* Shimmer sweep on button */
.btn-submit::before{
  content:'';
  position:absolute;
  top:-50%;left:-75%;
  width:50%;height:200%;
  background:linear-gradient(105deg,transparent 20%,rgba(255,255,255,.18) 50%,transparent 80%);
  animation:btn-shimmer 2.8s ease-in-out infinite;
}
@keyframes btn-shimmer{
  0%  {left:-75%}
  60%,100%{left:125%}
}

/* ── Back link ───────────────────────────────────────── */
.back-link{
  display:flex;align-items:center;justify-content:center;gap:7px;
  margin-top:24px;
  color:var(--muted);font-size:.82rem;font-weight:500;
  text-decoration:none;
  transition:color var(--t);
}
.back-link:hover{color:rgba(255,255,255,.75);}
.back-link i{font-size:.8rem;}

/* ── Responsive ──────────────────────────────────────── */
@media(max-width:820px){
  .brand{display:none;}
  .card-wrap{
    width:100%;
    padding:32px 24px;
  }
  .card-wrap::before{display:none;}
  .card{padding:36px 28px;}
}
@media(max-width:400px){
  .card{padding:28px 20px;}
}
</style>
</head>
<body>

<!-- ── Particle canvas ── -->
<canvas id="canvas"></canvas>
<div class="glow-center"></div>

<!-- ── GPS pings scattered on left side ── -->
<div class="ping" style="left:12%;top:22%">
  <div class="ping-dot"></div>
  <div class="ping-ring"></div>
  <div class="ping-ring"></div>
  <div class="ping-ring"></div>
</div>
<div class="ping" style="left:28%;top:65%">
  <div class="ping-dot" style="background:#3b82f6;box-shadow:0 0 10px 3px rgba(59,130,246,.4)"></div>
  <div class="ping-ring" style="border-color:#3b82f6;animation-delay:.3s"></div>
  <div class="ping-ring" style="border-color:rgba(59,130,246,.4);animation-delay:1s"></div>
</div>
<div class="ping" style="left:8%;top:72%">
  <div class="ping-dot" style="width:5px;height:5px;"></div>
  <div class="ping-ring" style="animation-delay:.9s"></div>
  <div class="ping-ring" style="border-color:rgba(229,57,53,.3);animation-delay:1.8s"></div>
</div>
<div class="ping" style="left:42%;top:18%">
  <div class="ping-dot" style="background:#22c55e;box-shadow:0 0 10px 3px rgba(34,197,94,.4);width:6px;height:6px;"></div>
  <div class="ping-ring" style="border-color:#22c55e;animation-delay:.5s"></div>
  <div class="ping-ring" style="border-color:rgba(34,197,94,.3);animation-delay:1.3s"></div>
</div>
<div class="ping" style="left:34%;top:82%">
  <div class="ping-dot" style="width:5px;height:5px;background:#f59e0b;box-shadow:0 0 8px 2px rgba(245,158,11,.4);"></div>
  <div class="ping-ring" style="border-color:#f59e0b;animation-delay:.2s"></div>
  <div class="ping-ring" style="border-color:rgba(245,158,11,.3);animation-delay:1.1s"></div>
</div>

<!-- ── Page layout ── -->
<div class="page">

  <!-- Left brand panel -->
  <div class="brand">

    <div class="brand-logo">
      <div class="brand-logo-icon"><i class="bi bi-geo-alt-fill"></i></div>
      <div class="brand-logo-name">SMAR<span>TRACK</span></div>
    </div>

    <h1 class="brand-headline">
      Command your<br>
      entire fleet —<br>
      <span class="hl">in real time.</span>
    </h1>

    <p class="brand-sub">
      Monitor every vehicle, manage content, handle client requests, and keep your team in sync — all from one secure dashboard.
    </p>

    <div class="stats">
      <div class="stat">
        <div class="stat-num" data-target="38">0</div>
        <div class="stat-lbl">K+ Clients</div>
      </div>
      <div class="stat">
        <div class="stat-num" data-target="166">0</div>
        <div class="stat-lbl">Projects</div>
      </div>
      <div class="stat">
        <div class="stat-num" style="-webkit-text-fill-color:#fff;">24/7</div>
        <div class="stat-lbl">Uptime</div>
      </div>
    </div>

    <div class="features">
      <div class="feature">
        <div class="feature-icon"><i class="bi bi-shield-lock-fill"></i></div>
        Secure, CSRF-protected admin sessions
      </div>
      <div class="feature">
        <div class="feature-icon"><i class="bi bi-translate"></i></div>
        Bilingual EN / FR content management
      </div>
      <div class="feature">
        <div class="feature-icon"><i class="bi bi-cloud-upload-fill"></i></div>
        Media upload with live website preview
      </div>
    </div>

  </div>

  <!-- Right login card -->
  <div class="card-wrap">
    <div class="card">

      <div class="card-accent"></div>

      <div class="card-title">Welcome back</div>
      <div class="card-sub">Sign in to your Smartrack admin panel</div>

      <?php if ($error): ?>
        <div class="err">
          <i class="bi bi-exclamation-triangle-fill"></i>
          <?php echo escape($error); ?>
        </div>
      <?php endif; ?>

      <form method="post" novalidate>
        <input type="hidden" name="csrf_token" value="<?php echo escape(csrf_token()); ?>">

        <!-- Username -->
        <div class="field">
          <label class="field-label" for="username">Username</label>
          <div class="field-inner">
            <span class="field-icon"><i class="bi bi-person-fill"></i></span>
            <input
              type="text"
              id="username"
              name="username"
              placeholder="Enter your username"
              autocomplete="username"
              value="<?php echo escape($_POST['username'] ?? ''); ?>"
              required
            >
          </div>
        </div>

        <!-- Password -->
        <div class="field">
          <label class="field-label" for="password">Password</label>
          <div class="field-inner">
            <span class="field-icon"><i class="bi bi-lock-fill"></i></span>
            <input
              type="password"
              id="password"
              name="password"
              placeholder="Enter your password"
              autocomplete="current-password"
              required
            >
            <button type="button" class="pwd-btn" id="pwdToggle" title="Show / hide">
              <i class="bi bi-eye" id="pwdIcon"></i>
            </button>
          </div>
        </div>

        <button type="submit" class="btn-submit">
          <i class="bi bi-box-arrow-in-right"></i>
          Sign In to Dashboard
        </button>
      </form>

      <a href="<?php echo escape(site_url('index.php')); ?>" class="back-link">
        <i class="bi bi-arrow-left"></i>
        Back to website
      </a>

    </div>
  </div>
</div>

<script>
/* ══════════════════════════════════════════════════════
   Animated particle canvas — GPS signal network
══════════════════════════════════════════════════════ */
(function () {
  const canvas = document.getElementById('canvas');
  const ctx    = canvas.getContext('2d');
  let W, H;

  function resize() {
    W = canvas.width  = window.innerWidth;
    H = canvas.height = window.innerHeight;
  }
  resize();
  window.addEventListener('resize', () => { resize(); init(); });

  const COUNT    = 75;
  const MAX_DIST = 140;
  let   particles = [];

  function rand(min, max) { return Math.random() * (max - min) + min; }

  function init() {
    particles = [];
    for (let i = 0; i < COUNT; i++) {
      particles.push({
        x:    rand(0, W),
        y:    rand(0, H),
        vx:   rand(-.35, .35),
        vy:   rand(-.35, .35),
        r:    rand(1, 2.2),
        glow: Math.random() > .82,
        hue:  Math.random() > .6 ? '229,57,53' : Math.random() > .5 ? '59,130,246' : '255,255,255',
        alpha:rand(.15, .55),
      });
    }
  }
  init();

  function draw() {
    ctx.clearRect(0, 0, W, H);

    /* connections */
    for (let i = 0; i < particles.length; i++) {
      const a = particles[i];
      for (let j = i + 1; j < particles.length; j++) {
        const b  = particles[j];
        const dx = a.x - b.x, dy = a.y - b.y;
        const d  = Math.sqrt(dx * dx + dy * dy);
        if (d < MAX_DIST) {
          const op = (.08 * (1 - d / MAX_DIST)).toFixed(3);
          ctx.strokeStyle = `rgba(229,57,53,${op})`;
          ctx.lineWidth   = .6;
          ctx.beginPath();
          ctx.moveTo(a.x, a.y);
          ctx.lineTo(b.x, b.y);
          ctx.stroke();
        }
      }
    }

    /* particles */
    particles.forEach(p => {
      if (p.glow) {
        const g = ctx.createRadialGradient(p.x, p.y, 0, p.x, p.y, p.r * 6);
        g.addColorStop(0, `rgba(${p.hue},.45)`);
        g.addColorStop(1, `rgba(${p.hue},0)`);
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.r * 6, 0, Math.PI * 2);
        ctx.fillStyle = g;
        ctx.fill();
      }

      ctx.beginPath();
      ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
      ctx.fillStyle = `rgba(${p.hue},${p.alpha})`;
      ctx.fill();

      p.x += p.vx;
      p.y += p.vy;
      if (p.x < -10)    p.x = W + 10;
      if (p.x > W + 10) p.x = -10;
      if (p.y < -10)    p.y = H + 10;
      if (p.y > H + 10) p.y = -10;
    });

    requestAnimationFrame(draw);
  }
  draw();
})();

/* ══════════════════════════════════════════════════════
   Counter animation for stat numbers
══════════════════════════════════════════════════════ */
document.querySelectorAll('.stat-num[data-target]').forEach(el => {
  const target = +el.dataset.target;
  let   current = 0;
  const step = Math.ceil(target / 45);
  const timer = setInterval(() => {
    current = Math.min(current + step, target);
    el.textContent = current + (target >= 100 ? '' : 'K+');
    if (current >= target) {
      el.textContent = target + (target < 100 ? '' : '') + (target === 38 ? 'K+' : '');
      clearInterval(timer);
    }
  }, 28);
});

/* ══════════════════════════════════════════════════════
   Password toggle
══════════════════════════════════════════════════════ */
const pwdInput  = document.getElementById('password');
const pwdToggle = document.getElementById('pwdToggle');
const pwdIcon   = document.getElementById('pwdIcon');

pwdToggle.addEventListener('click', () => {
  const visible    = pwdInput.type === 'text';
  pwdInput.type    = visible ? 'password' : 'text';
  pwdIcon.className = visible ? 'bi bi-eye' : 'bi bi-eye-slash';
});

/* Auto-focus */
const u = document.getElementById('username');
if (!u.value) u.focus();
</script>

</body>
</html>
