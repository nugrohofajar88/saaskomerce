# AGENTS.md — saaskomerce

Platform **SaaS multi-tenant** buat UMKM bikin toko online sendiri (bukan niche spesifik — toko baju, kopi, sembako, dll). **1 codebase, 1 database, 1 deployment** — bukan split BFF+API kayak `larashop-be`/`larashop-fe`. Setiap toko = 1 **tenant**, diidentifikasi via subdomain.

- Stack: **Laravel 13, PHP 8.3+, MySQL**, Blade + Alpine.js (CDN) + Tailwind v4 (via Vite, **build di-commit** — sama kayak `larashop-fe`/`simple-pos-backoffice`, host gak punya Node).
- Asumsi hosting: shared cPanel kayak proyek lain di ekosistem ini (proc_open/exec sering dimatikan) — makanya multi-tenancy **hand-rolled**, bukan pakai package besar (mis. stancl/tenancy).
- Repo ini **baru** (dibuat dari rancangan ulang, bukan modifikasi `larashop-be`/`larashop-fe` yang sudah jalan) — lihat pola yang di-reuse di bagian bawah.

## Desain
UI didesain lewat Google Stitch (lihat `STITCH-PRD.md`). Token desain (warna Material-3-ish, tipografi Plus Jakarta Sans/Inter, radius) diterjemahkan ke `resources/css/app.css` (`@theme` buat warna/radius, `@layer utilities` buat kelas tipografi majemuk `text-headline-*`/`text-body-*`/`text-label-*`/`text-price-*` yang udah termasuk font-family+size+weight+leading+tracking dalam 1 class). Ikon pakai **Material Symbols Outlined** (webfont, lihat `layouts/base.blade.php`).

8 dari ~20 layar PRD sudah ke-generate Stitch & di-port 1:1 ke Blade (marketing: landing/signup/status; tenant admin: dashboard/produk/pesanan). **Semua layar lain** (storefront publik lengkap, kategori/rekening/pengaturan tenant admin, platform admin) sudah di-desain MANUAL mengikuti token/komponen yang sama (card `rounded-2xl bg-surface-container-lowest shadow-sm`, badge status pill warna semantik, toggle switch pill, dst) - belum pernah lewat Stitch, jadi kalau mau regenerate salah satunya lewat Stitch, paste prompt dari `STITCH-PRD.md` lalu port manual (bukan copy-paste HTML mentah - ganti `p-space-md` dkk ke kelas Tailwind numerik biasa, `font-headline-lg text-headline-lg` jadi cukup `text-headline-lg`). Landing page marketing juga sudah ditambah section "Kenapa Pilih Kami" (4 poin diferensiasi) + nav anchor (`#kenapa-kami`, `#fitur`, `#cara-kerja`) di luar PRD asli, atas permintaan user.

## Menjalankan (lokal)

```bash
composer install
php artisan migrate --seed   # seed: 1 platform admin + 2 tenant contoh (status active)
php artisan storage:link
npm install && npm run build
```

**Dev server + subdomain lokal**: Herd (kalau wildcard `*.test` DNS-nya nyambung di browser kamu) — park folder ini, otomatis `saaskomerce.test` + semua subdomainnya resolve. **Kalau DNS wildcard gak nyambung** (pernah kejadian di sandbox CLI, belum tentu masalah di browser beneran), fallback test manual:
```bash
php artisan serve --port=8099
curl --resolve kopisenja.saaskomerce.test:8099:127.0.0.1 http://kopisenja.saaskomerce.test:8099/
```
(`curl --resolve` bypass DNS sepenuhnya, gak perlu edit hosts file.)

Login seed: platform admin `admin@saaskomerce.test` / `password`; tenant admin `admin@kopisenja.test` / `password` (subdomain `kopisenja`) & `admin@bajumila.test` / `password` (subdomain `bajumila`).

## Arsitektur

**3 konteks, 1 app**, dibedakan dari Host header (bukan path):
- **Apex** (`saaskomerce.test`) — marketing + form signup tenant (`routes/web.php`, domain LITERAL).
- **`app.saaskomerce.test`** — platform super-admin, approve/suspend tenant (`routes/platform.php`, domain LITERAL).
- **`{apa pun}.saaskomerce.test`** — storefront publik (`routes/storefront.php`) + admin tenant di prefix `/admin` (`routes/tenant_admin.php`). **TANPA `Route::domain()`** — lihat gotcha di bawah kenapa.

### ⚠️ GOTCHA PALING PENTING: jangan pakai `Route::domain('{subdomain}.'+base)`

Awalnya storefront/tenant_admin pakai `Route::domain('{subdomain}.'.config('app.base_domain'))` biar jelas "ini rute subdomain tenant". **INI BUG BESAR**: domain dengan placeholder (`{subdomain}`) bikin Laravel nambahin nilainya sbg **route parameter PERTAMA** di SEMUA rute dalam grup itu. `ControllerDispatcher` Laravel nge-pass parameter ke method controller **SECARA POSISIONAL** (`$controller->{$method}(...array_values($parameters))`), BUKAN by-name — jadi semua argumen controller (`Order $order`, `ProductVariant $variant`, dst) **kegeser 1 posisi**, nerima nilai subdomain ("kopisenja") padahal harusnya nerima model/parameter aslinya. Gejalanya: `TypeError: Argument #1 must be of type X, string given`.

**Solusi yang dipakai**: `routes/storefront.php` & `routes/tenant_admin.php` **TANPA domain constraint sama sekali** — match host apa pun. Disambiguasi dari `routes/web.php` (apex) & `routes/platform.php` (app.) tetap aman karena keduanya pakai domain **literal** (tanpa `{}`, jadi TIDAK menambah parameter apa pun — terbukti `platform.login` dkk jalan normal tanpa butuh dummy parameter). Tenant context & keamanan akses 100% digantungkan ke `ResolveTenant` (baca `Host` header manual) + middleware `tenant.require`/`tenant.approved`, bukan ke parameter route Laravel. Kalau mau nambah proteksi domain-level lagi di masa depan, JANGAN pakai `Route::domain()` ber-placeholder — kalau beneran perlu, setiap controller method di bawahnya WAJIB deklarasi `string $subdomain` sbg parameter PERTAMA (unused, cuma buat jaga alignment).

### Multi-tenancy (hand-rolled)
- **`app/Models/Concerns/BelongsToTenant`** (trait) + **`TenantScope`** (global scope) — pasang di SEMUA model domain (`Product`, `ProductVariant`, `Order`, `OrderItem`, `Setting`, dst). Auto-filter `tenant_id` di query, auto-isi `tenant_id` saat create. **Gagal-keras** (throw `RuntimeException`) kalau dipanggil TANPA tenant context ter-bind — ini desain SENGAJA (bug ketahuan cepat, bukan kebocoran data senyap).
- **`User` model SENGAJA TIDAK pakai trait ini** — diakses dari 3 konteks (platform/tenant_admin/customer), termasuk konteks platform yang MEMANG tanpa tenant. Filter tenant_id dilakukan eksplisit di controller (login, dsb).
- **`App\Support\TenantContext`** + helper global `tenant()`/`tenant_id()` (`app/Support/helpers.php`, didaftarkan via `composer.json` → `autoload.files`) — akses tenant aktif, mirip pola `auth()`/`request()` bawaan Laravel.
- **`ResolveTenant` middleware** — WAJIB **prepend** (bukan append) ke grup `web` di `bootstrap/app.php`. Alasan: `SubstituteBindings` (route model binding, mis. `{order:order_number}`) ada di AKHIR grup `web` bawaan Laravel, dan binding itu butuh tenant context SUDAH ter-bind duluan (karena model-nya pakai global scope tenant_id). Kalau accidentally di-`append`, implicit binding bakal throw `TenantContext::require()` duluan sebelum sempat ke-bind.
- **Escape hatch lintas-tenant**: `Model::withoutTenantScope()` (dari trait). Dipakai di command cron (`orders:expire-unpaid`, lihat gotcha StockService di bawah) & dashboard platform admin.
- **Guard terpisah** per konteks login (`config/auth.php`): `platform`, `tenant_admin`, `customer` — 1 tabel `users` yang sama, tapi beda nama guard = beda session key, jadi user yang login di tenant A gak otomatis ke-auth di tenant B.
- **`EnsureTenantMatchesAuth` middleware** (`tenant.match-auth:<guard>`) — `SESSION_DOMAIN` di-set wildcard (`.saaskomerce.test`) biar cookie nyambung lintas-subdomain (kenyamanan), TAPI itu artinya guard session (mis. `tenant_admin`) tetap "authenticated" walau user pindah ke subdomain tenant LAIN. Middleware ini cek `user->tenant_id` vs `tenant()->id`, kalau beda → **logout + invalidate session + REDIRECT** (bukan cuma logout lalu lanjut — logout doang gak ngeblokir request yang LAGI jalan, cuma ngaruh ke request berikutnya; sempat jadi bug security nyata saat dites: admin tenant A bisa lanjut lihat dashboard tenant B selama logout-nya gak diikuti redirect).
- **`$middleware->redirectGuestsTo(...)` di `bootstrap/app.php`** — WAJIB di-custom. Default Laravel redirect guest yg kena `auth:<guard>` ke route literal `login`, yang gak ada di app ini (ada 3: `platform.login`/`tenant.login`/`storefront.login`). Tanpa ini → `RouteNotFoundException` tiap kali guest kena middleware auth.

### StockService & query lintas-tenant
`App\Support\StockService::reserveForOrder()`/`releaseForOrder()` (mekanisme atomik di-port dari `larashop-be`) **query `OrderItem::withoutTenantScope()` LANGSUNG** (bukan lewat relasi `$order->items`) — relasi tetap kena global scope `OrderItem` sendiri walau `$order`-nya didapat dari `Order::withoutTenantScope()`. Ini kepake di `orders:expire-unpaid` (command cron, lintas-tenant, TANPA tenant context sama sekali) — makanya command itu juga **TIDAK** eager-load `->with('items')` di query `Order`-nya (bakal throw duluan sebelum sempat ke StockService).

### Setting (tenant-scoped)
`App\Support\SettingService` — instance baru per pemanggilan (`new SettingService()` = tenant aktif, atau `new SettingService($tenantId)` buat command lintas-tenant), BUKAN static/global kayak larashop-be. Cache di level instance, bukan static property.

## Pola yang di-reuse dari `larashop-be`/`larashop-fe` (referensi desain, bukan hasil copy-paste)
- **Varian = sumber kebenaran** — `Product` TIDAK punya kolom price/stock/weight, `ProductVariant` satu-satunya sumber kebenaran, accessor di `Product` nurunin dari `defaultVariant()`. Pola ini sudah generik dari sananya.
- **Order snapshot pattern** — `order_items` simpan `product_name/sku/variant_label/price/subtotal` denormalized, FK `nullOnDelete`.
- **`GuestCart` → `App\Support\Cart`** — redesign: session key di-namespace per `tenant_id` (`cart.tenant.{id}`), hydrate harga LANGSUNG via Eloquent (bukan HTTP call ke API terpisah kayak di larashop-fe, karena ini monolith).
- **`ApiData` presenter TIDAK di-port** — itu solve masalah kontrak JSON lintas-proses (BE↔FE beda repo); di monolith ini gak ada boundary itu, Blade pakai model langsung.
- Konvensi kode: `casts()` method (bukan `$casts` property), `$request->validate()` inline (bukan Form Request), controller tipis + logic di `app/Support/*Service.php`.

### Subdomain HARUS lowercase
`ResolveTenant` cocokkan `Tenant::whereRaw('LOWER(subdomain) = ?', [strtolower($label)])` dengan sengaja (bukan `where('subdomain', $label)` polos) — Host header HTTP SELALU lowercase (Symfony `getHost()` normalize), tapi MySQL (collation default case-insensitive) vs SQLite (dipakai test, case-SENSITIVE) bisa beda perilaku kalau ada baris `tenants.subdomain` mixed-case (mis. dibuat lewat tinker/seeder ceroboh, bukan lewat form signup yang sudah `strtolower()`). Insight ini ketahuan dari test suite (lihat bagian Testing).

## MVP yang SUDAH ada
Signup tenant (pending) → approval manual platform admin → login admin tenant → kelola kategori/produk/varian (+upload gambar, UPSERT by id biar order_items gak putus) → storefront publik → customer daftar/login → cart (session) → checkout → order `pending_payment` + reserve stok atomik → upload bukti transfer → admin tandai lunas.

**Konten "Tentang Toko" per-tenant** — `Setting` key `about_content`/`why_us_content` (textarea biasa, bukan repeater/CMS penuh - sengaja simpel), dikelola dari `/admin/about`, tampil di storefront `/tentang`. Halaman publiknya 404 kalau dua-duanya kosong (`SettingService::hasAboutPage()`), dan link nav "Tentang" di storefront ikut disembunyikan - jangan kasih link ke halaman kosong.

**Detail produk storefront** — galeri foto dgn thumbnail (Alpine, bukan cuma 1 gambar statis), varian sbg pill button (bukan `<select>`, harga ikut update live sesuai varian dipilih), + "Produk Lainnya" (produk lain di kategori yang sama, exclude diri sendiri, limit 4).

**Cron** (`routes/console.php`, `Schedule::call()` bukan `::command()` — pola sama kayak proyek lain, antisipasi proc_open dimatikan di shared hosting):
- `orders:expire-unpaid` (tiap jam) — order `pending_payment` lewat batas waktu → `expired` + stok direlease.
- `tenants:suspend-expired-trials` (harian) — tenant `status=trial` yang `trial_ends_at`-nya lewat → `suspended` otomatis (bisa di-approve manual lagi dari antrean platform admin kalau ternyata sudah bayar).

## Testing
`php artisan test` (PHPUnit biasa, SQLite `:memory:`, lihat `phpunit.xml`). `tests/Concerns/CreatesTenants.php` = helper bikin tenant/produk/dll tanpa perlu lewat HTTP. Semua gotcha multi-tenancy di atas (Route::domain, EnsureTenantMatchesAuth redirect, StockService withoutTenantScope, subdomain lowercase) **punya test regresi** — kalau mau refactor area itu, jalankan test dulu/sesudah buat mastiin gak ke-ulang.

## Di luar cakupan (schema sudah siap, belum di-wire)
- Ongkir (Komerce), QRIS, WA bot — per-tenant, nanti pakai tabel kredensial baru (`tenant_integration_credentials`), BUKAN `.env` global.
- Billing/subscription otomatis — `tenants.status`/`plan` masih manual (auto-SUSPEND saat trial habis sudah ada, tapi belum ada cara tenant "bayar" buat balik `active` selain platform admin approve manual lagi).
- Multi-admin per tenant — `users.tenant_id` sudah FK biasa, tinggal nambah rute invite-staff tanpa migrasi.
- Custom domain — kolom `tenants.custom_domain` sudah ada (nullable, belum dipakai).

## Belum deploy
Masih tahap MVP lokal. Belum ada subdomain produksi/hosting yang disiapkan — deploy nanti ikuti pola `Schedule::call()` + `composer dump-autoload --no-scripts` dkk seperti proyek lain di ekosistem ini kalau host-nya shared cPanel serupa.
