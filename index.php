<?php
require_once __DIR__ . '/includes/auth.php';

if (is_logged_in()) {
    header('Location: /sia/' . current_role() . '/index.php');
} else {
    header('Location: /sia/auth/login.php');
}
exit;
