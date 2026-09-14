<?php
require_once __DIR__ . '/app/auth.php';
requireLogin();

header('Content-Type: application/json; charset=UTF-8');

$connection = require __DIR__ . '/app/config/database.php';
$resource = $_POST['resource'] ?? $_GET['resource'] ?? '';
$action = $_POST['action'] ?? $_GET['action'] ?? 'list';

$definitions = [
    'items' => [
        'table' => 'items',
        'key' => 'id_item',
        'fields' => ['item_name', 'price', 'quantity'],
    ],
    'employees' => [
        'table' => 'employee',
        'key' => 'nip',
        'fields' => ['name', 'addres', 'username', 'password'],
    ],
];

function respond(array $payload, int $status = 200): never
{
    http_response_code($status);
    echo json_encode($payload);
    exit;
}

if (!isset($definitions[$resource])) {
    respond(['success' => false, 'message' => 'Resource tidak valid.'], 400);
}

$definition = $definitions[$resource];
$table = $definition['table'];
$key = $definition['key'];
$fields = $definition['fields'];

if ($action === 'list') {
    $result = mysqli_query($connection, "SELECT * FROM `$table` ORDER BY `$key` DESC");
    $rows = $result ? mysqli_fetch_all($result, MYSQLI_ASSOC) : [];
    respond(['success' => true, 'data' => $rows]);
}

$id = trim((string) ($_POST['id'] ?? ''));

if ($action === 'delete') {
    if ($id === '') {
        respond(['success' => false, 'message' => 'ID data wajib diisi.'], 422);
    }

    $statement = mysqli_prepare($connection, "DELETE FROM `$table` WHERE `$key` = ?");
    mysqli_stmt_bind_param($statement, 's', $id);
    mysqli_stmt_execute($statement);

    if (mysqli_stmt_affected_rows($statement) < 1) {
        respond(['success' => false, 'message' => 'Data tidak ditemukan.'], 404);
    }

    respond(['success' => true, 'message' => 'Data berhasil dihapus.']);
}

$values = [];
foreach ($fields as $field) {
    $value = trim((string) ($_POST[$field] ?? ''));
    if ($value === '') {
        respond(['success' => false, 'message' => "Kolom $field wajib diisi."], 422);
    }
    $values[$field] = $value;
}

if ($resource === 'items') {
    if (!is_numeric($values['price']) || (float) $values['price'] < 0) {
        respond(['success' => false, 'message' => 'Harga harus berupa angka nol atau lebih.'], 422);
    }
    if (!ctype_digit($values['quantity']) || (int) $values['quantity'] < 0) {
        respond(['success' => false, 'message' => 'Stok harus berupa bilangan bulat nol atau lebih.'], 422);
    }
}

if ($resource === 'employees' && strlen($values['username']) < 3) {
    respond(['success' => false, 'message' => 'Username minimal 3 karakter.'], 422);
}

if ($action === 'create') {
    $placeholders = implode(', ', array_fill(0, count($fields), '?'));
    $columns = implode('`, `', $fields);
    $statement = mysqli_prepare($connection, "INSERT INTO `$table` (`$columns`) VALUES ($placeholders)");
    mysqli_stmt_bind_param($statement, str_repeat('s', count($fields)), ...array_values($values));
} elseif ($action === 'update') {
    if ($id === '') {
        respond(['success' => false, 'message' => 'ID data wajib diisi untuk edit.'], 422);
    }

    $updates = implode(', ', array_map(fn ($field) => "`$field` = ?", $fields));
    $statement = mysqli_prepare($connection, "UPDATE `$table` SET $updates WHERE `$key` = ?");
    $parameters = array_values($values);
    $parameters[] = $id;
    mysqli_stmt_bind_param($statement, str_repeat('s', count($fields) + 1), ...$parameters);
} else {
    respond(['success' => false, 'message' => 'Aksi tidak valid.'], 400);
}

if (!mysqli_stmt_execute($statement)) {
    respond(['success' => false, 'message' => 'Database menolak perubahan. Pastikan data tidak duplikat.'], 409);
}

respond(['success' => true, 'message' => $action === 'create' ? 'Data berhasil ditambahkan.' : 'Data berhasil diperbarui.']);