<?php

if (!function_exists('normalizePhoneNumber')) {
    /**
     * Normalize Indonesian phone number to consistent format
     * Converts +62 or 08 to 62 format and removes special characters
     */
    function normalizePhoneNumber(string $phone): string
    {
        // Remove spaces, dashes, parentheses, and other special characters
        $phone = preg_replace('/[\s\-\(\)]+/', '', $phone);

        // Remove + sign if present
        $phone = ltrim($phone, '+');

        // Convert 08... to 62...
        if (str_starts_with($phone, '08')) {
            $phone = '62' . substr($phone, 1);
        }

        return $phone;
    }
}
