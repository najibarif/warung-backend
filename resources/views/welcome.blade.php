<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #e2e8f0;
        }
        .container {
            text-align: center;
            padding: 2rem;
        }
        .logo {
            width: 80px;
            height: 80px;
            background: #ff2d20;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            font-size: 2rem;
            font-weight: 900;
            color: #fff;
            box-shadow: 0 20px 60px rgba(255, 45, 32, 0.3);
        }
        h1 {
            font-size: 2.5rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
            background: linear-gradient(to right, #e2e8f0, #94a3b8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .version {
            font-size: 1rem;
            color: #64748b;
            margin-bottom: 2rem;
        }
        .version span {
            color: #38bdf8;
            font-weight: 600;
        }
        .subtitle {
            color: #94a3b8;
            font-size: 1.1rem;
            margin-bottom: 0.5rem;
        }
        .links {
            margin-top: 2.5rem;
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        .links a {
            color: #94a3b8;
            text-decoration: none;
            padding: 0.75rem 1.5rem;
            border: 1px solid #334155;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 500;
            transition: all 0.2s;
        }
        .links a:hover {
            border-color: #ff2d20;
            color: #fff;
            background: rgba(255, 45, 32, 0.1);
        }
        .php-version {
            margin-top: 3rem;
            font-size: 0.8rem;
            color: #475569;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo">L</div>
        <h1>Laravel</h1>
        <div class="version">
            v{{ Illuminate\Foundation\Application::VERSION }} · <span>PHP {{ PHP_VERSION }}</span>
        </div>
        <p class="subtitle">{{ config('app.name') }}</p>
        <p class="subtitle" style="font-size:0.9rem;color:#64748b;">AplikasiWarung API Server</p>

        <div class="links">
            <a href="/api/ping">API Ping</a>
            <a href="/api/products">Products</a>
            <a href="/api/categories">Categories</a>
        </div>

        <div class="php-version">
            Server is running · {{ app()->environment() }} mode
        </div>
    </div>
</body>
</html>
