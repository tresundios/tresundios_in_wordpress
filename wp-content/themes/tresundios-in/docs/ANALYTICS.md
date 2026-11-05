# GA4 & conversion tracking (internal)

The theme does **not** load GA4 and must not. Adding it here would double-count every page view.

## 1. Where GA4 is installed today

The 2026 restructure searched the theme, the `tresundios-core` mu-plugin and `wp-content/plugins`.
**None of them contain a gtag.js / GTM snippet or a `G-` measurement ID.** So GA4 is coming from one of:

- [ ] a Custom HTML block or a theme-editor customisation saved in the database (Site Editor → Templates / Patterns, search "gtag")
- [ ] a plugin that is active on production but not in this local copy (e.g. Site Kit, Rank Math Analytics, GTM4WP)
- [ ] the hosting control panel / CDN injecting the tag
- [ ] Google Tag Manager added by someone else

Check: open the live site → View Source → search `gtag(` and `googletagmanager.com`. Write the answer here:

> GA4 location: `[fill in]` · Measurement ID: `G-[fill in]` · GTM container: `GTM-[fill in or none]`

## 2. Click and form hooks already in the markup

Every lead link and CTA carries data attributes. Tracking is connected to these attributes in GTM or with one small gtag listener. The markup does not change.

| What | Selector | Attributes |
|---|---|---|
| WhatsApp clicks | `a[data-channel="whatsapp"]` (`https://wa.me/…`) | `data-track="lead_click"`, `data-track-location` |
| Phone clicks | `a[data-channel="phone"]`, plus any `a[href^="tel:"]` (contact-details block, footer) | same |
| Email clicks | `a[data-channel="email"]`, plus any `a[href^="mailto:"]` | same |
| Audit CTA clicks | `a[data-track="cta_click"]` | `data-track-location` = `header`, `hero`, `growth-system`, `service-<key>`, `package-<key>`, `case-study` |
| Enquiry form | `form#enquiry` (`partials/enquiry-form.php`, sent by the mu-plugin over fetch) | fire on **success**, i.e. when `[data-enquiry-out]` shows without `.is-error` |

`data-track-location` values for lead buttons: `hero`, `audit`, `footer`, `inline`.

## 3. Recommended GA4 events

| Event name | Trigger | Parameters | Mark as key event? |
|---|---|---|---|
| `generate_lead` | enquiry form success | `form_id=enquiry`, `service` (form select) | Yes |
| `whatsapp_click` | WhatsApp link click | `location` | Yes |
| `phone_click` | tel: click | `location` | Yes |
| `email_click` | mailto: click | `location` | Yes |
| `audit_cta_click` | `data-track="cta_click"` | `location` | No (micro-conversion) |

**GTM route (recommended):** Click – Just Links triggers on the CSS selectors above, plus a Custom Event trigger pushed from the form success handler. That means one `dataLayer.push({event:'generate_lead'})` in the mu-plugin's form JS. Ask before editing the mu-plugin.

## 4. Verify before go-live

- [ ] Exactly one GA4 config tag fires per page (GA4 DebugView / Tag Assistant). No duplicates.
- [ ] Each event above fires once per action, on mobile and desktop.
- [ ] WhatsApp opens the app on mobile and WhatsApp Web on desktop, with the prefilled message.
- [ ] `tel:` and `mailto:` events fire even though the browser leaves the page.
- [ ] Form success fires `generate_lead`; validation errors and failures do **not**.
- [ ] Key events are marked in GA4 Admin → Events, and imported into Google Ads if ads are running.
- [ ] Internal traffic filter set for the office IP; the local/staging domain is excluded.
- [ ] The privacy policy mentions analytics, and consent mode is set if required.
