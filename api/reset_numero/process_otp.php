<?php
require_once __DIR__ . '/../../config/db.php'; // debe definir $pdo

function normalizarPeruE164(string $tel): string {
    $tel = preg_replace('/[^\d+]/', '', $tel ?? '');
    // Si ya viene +51..., asegura últimos 9
    if (strpos($tel, '+51') === 0) {
        $digits = preg_replace('/\D/', '', $tel);
        return '+51' . substr($digits, 2, 9);
    }
    // 51XXXXXXXXX -> +51XXXXXXXXX
    $digits = preg_replace('/\D/', '', $tel);
    if (strlen($digits) === 11 && substr($digits, 0, 2) === '51') {
        return '+' . $digits;
    }
    // 9 dígitos -> +51 + 9
    if (strlen($digits) === 9) {
        return '+51' . $digits;
    }
    // fallback: últimos 9
    return '+51' . substr($digits, -9);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $telefonoInput = trim($_POST['telefono'] ?? '');
    $otp_code      = trim($_POST['otp_code'] ?? '');

    // Normaliza como E.164 y extrae últimos 9 para compatibilidad
    $telefonoE164 = normalizarPeruE164($telefonoInput);
    $solo9        = substr(preg_replace('/\D/', '', $telefonoE164), -9);

    // Busca por igualdad exacta E.164 o por últimos 9 dígitos (si en BD guardaron sin +51)
    $sql = "
        SELECT u.id_usuario
        FROM tb_usuario u
        INNER JOIN tb_persona p ON u.id_persona = p.id_persona
        WHERE p.telefono = :e164
           OR REPLACE(REPLACE(REPLACE(p.telefono, '+',''), ' ', ''), '-', '') LIKE CONCAT('%', :last9)
        LIMIT 1
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':e164' => $telefonoE164, ':last9' => $solo9]);
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$usuario) {
        exit('Número no reconocido.');
    }

    $id_usuario = (int)$usuario['id_usuario'];

    // Verifica OTP (vigente)
    $stmt = $pdo->prepare("
        SELECT id
        FROM otp_resets
        WHERE id_usuario = ? AND otp_code = ? AND expires_at > NOW()
        ORDER BY created_at DESC
        LIMIT 1
    ");
    $stmt->execute([$id_usuario, $otp_code]);
    $otp = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$otp) {
        exit('Código inválido o expirado.');
    }

    // OK -> ir a formulario de nueva contraseña del flujo telefónico
    header('Location: reset_password_phone.php?id_usuario=' . $id_usuario);
    exit;
} else {
    echo 'Acceso no permitido.';
}
