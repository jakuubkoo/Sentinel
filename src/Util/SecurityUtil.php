<?php

namespace App\Util;

/**
 * Class SecurityUtil
 *
 * Utility class for security-related operations.
 *
 * @package App\Util
 */
class SecurityUtil
{
    /**
     * Escape special characters in a string to prevent HTML injection.
     *
     * @param string $string The input string to escape.
     *
     * @return string|null The escaped string or null on error.
     */
    public function escapeString(string $string): ?string
    {
        return htmlspecialchars($string, ENT_QUOTES | ENT_HTML5);
    }

    /**
     * Generate hash for a given password.
     *
     * @param string $password The password to hash.
     *
     * @return string The hashed password.
     */
    public function generateHash(string $password): string
    {
        $options = [
            'memory_cost' => 1 << 16, // 64 MB
            'time_cost' => 6,         // 6 iterations
            'threads' => 2            // 2 threads
        ];

        // generate hash
        return password_hash($password, PASSWORD_ARGON2ID, $options);
    }

    /**
     * Verify a password against a given Argon2 hash.
     *
     * @param string $password The password to verify.
     * @param string $hash The hash to verify against.
     *
     * @return bool True if the password is valid, false otherwise.
     */
    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }
}
