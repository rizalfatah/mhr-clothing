<?php

describe('normalizePhoneNumber Helper', function () {
    test('normalizes phone number with +62 prefix', function () {
        $result = normalizePhoneNumber('+6281234567890');
        expect($result)->toBe('6281234567890');
    });

    test('normalizes phone number with 08 prefix', function () {
        $result = normalizePhoneNumber('081234567890');
        expect($result)->toBe('6281234567890');
    });

    test('removes spaces from phone number', function () {
        $result = normalizePhoneNumber('+62 812 3456 7890');
        expect($result)->toBe('6281234567890');
    });

    test('removes dashes from phone number', function () {
        $result = normalizePhoneNumber('+62-812-3456-7890');
        expect($result)->toBe('6281234567890');
    });

    test('removes parentheses from phone number', function () {
        $result = normalizePhoneNumber('+62 (812) 3456-7890');
        expect($result)->toBe('6281234567890');
    });

    test('handles phone number with mixed special characters', function () {
        $result = normalizePhoneNumber('+62 (812) 345-67 890');
        expect($result)->toBe('6281234567890');
    });

    test('handles phone number already in 62 format', function () {
        $result = normalizePhoneNumber('6281234567890');
        expect($result)->toBe('6281234567890');
    });

    test('handles phone number with 08 and spaces', function () {
        $result = normalizePhoneNumber('0812 3456 7890');
        expect($result)->toBe('6281234567890');
    });

    test('handles phone number with +62 without spaces', function () {
        $result = normalizePhoneNumber('+6281234567890');
        expect($result)->toBe('6281234567890');
    });

    test('normalizes different valid Indonesian number formats', function () {
        $formats = [
            '081234567890' => '6281234567890',
            '+6281234567890' => '6281234567890',
            '0812-3456-7890' => '6281234567890',
            '+62 812 3456 7890' => '6281234567890',
            '6281234567890' => '6281234567890',
        ];

        foreach ($formats as $input => $expected) {
            expect(normalizePhoneNumber($input))->toBe($expected);
        }
    });
});
