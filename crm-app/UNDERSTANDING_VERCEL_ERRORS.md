# Understanding Vercel NOT_FOUND Error for Laravel

## 🔧 What Was Fixed

### Problem Summary
The `NOT_FOUND` (404) error occurred because Vercel couldn't find the serverless function to handle requests. Three main issues were present:

1. **Wrong Configuration Schema**: Used `builds` instead of `functions`
2. **Incorrect Static Asset Routing**: Routes didn't properly handle static files
3. **Missing Document Root**: Laravel couldn't find its public path in serverless environment

### The Fixes Applied

#### 1. Changed from `builds` to `functions` in vercel.json
```json
// ❌ OLD (WRONG):
"builds": [
  {
    "src": "api/index.php",
    "use": "vercel-php@0.7.0"  // This builder doesn't exist properly
  }
]

// ✅ NEW (CORRECT):
"functions": {
  "api/index.php": {
    "runtime": "vercel-php@0.6.0"  // Stable runtime version
  }
}
```

#### 2. Fixed Static Asset Routing
```json
// ❌ OLD: Only matched specific folders
"src": "/(css|js|images)/(.*)"  // Misses files directly in public/

// ✅ NEW: Matches any static file by extension
"src": "/(.*\\.(css|js|png|jpg|jpeg|gif|ico|svg|woff|woff2|ttf|eot))"
```

#### 3. Set Document Root in api/index.php
```php
// ❌ OLD: Just forwarded request
require __DIR__ . '/../public/index.php';

// ✅ NEW: Sets proper paths for Laravel
$_SERVER['DOCUMENT_ROOT'] = __DIR__ . '/../public';
define('LARAVEL_PUBLIC_PATH', __DIR__ . '/../public');
require __DIR__ . '/../public/index.php';
```

---

## 📚 Root Cause Analysis

### What Was the Code Actually Doing?

**The Misconception:**
```
You thought: Vercel would automatically understand Laravel's structure
Reality: Vercel needs explicit configuration for serverless PHP
```

**What Happened:**
1. Vercel received request → looked for serverless function
2. `builds` config tried to use deprecated builder pattern
3. Function wasn't properly registered in Vercel's routing table
4. Result: 404 NOT_FOUND because no function existed to handle the route

### Why This Specific Error?

The error chain:
```
Request arrives → Vercel checks routes → Finds "/(.*)" → api/index.php
                                                              ↓
                                          Is this function registered?
                                                              ↓
                                          No! (because 'builds' is wrong)
                                                              ↓
                                               404 NOT_FOUND Error
```

### The Oversight

**Two paradigm shifts you missed:**

1. **Build-time vs Runtime**: 
   - Traditional hosting: PHP runs as a persistent process
   - Vercel serverless: Each request spins up a new function instance

2. **File system vs Functions**:
   - You thought: "Just point to the file" (like Apache/Nginx)
   - Vercel needs: "Register this file as a serverless function"

---

## 🎓 Core Concepts to Understand

### 1. Serverless Functions vs Traditional Hosting

**Traditional Hosting (Apache/Nginx):**
```
Web Server (always running)
    ↓
index.php (always ready)
    ↓
Your app
```

**Serverless (Vercel):**
```
Request arrives
    ↓
Cold start: Spin up function container
    ↓
Execute code
    ↓
Return response
    ↓
Container destroyed (or cached for ~5 min)
```

**Key Difference**: No persistent state between requests!

### 2. Why Does This Error Exist?

**Protection Purposes:**
1. **Resource Management**: Prevents infinite loops of undefined routes consuming resources
2. **Security**: Explicit function registration prevents accidental exposure of internal files
3. **Performance**: Forces you to define routes upfront for faster resolution

### 3. Mental Model for Vercel + PHP

Think of it like this:
```
vercel.json = "Function Registry" + "Router"
    ↓
    Tells Vercel: "These PHP files are functions, not just files"
    ↓
    Routes traffic to the right function
```

**NOT like Apache:**
```apache
# Apache: Any .php file in DocumentRoot is executable
<Directory /var/www/html>
    # All PHP files auto-handled
</Directory>
```

**Vercel requires explicit registration:**
```json
{
  "functions": {
    "api/index.php": { ... }  // Only THIS file is a function
  }
}
```

### 4. Static Assets in Serverless

**Why separate routing for static files?**

```
Request for /style.css
    ↓
Option 1: Route to PHP function (SLOW, wasteful)
    ↓
    Cold start + PHP execution for a static file!
    
Option 2: Direct file serve (FAST)
    ↓
    Vercel CDN serves directly, no function invocation
```

The regex `/(.*\\.(css|js|png|...)` ensures static files skip PHP entirely.

---

## 🚨 Warning Signs & Prevention

### Red Flags That Indicate This Issue

1. **Deployment succeeds but all routes return 404**
   - Sign: Build passes but nothing works
   - Cause: Functions not registered

2. **Static assets (CSS/JS) don't load**
   - Sign: HTML loads but unstyled
   - Cause: Static routes missing or incorrect

3. **Logs show "Function not found"**
   - Direct indicator: Check `vercel.json` config

4. **Works locally but fails on Vercel**
   - Sign: Environment difference
   - Cause: Local PHP-FPM vs serverless function model

### Similar Mistakes to Watch For

**Scenario 1: Multiple Entry Points**
```json
// ❌ WRONG: Trying to register multiple Laravel apps
"functions": {
  "api/index.php": {...},
  "public/index.php": {...}  // Conflict!
}
```

**Scenario 2: Missing Public Files**
```
Your app uses: <link href="/css/app.css">
But you have: public/build/css/app.css
Result: 404 for assets
```

**Scenario 3: Database URLs in .env**
```bash
# ❌ Local database won't work
DB_HOST=localhost

# ✅ Must use cloud database
DB_HOST=your-db.provider.com
```

### Prevention Checklist

Before deploying to Vercel:

- [ ] Check `vercel.json` uses `functions` not `builds`
- [ ] Verify runtime version is supported (`vercel-php@0.6.0`)
- [ ] Test all static asset paths match routing patterns
- [ ] Ensure `.env` variables are cloud-ready
- [ ] Confirm `api/index.php` sets document root
- [ ] Validate routes catch-all comes LAST in `routes` array

---

## 🔀 Alternative Approaches & Trade-offs

### Approach 1: Current Fix (Functions + Routing)
**Pros:**
- Full control over routing
- Can optimize static asset delivery
- Works with Laravel's structure

**Cons:**
- Complex configuration
- Need to understand serverless model
- Cold start latency on first request

### Approach 2: Vercel Native PHP (if it existed)
**Hypothetical:**
```json
{
  "framework": "laravel",
  "autoDetect": true
}
```
**Why it doesn't exist:** PHP frameworks vary too much; no one-size-fits-all.

### Approach 3: Containerized Deployment (Better Alternative!)

Instead of Vercel, use platforms built for Laravel:

**Railway.app:**
```toml
[build]
builder = "NIXPACKS"

[deploy]
startCommand = "php artisan serve --host=0.0.0.0 --port=$PORT"
```
- Native PHP support
- Traditional hosting model
- Easier database setup

**Pros over Vercel:**
- No serverless complexity
- Persistent processes
- Standard Laravel behavior
- File storage works

**Cons vs Vercel:**
- No global CDN by default
- Might be slower for static sites
- Less auto-scaling

### Approach 4: Docker + Fly.io/Render
Package Laravel in Docker:
```dockerfile
FROM php:8.2-apache
# Traditional hosting in a container
```

**Best for:** Full Laravel features without serverless limitations

---

## 🎯 Key Takeaways

1. **Serverless ≠ Traditional Hosting**: Each request is isolated, no persistent state
2. **Explicit Registration Required**: Vercel needs to know what's a function
3. **Static Assets Need Separate Routes**: Don't waste function invocations on CSS/JS
4. **Document Root Matters**: Serverless PHP doesn't assume Laravel's structure
5. **Consider Alternatives**: Vercel is great for Next.js, but Laravel might be happier elsewhere

### When to Use Vercel for Laravel:
✅ Simple APIs
✅ Read-heavy apps
✅ Stateless operations

### When NOT to Use Vercel:
❌ File uploads (no persistent storage)
❌ Long-running tasks (10s timeout)
❌ Complex database migrations
❌ Session-heavy apps

---

## 🔗 Further Reading

- [Vercel Serverless Functions Docs](https://vercel.com/docs/functions/serverless-functions)
- [Laravel Serverless Best Practices](https://laravel.com/docs/deployment#server-requirements)
- [Why Railway/Fly.io might be better for Laravel](https://railway.app/templates?q=laravel)

**Remember**: The right tool for the right job. Vercel excels at JAMstack, but Laravel might prefer traditional hosts or Railway/Fly.io for simpler deployment.
