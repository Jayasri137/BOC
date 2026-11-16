<?php

function get_central_crm_pdo(): PDO
{
    static $crmPdo = null;
    if ($crmPdo instanceof PDO) {
        return $crmPdo;
    }

    $host = getenv('BLUESTONE_CRM_DB_HOST') ?: 'srv696.hstgr.io';
    $database = getenv('BLUESTONE_CRM_DB_NAME') ?: 'u287260207_bgoi_bg';
    $username = getenv('BLUESTONE_CRM_DB_USER') ?: 'u287260207_bgoi_user';
    $password = getenv('BLUESTONE_CRM_DB_PASSWORD') ?: '4g@LMW2026';

    $crmPdo = new PDO(
        "mysql:host=$host;dbname=$database;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_TIMEOUT => 4,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );

    return $crmPdo;
}