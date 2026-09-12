<?php
session_start();

function requireLogin(): void
{
    if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }
}

function currentUser(): ?string
{
    return $_SESSION['user'] ?? null;
}
