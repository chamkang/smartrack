<?php
require_once __DIR__ . '/../includes/functions.php';
init_session();
session_unset();
session_destroy();
redirect(site_url('admin/login.php'));
