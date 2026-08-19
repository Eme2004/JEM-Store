<p align="center">
  <img src="https://capsule-render.vercel.app/api?type=waving&color=0:161616,100:2e2e2e&height=180&section=header&text=JEM%20Store&fontSize=58&fontColor=ffffff&animation=fadeIn&fontAlignY=38&desc=Nothing%20is%20casual.&descAlignY=58&descSize=18&descColor=c4d8f0" alt="JEM Store" width="100%" />
</p>

<p align="center">
  <a href="https://github.com/Eme2004/JEM-Store/actions/workflows/deploy.yml">
    <img src="https://github.com/Eme2004/JEM-Store/actions/workflows/deploy.yml/badge.svg" alt="Deploy status" />
  </a>
  <img src="https://img.shields.io/badge/Laravel-12-FF2D20?logo=laravel&logoColor=white" alt="Laravel 12" />
  <img src="https://img.shields.io/badge/PHP-%5E8.2-777BB4?logo=php&logoColor=white" alt="PHP ^8.2" />
  <img src="https://img.shields.io/badge/tests-118%20passing-2ea44f" alt="118 tests passing" />
  <img src="https://img.shields.io/badge/Pagos-Braintree%20Sandbox-0574EF" alt="Braintree Sandbox" />
</p>

<p align="center">
  <img src="https://readme-typing-svg.demolab.com/?font=Georgia&size=18&pause=1500&color=161616&center=true&vCenter=true&width=560&lines=Cat%C3%A1logo+%C2%B7+Carrito+%C2%B7+Checkout+con+pago+real+(sandbox);Panel+de+administraci%C3%B3n+%C2%B7+Historial+de+pedidos;Reportes+de+ventas+en+PDF+%C2%B7+CI%2FCD+autom%C3%A1tico" alt="Typing SVG" />
</p>

# JEM Store

Tienda de moda contemporánea construida con Laravel — catálogo, carrito, checkout con
pasarela de pago real en modo **sandbox** (Braintree), panel de administración, historial
de pedidos y reportes de ventas en PDF.

Proyecto académico / de portafolio. Ver [`docs/PRODUCT_IMAGES.md`](docs/PRODUCT_IMAGES.md)
y la página [Disclaimer](https://jemstore.alwaysdata.net/legal/disclaimer) para el detalle
de qué es real y qué es simulado.

**Producción:** https://jemstore.alwaysdata.net

![JEM Store — home](docs/images/readme-hero.jpg)

## Características

- **Catálogo** con búsqueda, filtros por categoría, público (hombre/mujer/unisex), rango
  de precio y ofertas.
- **Carrito y checkout** con cálculo de impuestos y envío, y pago con tarjeta real vía
  **Braintree Sandbox** (Hosted Fields — el número de tarjeta y el CVV nunca tocan el
  servidor de JEM Store, solo un token generado por Braintree en el navegador).
- **Cuentas de usuario**: registro, login, perfil, historial de pedidos con detalle de la
  transacción de pago.
- **Panel de administración** (`/admin/productos`): crear, editar y eliminar productos,
  con subida de foto, preview instantáneo y reemplazo/eliminación seguros.
- **Reportes de ventas** filtrables por mes y cliente, con descarga en **PDF** con diseño
  propio (logo, indicadores, tabla y resumen).
- **Páginas legales** propias (Disclaimer, Terms of Use, Privacy Policy).
- Diseño responsive de extremo a extremo (mobile / tablet / desktop).
- **Despliegue automático**: cada push a `main` corre los tests, compila los assets y
  despliega a producción vía SSH — sin FTP, sin pasos manuales.

## Stack

| | |
|---|---|
| Backend | Laravel 12 (PHP ^8.2) |
| Base de datos | SQLite |
| Frontend | Blade, Bootstrap 5, Sass, Vite |
| Pagos | Braintree Sandbox (`braintree/braintree_php`) |
| PDF | `barryvdh/laravel-dompdf` |
| Hosting | [alwaysdata](https://www.alwaysdata.com/) |
| CI/CD | GitHub Actions (tests → build → rsync/SSH) |

## Empezar en local

```bash
git clone https://github.com/Eme2004/JEM-Store.git
cd JEM-Store

composer install
npm install

cp .env.example .env
php artisan key:generate

touch database/database.sqlite
php artisan migrate --seed

php artisan storage:link

npm run build   # o `npm run dev` para desarrollo
php artisan serve
```

La app queda disponible en `http://localhost:8000`.

### Imágenes de producto

Las fotos del catálogo viven en `storage/app/public/products/{slug}.webp` (curadas y
documentadas en [`docs/PRODUCT_IMAGES.md`](docs/PRODUCT_IMAGES.md)) y ya vienen en el
repo. Para sincronizarlas contra los productos existentes sin tocar precio, stock ni
ningún otro dato:

```bash
php artisan products:sync-images
```

Las fotos subidas manualmente desde el panel de administración se guardan aparte, en
`storage/app/public/products/uploads/`, y ese comando nunca las toca ni las revierte.

### Pagos (Braintree Sandbox)

Sin credenciales configuradas, el checkout usa automáticamente una pasarela simulada
equivalente — la app y los tests funcionan igual, solo que sin pegarle a la API real de
Braintree. Para probar el sandbox real, agregá a tu `.env`:

```
BRAINTREE_ENV=sandbox
BRAINTREE_MERCHANT_ID=
BRAINTREE_PUBLIC_KEY=
BRAINTREE_PRIVATE_KEY=
```

(Se generan gratis creando una cuenta en [Braintree Sandbox](https://www.braintreepayments.com/).
Nunca se procesan cobros reales en este modo — es el ambiente de pruebas oficial.)

Tarjeta de prueba: `4111 1111 1111 1111`, cualquier fecha futura, cualquier CVV de 3
dígitos.

## Tests

```bash
php artisan test
```

118 tests / 305 assertions cubriendo catálogo, carrito, checkout (pago aprobado,
rechazado, doble envío), cuentas, pedidos, panel admin, reportes y páginas legales.

## Despliegue

Cada push a `main` dispara `.github/workflows/deploy.yml`:

```
push a main
  → tests (php artisan test)
  → build (npm run build)
  → composer install --no-dev
  → rsync/SSH a alwaysdata
  → migrate --force · storage:link · products:sync-images · optimize
```

`.env` y `database/database.sqlite` de producción nunca se tocan ni se sobrescriben — el
deploy no usa `rsync --delete` y excluye ambos archivos explícitamente.

## Créditos

Website & Branding — Emesis Mairena y Jairo Herrera.

Fotografía de producto y del home: Unsplash / Pexels, uso libre. Fuentes documentadas en
[`docs/PRODUCT_IMAGES.md`](docs/PRODUCT_IMAGES.md) y [`docs/HOME_IMAGES.md`](docs/HOME_IMAGES.md).

<p align="center">
  <img src="https://capsule-render.vercel.app/api?type=waving&color=0:161616,100:2e2e2e&height=100&section=footer" alt="" width="100%" />
</p>
