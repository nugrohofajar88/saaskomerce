# SaasKomerce — Design PRD for Google Stitch

> **How to use this file with Stitch (stitch.withgoogle.com):**
> 1. Start a new project. Paste **Section 2 (Design System)** first as global style/context.
> 2. Generate each screen one at a time by pasting its block from **Section 5 (Screens)** — pick the right Base Prompt (3A storefront/marketing, or 3B dashboard) for that screen first.
> 3. Keep the design system text at the top of every prompt if Stitch loses context between screens.
> 4. UI copy is in **Bahasa Indonesia**. Layout/structure instructions are in English.

---

## 1. Product Overview

**SaasKomerce** is a **multi-tenant SaaS platform** that lets any small Indonesian business ("UMKM" — a coffee shop, a clothing store, a grocery store, anything) spin up their own online store in minutes. One codebase, one database — each store is a **tenant**, identified by its own subdomain (`tokokamu.saaskomerce.com`).

There are **three distinct audiences**, each with their own screens:

1. **Marketing site (public)** — anyone considering signing up a store. Goal: look trustworthy and easy, convert visitors into signups. *Mobile-first, warm/approachable.*
2. **Tenant Admin (store owner)** — the UMKM owner managing their own store: products, orders, payments, settings. *Desktop-first dashboard, but must also work on a phone since many owners will check orders on mobile.*
3. **Storefront (shopper)** — the end customer browsing and buying from one specific tenant's store. *Mobile-first, needs to feel generic/neutral enough to suit ANY product category (coffee, clothes, groceries), not locked to one niche.*

*(There is also a fourth, internal-only "Platform Admin" area for approving new tenant signups — low priority for Stitch, utilitarian, see Section 6.)*

**Brand personality:** modern, clean, trustworthy SaaS — approachable for a small business owner who isn't tech-savvy, NOT cold/corporate enterprise software. Think "friendly toolkit," not "intimidating dashboard."

**Tech context (for reference only — Stitch outputs UI, not this stack):** Laravel Blade + Tailwind CSS, no JS framework (light Alpine.js for small interactions). Designs should map cleanly to utility-class HTML.

---

## 2. Design System (paste this first into Stitch)

### Color palette
- **Background (app canvas):** soft neutral `#F8F7FB` (very light violet-tinted gray)
- **Surface / cards:** white `#FFFFFF`
- **Primary accent (brand):** indigo — `#4F46E5` (indigo-600), darker `#4338CA` (indigo-700)
- **Primary brand gradient:** linear `#6366F1 → #8B5CF6` (indigo → violet; used on logo mark, avatars, hero accents)
- **Text — heading:** near-black `#111827` (gray-900)
- **Text — body:** `#374151` (gray-700)
- **Text — muted/secondary:** `#6B7280` (gray-500)
- **Borders / dividers:** `#E5E7EB` (gray-200)
- **Success (paid/active/approved):** emerald-50 bg + emerald-700 text
- **Warning (pending/trial/closed):** amber-50 bg + amber-700 text
- **Danger (suspended/cancelled/error):** rose-50 bg + rose-700 text
- **Price:** bold gray-900 (NOT a loud color — keep it calm/trustworthy, this is money across many different store types)
- Subtle **indigo radial glow** behind marketing hero / auth card headers (soft, ~15% opacity).

### Typography
- **Headings/display:** **"Plus Jakarta Sans"**, weight 600–700, tight tracking. Friendly-but-sharp geometric sans, works for both marketing headlines and dashboard titles.
- **Body/UI:** **"Inter"**, weights 400–600.
- Small **eyebrow labels** above section titles: uppercase, letter-spacing 0.2em, indigo-600, small size.

### Shape & elevation
- **Rounded** corners: cards `1rem–1.25rem` (rounded-2xl), buttons/pills fully rounded or `0.75rem`, inputs `0.75rem`.
- **Soft, low-contrast shadows** — small blur, barely-there on dashboard tables, slightly more pronounced on marketing/storefront cards. Cards lift very slightly on hover (consumer-facing screens only — not on dense admin tables).
- Inputs: rounded-xl, gray-50 fill, indigo border + soft indigo focus ring on focus.

### Iconography
- **Outline icons** (Heroicons/Lucide style), 1.5–1.75 stroke. Gray-400 default, indigo-600 when active.

### Buttons
- **Primary:** indigo-600 fill, white text, rounded-xl, soft indigo shadow.
- **Secondary / ghost:** white fill, gray-300 border, hover → indigo border + indigo text.
- **Destructive:** rose-600 fill or rose ghost (suspend, delete, reject).

### Status badges (used everywhere: tenant status, order status)
Small rounded-full pill, colored by state:
- `pending` / `trial` → amber
- `active` / `paid` / `approved` → emerald
- `suspended` / `cancelled` / `expired` → rose
- `pending_payment` → amber, `processing` neutral gray

### Reusable components
- **Surface card** — white, rounded-2xl, soft shadow, thin gray-200 border, `p-5`–`p-6`.
- **Stat tile** (dashboards) — big number (Plus Jakarta Sans bold), small label above, tiny icon top-right in a soft indigo-50 circle.
- **Empty state** — centered icon in a soft gray circle, muted one-line message, optional CTA button.
- **Data table row** (admin) — hover bg gray-50, status pill right-aligned, row-level actions appear on hover (desktop) or always-visible icon buttons (mobile card view).

---

## 3. Base Prompts

### 3A — Consumer-facing (Marketing + Storefront)

```
Design a mobile-first screen for "SaasKomerce" using the design system above (indigo/violet SaaS palette, Plus Jakarta Sans headings, Inter body, rounded-2xl cards, soft shadows). UI copy in Bahasa Indonesia. Friendly, modern, trustworthy small-business feel — NOT generic cold enterprise software.

SCREEN TO DESIGN:
<<paste one screen block from Section 5.A or 5.C here>>
```

### 3B — Dashboard (Tenant Admin + Platform Admin)

```
Design a desktop-first dashboard screen for "SaasKomerce" using the design system above (indigo/violet SaaS palette, Plus Jakarta Sans headings, Inter body). Left vertical sidebar navigation, top bar with page title. Dense, utilitarian but still warm (not cold enterprise gray) — this is for a small-business owner, not an IT department. UI copy in Bahasa Indonesia. Must also look reasonable responsively stacked on mobile (owners check this on their phone).

SCREEN TO DESIGN:
<<paste one screen block from Section 5.B here>>
```

---

## 4. Global Navigation

### Marketing site (public, no login)
- Simple top bar: logo mark (indigo-violet gradient rounded-square with a small storefront/shop icon) + "SaasKomerce" wordmark, left. Right: ghost "Masuk" (owner login — can skip for v1) + primary **"Daftar Toko Gratis"** button.
- Footer: minimal, logo + one line tagline + copyright.

### Storefront (per-tenant, customer-facing)
- **Desktop:** sticky top bar — left: store name (NOT the SaasKomerce brand — this is the tenant's own storefront) as the "logo" text; center/right: nav links "Keranjang" (badge with item count), "Pesanan Saya" (only if logged in), account menu or "Masuk" button.
- **Mobile:** simple top bar (store name + cart icon with badge) is enough; keep it light since stores can sell anything from coffee to clothes — avoid icons that imply one specific category.
- A thin **amber banner** below the header when the store is temporarily closed: "Toko sedang tutup sementara — katalog masih bisa dilihat, tapi belum bisa menerima pesanan baru."

### Tenant Admin dashboard
- **Left sidebar** (collapsible on mobile to a hamburger/top bar): store name at top, then nav items with icons — Dashboard, Kategori, Produk, Pesanan, Rekening Pembayaran, Pengaturan. Active item = indigo-50 pill background + indigo text + indigo left border accent.
- **Top bar:** page title (left), nothing fancy on the right except maybe a small "Keluar" (logout).

### Platform Admin (internal, low design priority)
- Same sidebar pattern as Tenant Admin but single nav item for now ("Tenant"), clearly a more "ops tool" feel — can be plainer/denser.

---

## 5. Screens (generate each one in Stitch)

### 5.A Marketing

#### 5.A.1 Landing / Home `(route: /)`
**Purpose:** convert a visiting UMKM owner into a signup.
**Layout (top → bottom):**
1. **Hero section:** eyebrow "PLATFORM TOKO ONLINE UNTUK UMKM", big Plus Jakarta Sans headline ("Bikin toko online kamu sendiri, dalam hitungan menit"), muted subheadline, primary CTA **"Daftar Toko Gratis"** + ghost "Lihat Contoh Toko". Soft indigo radial glow behind, maybe a simple illustrative mockup of a phone showing a storefront.
2. **Feature grid** (3 columns desktop / stacked mobile): 3 surface cards each with an icon in an indigo-50 circle + short title + 1-line description. Example features: "Katalog & Varian Produk" (mengelola stok per varian), "Pesanan Masuk Otomatis" (checkout & pembayaran manual transfer), "Kelola dari HP" (dashboard responsif).
3. **How it works** (3-step horizontal strip, numbered circles 1-2-3): "Daftar" → "Admin approve" → "Toko kamu live".
4. **Simple pricing teaser** (optional, can be a single centered card): "Gratis buat mulai" + a short feature bullet list + CTA.
5. Footer.

#### 5.A.2 Signup Form `(route: /daftar)`
- Centered card (max-w-md) on the soft indigo-glow background.
- Title "Daftarkan Toko" + muted subtitle.
- Fields: Nama Toko, Subdomain (text input with a **suffix label** showing ".saaskomerce.com" so the owner sees their future URL live), Nama Pemilik, Email, No. WhatsApp, Password, Konfirmasi Password.
- Primary full-width **"Daftar"** button.
- Small muted note below: "Toko kamu akan ditinjau dulu sebelum aktif."

#### 5.A.3 Signup Success `(route: /daftar/selesai)`
- Centered card, success state: a big soft-emerald circle with a checkmark icon.
- Title "Pendaftaran terkirim!" + message showing the store name and future subdomain URL, explaining it's pending review.

---

### 5.B Tenant Admin (dashboard, desktop-first)

#### 5.B.1 Login `(route: /admin/login)`
- Centered card, store name shown above the form (this is THIS store's admin login, not the SaasKomerce brand).
- Email + Password fields, primary "Masuk" button.

#### 5.B.2 Dashboard `(route: /admin)`
- Page title "Dashboard".
- **3 stat tiles** in a row: "Produk" (count, box icon), "Pesanan Menunggu Bayar" (count, amber accent, clock icon), "Pesanan Lunas" (count, emerald accent, check icon).
- Below: an empty-ish "Pesanan Terbaru" table/list placeholder (a few rows: order number, customer, total, status pill) — room to grow into a richer dashboard later.

#### 5.B.3 Kategori `(route: /admin/categories)`
- Page title + primary "+ Tambah Kategori" button top-right.
- Simple list/table: name, sort order, edit/delete row actions.
- "+ Tambah"/"Edit" open a simple form (name + sort order) — can be inline or a side panel/modal.

#### 5.B.4 Produk — List `(route: /admin/products)`
- Page title + "+ Tambah Produk" button.
- **Table/card list**, each row: small square product thumbnail (or placeholder icon if none), name, category pill, price (from default variant), total stock, active/inactive status pill, edit/delete actions.

#### 5.B.5 Produk — Form (Create/Edit) `(route: /admin/products/create, /admin/products/{id}/edit)`
**This is the most complex screen — give it care.**
- Two-section form: product basics (Nama, Kategori dropdown, Deskripsi textarea, Aktif toggle, image upload with thumbnail previews + delete button per image) THEN a **"Varian"** section below.
- **Variant section:** a repeatable row group — each row has SKU, Label (e.g. "500ml"), Harga, Stok, Berat (gram), a "Default" checkbox/radio, and a remove (trash) icon. A **"+ Tambah Varian"** ghost button below the rows adds a new empty row. Small muted helper line above: "Minimal 1 varian wajib diisi — harga & stok produk diambil dari varian."
- Primary "Simpan" button at the bottom.

#### 5.B.6 Pesanan — List `(route: /admin/orders)`
- Page title + status filter pills (Semua, Menunggu Bayar, Lunas, Batal, Kedaluwarsa).
- List/table rows: order number (+ small amber "Ada bukti bayar" badge if a proof was uploaded — these rows should visually stand out/sort first), customer name, total, status pill, relative time ("2 jam lalu").

#### 5.B.7 Pesanan — Detail `(route: /admin/orders/{id})`
- Back link, order number + status pill header.
- Customer info block (name, phone, email, shipping address if any).
- Item list (qty × name + variant label, subtotal per line).
- Total row (bold, larger).
- **Payment proof image** (if uploaded) shown as a clickable/zoomable thumbnail.
- Primary **"Tandai Lunas"** button (only shown when still pending payment).

#### 5.B.8 Rekening Pembayaran `(route: /admin/payment-accounts)`
- Page title + "+ Tambah Rekening" button.
- List of bank account cards: bank name + account number (bold, larger), account holder name below, active/inactive pill, edit/delete.

#### 5.B.9 Pengaturan `(route: /admin/settings)`
- Simple settings form in a surface card: two toggle switches — "Toko buka (terima pesanan baru)" and "Terima pembayaran transfer manual" — each with a short description line under the label. Primary "Simpan" button.

---

### 5.C Storefront (per-tenant, customer-facing, mobile-first)

> Keep this generic enough to photograph well for a coffee shop, a clothing store, or a grocery store — don't bake in category-specific visual metaphors.

#### 5.C.1 Katalog / Home `(route: /)`
- Store name as header title (no SaasKomerce branding visible here).
- Closed-store amber banner (see Section 4) when applicable.
- **Product grid:** 2 columns mobile / 4 desktop. Each card: square product image (placeholder generic shopping-bag icon if none), name (2-line max), price (bold, calm gray-900, not a loud accent color since this spans many product categories).
- Empty state if no products yet.

#### 5.C.2 Detail Produk `(route: /produk/{slug})`
- Back link, large product image, name (Plus Jakarta Sans), description paragraph.
- **Variant selector:** a dropdown or selectable pill row (e.g. "500ml — Rp18.000", "Large — Rp22.000"); out-of-stock variants shown disabled/struck-through.
- Quantity stepper.
- Primary **"Tambah ke Keranjang"** button, full width.

#### 5.C.3 Keranjang `(route: /keranjang)`
- Title "Keranjang".
- Cart item rows: thumbnail + name/variant + unit price, quantity stepper, line subtotal, remove (trash) icon.
- Summary footer (sticky on mobile): total (bold, large) + primary **"Checkout"** button.
- Empty state: muted icon + "Keranjang masih kosong" + ghost "Mulai belanja" link.

#### 5.C.4 Login / Daftar `(route: /login, /daftar)`
- Same centered-card pattern as marketing signup but simpler (just Nama/Email/No.WA/Password for register; Email/Password for login). Store name shown above the card instead of the SaasKomerce brand.

#### 5.C.5 Checkout `(route: /checkout)`
- Order summary card (items + total) left/top.
- Form: Nama Penerima, No. WhatsApp, Alamat Pengiriman (textarea).
- Primary **"Buat Pesanan"** button.

#### 5.C.6 Konfirmasi Pesanan `(route: /pesanan/{order_number})`
- Order number + status pill header.
- Item list + total.
- **"Transfer ke salah satu rekening"** card listing the tenant's active bank accounts (bank name, account number, holder name).
- **Upload bukti transfer** — file input + "Kirim Bukti" button; once uploaded, show the uploaded image with a muted "menunggu konfirmasi admin" note instead of the form.

#### 5.C.7 Pesanan Saya `(route: /pesanan)`
- Title "Pesanan Saya".
- List of past orders: order number, date, total, status pill — each row links to 5.C.6's detail view.
- Empty state: "Belum ada pesanan" + "Mulai belanja" link.

---

## 6. Platform Admin (internal tool, optional for Stitch, low priority)

If time allows: same sidebar dashboard pattern as Tenant Admin (Section 3B / 4), plainer and denser.
- **Login** — plain centered card.
- **Tenant queue** — status filter pills (Pending, Trial, Active, Suspended) + a list: store name + subdomain, owner name/email/phone, status pill, signup date, and action buttons ("Setujui (Trial)" emerald, "Suspend" rose-ghost) shown contextually by status.

---

## 7. Content & Tone Guidelines
- **Language:** Bahasa Indonesia throughout, simple and direct — this audience is small-business owners, not developers.
- **Currency:** Indonesian Rupiah formatted "Rp18.000".
- **Dates:** Indonesian format, relative where it helps ("2 jam lalu").
- Keep CTAs short and action-first: "Daftar Toko Gratis", "Tambah ke Keranjang", "Buat Pesanan", "Tandai Lunas".
- Status pills always use the same color vocabulary everywhere (amber=waiting, emerald=good/done, rose=bad/stopped) so a store owner learns it once and reads it everywhere in the app.

---

## 8. What to ask Stitch to output
- Mobile AND desktop frames for every Storefront + Marketing screen (5.A, 5.C).
- Desktop frames (with a quick mobile-stacked check) for every Tenant Admin screen (5.B).
- A components sheet: buttons, inputs, status pills, stat tile, surface card, sidebar nav item (active/inactive), product card, variant-row (the repeatable product-form row), empty state.
- Keep everything consistent with Section 2 so the result maps directly to Tailwind utility classes.
