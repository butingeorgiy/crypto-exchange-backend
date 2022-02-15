<?php

use Illuminate\Support\Str;

function hidden_string(string $needle, int $start = 3, int $end = 5): ?string {
    if (iconv_strlen($needle) < $start + $end) {
        return null;
    }

    $startWith = Str::limit($needle, $start, '');
    $endWith = Str::limit(strrev($needle), $end, '');

    return $startWith . '...' . strrev($endWith);
}
