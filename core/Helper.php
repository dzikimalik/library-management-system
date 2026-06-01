<?php
require_once __DIR__ . '/Session.php';

function csrf_token()
{
    if (!Session::has('csrf_token')) {
        $token = bin2hex(random_bytes(32));
        Session::set('csrf_token', $token);
    }
    return Session::get('csrf_token');
}

function csrf_field()
{
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}

function verify_csrf($token)
{
    if (!Session::has('csrf_token')) {
        return false;
    }
    return hash_equals(Session::get('csrf_token'), $token);
}

function sanitize($input)
{
    if (is_array($input)) {
        return array_map('sanitize', $input);
    }
    return htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
}

function redirect($url)
{
    header('Location: ' . $url);
    exit;
}

function old($key)
{
    return get_flash('old_' . $key);
}

function set_flash($key, $message)
{
    Session::set('flash_' . $key, $message);
}

function get_flash($key)
{
    $value = Session::get('flash_' . $key);
    Session::delete('flash_' . $key);
    return $value;
}

function formatDate($date)
{
    $months = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $timestamp = strtotime($date);
    $day = date('j', $timestamp);
    $month = $months[date('n', $timestamp) - 1];
    $year = date('Y', $timestamp);
    return "$day $month $year";
}

function rupiah($number)
{
    return 'Rp ' . number_format($number, 0, ',', '.');
}

function tgl_indo($date)
{
    $months = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    $timestamp = strtotime($date);
    $day = date('j', $timestamp);
    $month = $months[date('n', $timestamp) - 1];
    $year = date('Y', $timestamp);
    $time = date('H:i', $timestamp);
    return "$day $month $year $time";
}

function excerpt($text, $limit)
{
    if (strlen($text) <= $limit) {
        return $text;
    }
    return substr($text, 0, $limit) . '...';
}
