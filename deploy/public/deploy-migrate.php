<?php

/**
 * Corre "php artisan migrate --force" desde el navegador.
 *
 * InfinityFree (plan gratuito) no da acceso SSH ni cron, así que no hay
 * forma normal de correr migraciones en el servidor. Este script es la
 * salida práctica: bootea Laravel igual que public/index.php y ejecuta
 * únicamente el comando "migrate", protegido por un token que solo tú
 * conoces (DEPLOY_TOKEN en el .env del servidor — ver .env.infinityfree.example).
 *
 * IMPORTANTE:
 *   - Sin DEPLOY_TOKEN configurado en el servidor, este script rechaza
 *     cualquier request (falla cerrado, no abierto).
 *   - Después de correr las migraciones que necesites, considera borrar
 *     este archivo del servidor (o al menos cambiar DEPLOY_TOKEN) para
 *     no dejar un endpoint de administración expuesto indefinidamente.
 *   - Uso: https://tu-dominio/deploy-migrate.php?token=TU_DEPLOY_TOKEN
 */

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$expectedToken = env('DEPLOY_TOKEN');
$providedToken = $_GET['token'] ?? '';

if (! $expectedToken || ! is_string($providedToken) || ! hash_equals($expectedToken, $providedToken)) {
    http_response_code(403);
    header('Content-Type: text/plain; charset=utf-8');
    echo "Forbidden.\n";
    exit;
}

header('Content-Type: text/plain; charset=utf-8');

Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);

echo Illuminate\Support\Facades\Artisan::output();
