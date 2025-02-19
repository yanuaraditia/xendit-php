<?php

/**
 * Xendit.php
 * php version 7.2.0
 *
 * @category Class
 * @package  Xendit
 * @author   Ellen <ellen@xendit.co>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://api.xendit.co
 */

namespace Xendit;

use Dotenv\Dotenv;
use Xendit\HttpClient\GuzzleClient;

/**
 * Class Xendit
 *
 * @category Class
 * @package  Xendit
 * @author   Ellen <ellen@xendit.co>
 * @license  https://opensource.org/licenses/MIT MIT License
 * @link     https://api.xendit.co
 */
class Xendit
{
    public static $apiKey;

    public static $apiBase = 'https://api.xendit.co';

    public static $libVersion;

    private static $_httpClient;

    const VERSION = "2.18.0";

    public function __construct() {
    }

    /**
     * ApiBase getter
     *
     * @return string
     */
    public static function getApiBase(): string
    {
        return self::$apiBase;
    }

    /**
     * ApiBase setter
     *
     * @param string $apiBase api base url
     *
     * @return void
     */
    public static function setApiBase(string $apiBase): void
    {
        self::$apiBase = $apiBase;
    }

    /**
     * Get the value of apiKey
     *
     * @return string Secret API key
     */
    public static function getApiKey(): string
    {
        return self::$apiKey;
    }

    /**
     * Set the value of apiKey
     *
     * @param string $apiKey Secret API key
     *
     * @return void
     */
    public static function setApiKey(string $apiKey)
    {
        self::$apiKey = $apiKey;
    }

    /**
     * Get library version
     *
     * @return mixed
     */
    public static function getLibVersion()
    {
        if (self::$libVersion === null) {
            self::$libVersion = self::VERSION;
        }
        return self::$libVersion;
    }

    /**
     * Set library version
     *
     * @param string|null $libVersion library version
     *
     * @return void
     */
    public static function setLibVersion(string $libVersion = null): void
    {
        self::$libVersion = $libVersion;
    }

    /**
     * Set custom http client
     *
     * @param HttpCLientInterface $client custom http client
     *
     * @return void
     */
    public static function setHttpClient($client): void
    {
        self::$_httpClient = $client;
    }

    /**
     * Get current http client being used in the package
     *
     * @return HttpClientInterface
     */
    public static function getHttpClient()
    {
        return self::$_httpClient;
    }
}
