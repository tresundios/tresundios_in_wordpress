# Prompt for Claude Code — tresundios.in (India site, INR)

Copy everything below the line into Claude in VS Code, with the tresundios.in WordPress project open.

---

## Context

You are working on the source code of **tresundios.in**, the India website of Tresundios Software Products and Services Pvt. Ltd. (Chennai; CIN U62099TN2024PTC174739; DPIIT-recognised). It is a **WordPress site using a block theme (Full Site Editing)**. Prices on this site are in **INR, excluding GST**.

The global sister site is **tresundios.com** (USD). Both sites have nearly the same content, which is why we are adding regional SEO signals.

Tresundios is the **exclusive licensee of DentalPin for India** and runs **https://www.dentalpin.in**, dental practice software for Indian clinics. DentalPin is a separate brand that Tresundios endorses ("DentalPin India — a Tresundios product"). On this India site, **DentalPin is a product we sell**.

Facts about DentalPin India (use only these; do not invent features, prices, customers, or numbers):
- Dental practice management software for Indian clinics: solo practitioners, multi-chair clinics, and clinic chains
- AI assistant, GST billing, WhatsApp appointment reminders, interface in 7 Indian languages
- Open-source core (source-available under BSL 1.1), self-hostable; core advertised as "₹0 per chair or dentist"
- Live demo on dentalpin.in, a pricing page on dentalpin.in, and a "Become a partner" programme
- Paid services from Tresundios (setup, hosting, support, customisation): **use placeholders** like `[[PRICE: setup]]`, because these are not final

Brand identity (use the theme.json presets; do not hard-code new values if presets exist):
- Primary: terracotta `#C84734`
- Headings: Space Grotesk · Body: Inter · Code: JetBrains Mono

## Step 0 — Inspect before changing anything

Do not edit files until you have reported back on:
1. The theme folder (`wp-content/themes/<theme>/`): `theme.json`, `templates/`, `parts/` (header, footer), `patterns/`, `functions.php`, `style.css`, and any `inc/` folder.
2. **How the header navigation is built.** Is it hard-coded `core/navigation-link` blocks in `parts/header.html`, or a `core/navigation` block with a `ref` to a `wp_navigation` post stored in the database? If it's a database ref, tell me and give me both options: (a) a WP-CLI / admin step to add the menu item, or (b) converting the header to inline links in the template part.
3. Whether an SEO plugin (Yoast, Rank Math, SEOPress, AIOSEO) is installed or active, and whether it already outputs canonical, hreflang, or JSON-LD schema. **Do not output duplicate canonical, hreflang, or schema tags.**
4. Whether `wp-content/mu-plugins/` exists.
5. How the homepage and pages are built: `front-page.html` / `home.html` templates, patterns, or content stored in the database.
6. The current service sections. The live site shows 4 services (Digital Marketing, Graphic Design, Event Management, Websites & Apps), while tresundios.com shows 6 (it also has **DevOps & Cloud** and **Product Photography**).

Then give me a short plan listing every file you'll create or change, and wait for my OK.

## Tasks

### 1. "Products" in the main navigation
- Add a top-level **Products** item to the header (desktop and mobile) and to the footer "Company" column.
- It links to `/products/`. Use a dropdown or submenu only if the existing navigation already uses them; otherwise one link is enough.
- Leave room for future products (HealtheKit will be added later).

### 2. `/products/` overview page
- Create a block pattern `patterns/products-overview.php` (category: `tresundios`) and, if the theme uses them, a page template `templates/page-products.html`.
- Content: an intro ("Software products built and run by Tresundios"), then one **product card for DentalPin India** with its logo placeholder, a one-line description, 3 key benefits, a **Try the live demo** button (→ https://www.dentalpin.in, opens in the same tab), and a **Learn more** link (→ `/products/dentalpin/`).
- Build the card as a reusable pattern (`patterns/product-card.php`) so later products can use the same layout.

### 3. `/products/dentalpin/` product page (this is the sales page)
Make it a pattern plus a template, like the overview page. Sections, in this order:
1. **Hero:** H1 "DentalPin India — dental clinic software made for Indian practices", a subline, primary CTA **Try the live demo** (dentalpin.in), secondary CTA **Book a call** (reuse the site's existing booking link), and the endorsement line "Exclusive India licensee: Tresundios Software Products and Services Pvt. Ltd."
2. **Who it's for:** three columns: Solo dentist / Multi-chair clinic / Clinic chain.
3. **Features:** AI assistant, GST billing, WhatsApp reminders, 7 Indian languages, self-hosted or managed hosting, open-source core.
4. **What's free vs. what Tresundios provides:** a table. Free: the core software. Paid from Tresundios: installation and setup, managed hosting (India region), data migration, training, support plans, customisation. Prices in INR **as placeholders** `[[PRICE: …]]` + "excl. GST", matching how the existing pricing section is styled.
5. **Why Tresundios:** a short paragraph about our in-house engineering, DevOps, and India-region hosting. Link to the Websites & Apps and DevOps & Cloud service sections.
6. **FAQ:** use `core/details` blocks. Include: Is it really free? Where is my data hosted? Can you migrate from my current software? Do you offer training? Is it available outside India? (Answer: Tresundios's licence covers India.)
7. **Final CTA band:** demo + book a call + "Become a partner" (→ the dentalpin.in partner page; find the exact URL or leave `[[URL: partner]]`).

Copy rules: plain, confident English for Indian dentists; no superlatives you can't back up; no made-up testimonials, client counts, or statistics. Wherever proof would help, add an HTML comment `<!-- TODO: add real testimonial -->` instead of inventing one.

### 4. Homepage teaser
- On the homepage, add a compact **"Our product: DentalPin India"** band between the services section and the work/case-study section (or wherever fits best; suggest a spot). It has one line, two CTAs (demo / learn more), and a terracotta accent.
- Build it as a pattern (`patterns/home-product-teaser.php`) and insert it into the front-page template.

### 5. Service parity with tresundios.com
- Add the missing **DevOps & Cloud** (cloud servers, CI/CD pipelines, containers, monitoring) and **Product Photography** (product, lifestyle, on-location shoots) service blocks, matching the style of the existing service cards.
- Add them to the footer "Services" column.
- Add INR pricing rows for them if the pricing section lists services, using `[[PRICE: …]]` placeholders.

### 6. Hreflang + canonical (only if no SEO plugin already handles it)
- Create a must-use plugin `wp-content/mu-plugins/tresundios-hreflang.php` that hooks `wp_head` and outputs:
  - `<link rel="alternate" hreflang="en-IN" href="https://www.tresundios.in{path}">`
  - `<link rel="alternate" hreflang="en" href="https://www.tresundios.com{path}">`
  - `<link rel="alternate" hreflang="x-default" href="https://www.tresundios.com{path}">`
- Use a **filterable path map** (`apply_filters( 'tresundios_hreflang_map', [...] )`) so pages whose slugs differ between the two sites map correctly. Default to the same path on both sites.
- **Exclude** `/products/dentalpin/` from the alternate pair, or map it to `/work/dentalpin-india/` on .com. Those pages differ on purpose, so tell me which you chose and why.
- Make sure every page has a self-referencing canonical on `https://www.tresundios.in` (www, https, trailing slash consistent with the current permalinks).
- If an SEO plugin is active, don't write code. Give me the exact plugin settings to change instead.
- Use the same plugin file on both sites, with the site's own locale detected from `home_url()`, so I can copy it to tresundios.com unchanged.

### 7. Structured data (JSON-LD)
In the same mu-plugin (or the SEO plugin's schema settings if one is active):
- Sitewide **Organization** schema: name, legalName, url, logo, address (4/5 (Old 9/5), School VII Street, Jafferkhanpet, Chennai 600083, Tamil Nadu, IN), email navis@tresundios.in, `sameAs` → tresundios.com, `brand` → `{ "@type": "Brand", "name": "DentalPin India", "url": "https://www.dentalpin.in" }`.
- On `/products/dentalpin/`, add **SoftwareApplication** schema: name DentalPin India, applicationCategory `BusinessApplication`, operatingSystem `Web`, `offers` with price `0` and priceCurrency `INR` for the core, publisher = the Tresundios Organization (`@id` reference), url https://www.dentalpin.in.
- On the FAQ section, add **FAQPage** schema generated from the same questions (one source of truth, no copy-paste drift).
- Validate the JSON (`json_encode` with `JSON_UNESCAPED_SLASHES`) and output it only once per page.

### 8. Links to dentalpin.in
- Use normal followed links (no `nofollow`). Use descriptive anchor text such as "DentalPin India dental clinic software" in at least one in-content link. Don't use "click here".
- In the footer, add one "Our products → DentalPin India" link. No more sitewide links than that.

## Constraints
- Use theme.json colour, font, and spacing presets (`var(--wp--preset--…)`). Add new presets to theme.json only if you have to, and tell me.
- All new blocks must be valid core block markup that opens in the Site Editor with no "This block contains unexpected or invalid content" warnings.
- Make it responsive (check at 375px, 768px, and 1280px) and accessible (one H1 per page, logical heading order, alt text, AA contrast for terracotta on white, focus states on buttons).
- Use `esc_html__()` / `esc_url()` in PHP patterns, with the theme's text domain.
- No new third-party plugins unless I approve.
- Don't change prices that already exist.

## Deliverables
1. The plan from Step 0 (wait for approval).
2. The code changes.
3. A **"Do in WP Admin"** checklist for anything that lives in the database (creating the `/products/` and `/products/dentalpin/` pages and assigning templates, menu items, permalinks), with WP-CLI equivalents, e.g. `wp post create --post_type=page --post_title="Products" --post_name=products --post_status=draft`.
4. A test checklist: View Source to check for exactly one canonical, three hreflang tags, and valid JSON-LD (paste into https://validator.schema.org), Site Editor shows no invalid blocks, mobile menu works, all CTAs go to the right URLs.
5. A list of every `[[PLACEHOLDER]]` and `TODO` left for me to fill.
