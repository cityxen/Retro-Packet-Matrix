<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($pageTitle ?? 'RPM') ?> // RPM Hotspot Manager</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=VT323&family=Share+Tech+Mono&display=swap" rel="stylesheet">
<link rel="stylesheet" href="/assets/css/retro.css">
</head>
<body>

<div class="scanlines"></div>

<div class="app-shell">

  <!-- Sidebar Nav -->
  <nav class="sidebar">
    <div class="sidebar-logo">
      <span class="logo-bracket">[</span>
      <span class="logo-text">RPM</span>
      <span class="logo-bracket">]</span>
      <div class="logo-sub">RETRO PACKET MATRIX</div>
    </div>

    <ul class="nav-menu">
      <li class="nav-item <?= (!isset($pageTitle) || $pageTitle === 'Command Center') ? 'active' : '' ?>">
        <a href="/" class="nav-link">
          <span class="nav-icon">&#9632;</span>
          <span class="nav-label">COMMAND CENTER</span>
        </a>
      </li>
      <li class="nav-item <?= ($pageTitle ?? '') === 'Hotspot Control' ? 'active' : '' ?>">
        <a href="/hotspot" class="nav-link">
          <span class="nav-icon">&#9670;</span>
          <span class="nav-label">HOTSPOT CTRL</span>
        </a>
      </li>
      <li class="nav-item <?= ($pageTitle ?? '') === 'Connected Devices' ? 'active' : '' ?>">
        <a href="/devices" class="nav-link">
          <span class="nav-icon">&#9675;</span>
          <span class="nav-label">DEVICES</span>
        </a>
      </li>
      <li class="nav-item <?= ($pageTitle ?? '') === 'DHCP / DNS' ? 'active' : '' ?>">
        <a href="/dhcp" class="nav-link">
          <span class="nav-icon">&#9654;</span>
          <span class="nav-label">DHCP / DNS</span>
        </a>
      </li>
      <li class="nav-item <?= ($pageTitle ?? '') === 'Diagnostics' ? 'active' : '' ?>">
        <a href="/diagnostics" class="nav-link">
          <span class="nav-icon">&#9650;</span>
          <span class="nav-label">DIAGNOSTICS</span>
        </a>
      </li>
    </ul>

    <div class="sidebar-footer">
      <div class="sys-clock" id="sysClock">--:--:--</div>
      <div class="version">v<?= APP_VERSION ?></div>
    </div>
  </nav>

  <!-- Main Content -->
  <main class="main-content">
    <header class="top-bar">
      <div class="top-bar-left">
        <span class="top-bar-prompt">root@rpm:~#</span>
        <span class="top-bar-title"><?= htmlspecialchars($pageTitle ?? 'RPM') ?></span>
        <span class="cursor-blink">_</span>
      </div>
      <div class="top-bar-right">
        <span class="status-pill" id="hotspotStatus">AP: CHECKING...</span>
      </div>
    </header>

    <div class="content-area">
