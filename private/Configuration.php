<?php
use Old\Milantex\Core\ConfigurationInterface;

final class ApplicationConfiguration implements ConfigurationInterface {
    public static function getDatabaseHostname(): string {
        return 'localhost';
    }

    public static function getDatabaseUsername(): string {
        return 'root';
    }

    public static function getDatabasePassword(): string {
        return '';
    }

    public static function getDatabaseName(): string {
        return 'old_milantex_app_database';
    }

    public static function getWebsiteBaseUrl(): string {
        return 'http://localhost/Old-Milantex-Application-Template/public/';
    }

    public static function getTemplatesFilesystemPath(): string {
        return '../private/application/templates/';
    }
}
