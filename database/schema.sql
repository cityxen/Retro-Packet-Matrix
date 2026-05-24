-- RPM Hotspot Manager — Database Schema
-- MySQL 8.0+ / MariaDB 10.6+

CREATE DATABASE IF NOT EXISTS rpm_hotspot CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE rpm_hotspot;

-- ── Hotspot configurations ───────────────────────────────────────
CREATE TABLE IF NOT EXISTS hotspot_configs (
    id          INT UNSIGNED     NOT NULL AUTO_INCREMENT,
    ssid        VARCHAR(32)      NOT NULL DEFAULT 'RPM-Network',
    channel     TINYINT UNSIGNED NOT NULL DEFAULT 6,
    band        ENUM('2.4GHz','5GHz') NOT NULL DEFAULT '2.4GHz',
    security    ENUM('open','wpa2','wpa3') NOT NULL DEFAULT 'wpa2',
    password    VARCHAR(64)      NOT NULL DEFAULT '',
    max_clients TINYINT UNSIGNED NOT NULL DEFAULT 10,
    enabled     TINYINT(1)       NOT NULL DEFAULT 0,
    created_at  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at  TIMESTAMP        NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id)
) ENGINE=InnoDB;

-- ── Connected / known devices ────────────────────────────────────
CREATE TABLE IF NOT EXISTS devices (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    mac_address VARCHAR(17)  NOT NULL,
    ip_address  VARCHAR(15)  DEFAULT NULL,
    hostname    VARCHAR(64)  DEFAULT NULL,
    alias       VARCHAR(64)  DEFAULT NULL,
    vendor      VARCHAR(128) DEFAULT NULL,
    status      ENUM('connected','disconnected','blocked') NOT NULL DEFAULT 'disconnected',
    first_seen  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    last_seen   TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_mac (mac_address)
) ENGINE=InnoDB;

-- ── DHCP leases (mirror of dnsmasq.leases) ───────────────────────
CREATE TABLE IF NOT EXISTS dhcp_leases (
    id          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    mac_address VARCHAR(17)  NOT NULL,
    ip_address  VARCHAR(15)  NOT NULL,
    hostname    VARCHAR(64)  DEFAULT NULL,
    expires_at  TIMESTAMP    DEFAULT NULL,
    static      TINYINT(1)   NOT NULL DEFAULT 0,
    created_at  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_mac_ip (mac_address, ip_address)
) ENGINE=InnoDB;

-- ── Custom DNS entries ───────────────────────────────────────────
CREATE TABLE IF NOT EXISTS dns_entries (
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    hostname   VARCHAR(128) NOT NULL,
    ip_address VARCHAR(15)  NOT NULL,
    ttl        INT UNSIGNED NOT NULL DEFAULT 300,
    enabled    TINYINT(1)   NOT NULL DEFAULT 1,
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_hostname (hostname)
) ENGINE=InnoDB;

-- ── Activity / event log ─────────────────────────────────────────
CREATE TABLE IF NOT EXISTS activity_log (
    id         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    event_type VARCHAR(32)  NOT NULL DEFAULT 'info',
    message    TEXT         NOT NULL,
    severity   ENUM('info','warning','error') NOT NULL DEFAULT 'info',
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_created (created_at),
    KEY idx_severity (severity)
) ENGINE=InnoDB;

-- ── Key/value settings store ─────────────────────────────────────
CREATE TABLE IF NOT EXISTS settings (
    id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
    setting_key   VARCHAR(64)  NOT NULL,
    setting_value TEXT         DEFAULT NULL,
    updated_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uq_key (setting_key)
) ENGINE=InnoDB;

-- ── Default hotspot config ───────────────────────────────────────
INSERT IGNORE INTO hotspot_configs (ssid, channel, band, security, password, max_clients, enabled)
VALUES ('RPM-Network', 6, '2.4GHz', 'wpa2', '', 10, 0);

-- ── Default settings ─────────────────────────────────────────────
INSERT IGNORE INTO settings (setting_key, setting_value) VALUES
    ('interface_ap',   'wlan0'),
    ('interface_eth',  'eth0'),
    ('dhcp_range_start', '192.168.4.10'),
    ('dhcp_range_end',   '192.168.4.254'),
    ('dhcp_subnet',      '192.168.4.0'),
    ('dhcp_gateway',     '192.168.4.1'),
    ('dhcp_dns',         '8.8.8.8');
