<?php
require_once(__DIR__ . '/../vendor/autoload.php');
require_once(__DIR__ . '/connection.php');
require_once(__DIR__ . '/../class/square/ConfigManager.php');

// Initialize ConfigManager and tables
$configManager = new ConfigManager($connection);
$initResult = $configManager->initializeTables();

if (!$initResult['success']) {
    error_log("Failed to initialize Square tables: " . $initResult['message']);
}

// Get settings
$settingsResult = $configManager->getSettings();
$settings = $settingsResult['success'] ? $settingsResult['settings'] : [];

// Make settings available globally
define('SQUARE_SETTINGS', $settings);

// Function to check if Square is properly configured
function isSquareConfigured() {
    $requiredSettings = ['access_token', 'environment'];
    foreach ($requiredSettings as $setting) {
        if (!isset(SQUARE_SETTINGS[$setting]) || empty(SQUARE_SETTINGS[$setting])) {
            return false;
        }
    }
    return true;
}
