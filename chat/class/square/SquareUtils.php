<?php
class SquareUtils {
    public static function isSquareConfigured() {
        if (!defined('SQUARE_SETTINGS')) {
            require_once(__DIR__ . '/../../includes/square_settings.php');
        }
        
        return !empty(SQUARE_SETTINGS['access_token']) && 
               !empty(SQUARE_SETTINGS['environment']) && 
               in_array(SQUARE_SETTINGS['environment'], ['sandbox', 'production']);
    }
}
