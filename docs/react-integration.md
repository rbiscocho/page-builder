# React frontend integration

The Laravel API serves **published** Tailwind HTML only. The React app must:

1. Import the generated safelist so Tailwind does not purge classes that only appear in runtime HTML.
2. Render with `dangerouslySetInnerHTML` inside a wrapper.
3. Sanitize with DOMPurify (defense in depth — the server never stores user-submitted HTML).

## 1. Generate and import the safelist

On the Laravel side:

```bash
php artisan page-builder:tailwind-safelist
# writes resources/css/page-builder-safelist.css (or path from config)
```

Copy that file into the React repo (or fetch `GET /api/page-builder/safelist.css`).

In your React Tailwind **entry CSS** (the file Vite/PostCSS processes):

```css
@import "tailwindcss";
@import "./page-builder-safelist.css"; /* @source inline(...) from the artisan command */
```

Tailwind v4 will keep every class listed in the `@source inline(...)` directives.

## 2. `usePage` hook

```tsx
// hooks/usePage.ts
import { useEffect, useState } from "react";

export type PagePayload = {
  slug: string;
  title: string;
  html: string;
  updated_at: string | null;
  exporter_version: string | null;
};

export function usePage(slug: string, apiBase = import.meta.env.VITE_API_URL) {
  const [data, setData] = useState<PagePayload | null>(null);
  const [error, setError] = useState<string | null>(null);
  const [loading, setLoading] = useState(true);

  useEffect(() => {
    let cancelled = false;
    setLoading(true);
    fetch(`${apiBase}/api/pages/${encodeURIComponent(slug)}`, {
      headers: { Accept: "application/json" },
    })
      .then(async (res) => {
        if (res.status === 404) throw new Error("Page not found");
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        return res.json() as Promise<PagePayload>;
      })
      .then((json) => {
        if (!cancelled) {
          setData(json);
          setError(null);
        }
      })
      .catch((e) => {
        if (!cancelled) {
          setError(e.message);
          setData(null);
        }
      })
      .finally(() => {
        if (!cancelled) setLoading(false);
      });
    return () => {
      cancelled = true;
    };
  }, [slug, apiBase]);

  return { data, error, loading };
}
```

## 3. Page component with DOMPurify

```tsx
// components/CmsPage.tsx
import DOMPurify from "dompurify";
import { usePage } from "../hooks/usePage";
import { useNavigate } from "react-router-dom"; // or your router

const ALLOWED_TAGS = [
  "section", "div", "h1", "h2", "h3", "h4", "h5", "h6",
  "p", "br", "a", "img", "hr", "iframe", "span",
];
const ALLOWED_ATTR = [
  "class", "href", "src", "alt", "loading", "title",
  "frameborder", "allow", "allowfullscreen", "aria-hidden",
  "data-link-type",
];

export function CmsPage({ slug }: { slug: string }) {
  const { data, error, loading } = usePage(slug);
  const navigate = useNavigate();

  if (loading) return <div>Loading…</div>;
  if (error || !data) return <div>{error ?? "Not found"}</div>;

  const clean = DOMPurify.sanitize(data.html, {
    ALLOWED_TAGS,
    ALLOWED_ATTR,
    ADD_ATTR: ["target"], // optional
  });

  return (
    <div
      className="cms-page"
      dangerouslySetInnerHTML={{ __html: clean }}
      onClick={(e) => {
        const a = (e.target as HTMLElement).closest("a[data-link-type=internal]");
        if (a) {
          e.preventDefault();
          const href = a.getAttribute("href");
          if (href) navigate(href);
        }
      }}
    />
  );
}
```

Install: `npm i dompurify` and `@types/dompurify` if using TypeScript.

## 4. CORS

Set in Laravel `.env`:

```
PAGE_BUILDER_CORS_ORIGIN=https://your-react-app.example
```

Register CORS for that origin in `config/cors.php` (or middleware) so the React origin can call `/api/pages/*`.

## 5. Absolute image URLs

The exporter already turns relative paths into absolute URLs using `asset()` / `APP_URL`. Ensure `APP_URL` is the public Laravel origin the React app can reach.

## 6. Interaction notes

- Buttons and links are plain `<a>` tags.
- Internal links carry `data-link-type="internal"` so the React router can intercept them (see `onClick` above).
- External links leave the SPA as normal.

## 7. Security

- The server **only** stores HTML it generated itself from the design JSON. Never accept raw HTML from the client.
- DOMPurify is still required on the React side as defense in depth.
- Embed iframes are restricted to an allow-list of hosts (YouTube, Vimeo, Google Maps) on the server.
