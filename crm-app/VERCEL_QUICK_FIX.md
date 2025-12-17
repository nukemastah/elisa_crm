# Quick Fix Checklist - Vercel 404 NOT_FOUND

## Immediate Actions

### 1. Check vercel.json Structure
```json
{
  "functions": {              // ✅ Must be "functions"
    "api/index.php": {        // ✅ Not "builds"
      "runtime": "vercel-php@0.6.0"
    }
  }
}
```

### 2. Verify api/index.php Sets Paths
```php
$_SERVER['DOCUMENT_ROOT'] = __DIR__ . '/../public';
define('LARAVEL_PUBLIC_PATH', __DIR__ . '/../public');
require __DIR__ . '/../public/index.php';
```

### 3. Test Static Asset Routes
```json
{
  "src": "/(.*\\.(css|js|png|jpg|...))",  // ✅ Regex with double backslash
  "dest": "public/$1"                     // ✅ Points to public folder
}
```

## Deployment Test Commands

```bash
# 1. Verify files exist
ls -la api/index.php vercel.json

# 2. Test locally first (if possible)
vercel dev

# 3. Deploy to preview
vercel

# 4. Check logs
vercel logs [url]

# 5. Deploy to production
vercel --prod
```

## Common Gotchas

| Issue | Cause | Fix |
|-------|-------|-----|
| 404 on all routes | `builds` instead of `functions` | Change to `functions` |
| CSS/JS not loading | Static route missing | Add file extension regex |
| Laravel path errors | No document root set | Set in `api/index.php` |
| Database connection | Using localhost | Use cloud database URL |
| Session lost | Using `file` driver | Use `cookie` driver |

## Environment Variables Checklist

Required in Vercel Dashboard:
- [ ] `APP_KEY` (from `php artisan key:generate --show`)
- [ ] `APP_URL` (your Vercel URL)
- [ ] `DB_CONNECTION=pgsql`
- [ ] `DB_HOST` (cloud database host)
- [ ] `DB_DATABASE` (database name)
- [ ] `DB_USERNAME` (database user)
- [ ] `DB_PASSWORD` (database password)
- [ ] `SESSION_DRIVER=cookie`
- [ ] `LOG_CHANNEL=stderr`

## If Still Not Working

1. **Check Vercel Build Logs**
   ```bash
   vercel logs --follow
   ```

2. **Verify PHP Runtime**
   - Supported: `vercel-php@0.6.0`
   - Not: `vercel-php@0.7.0` (doesn't exist)

3. **Test Route Priority**
   - Static routes BEFORE catch-all
   - Catch-all `"src": "/(.*)"` goes LAST

4. **Alternative: Use Railway Instead**
   ```bash
   # Railway is simpler for Laravel
   railway init
   railway up
   ```
   No serverless complexity!

## Success Indicators

✅ Deployment shows "Ready"
✅ Opening URL shows your app (not Vercel 404 page)
✅ CSS/JS loads correctly
✅ Database connections work
✅ Session persists across requests

## Remember

Vercel is optimized for **Next.js**, not Laravel.

For Laravel, consider:
- **Railway.app** (easiest)
- **Fly.io** (powerful)
- **DigitalOcean App Platform** (traditional)
- **Laravel Forge + DigitalOcean** (production-ready)

Serverless PHP has limitations:
- 10s timeout
- No file storage
- Cold starts
- Complex configuration

Choose the right tool for your needs!
