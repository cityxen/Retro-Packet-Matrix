<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>404 // RPM</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=VT323&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/retro.css">
</head>
<body>
<div class="scanlines"></div>
<div class="error-screen">
  <pre class="error-art">
  +--------------------------+
  |  ERROR: 404 NOT FOUND    |
  |  PACKET LOST IN TRANSIT  |
  +--------------------------+
  </pre>
  <p>REQUESTED PATH: <span class="mono"><?= htmlspecialchars($uri ?? '/') ?></span></p>
  <a href="/" class="btn btn-primary">[ RETURN TO BASE ]</a>
</div>
</body>
</html>
