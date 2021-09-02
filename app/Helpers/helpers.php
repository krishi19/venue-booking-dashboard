<?php

use Illuminate\Support\Facades\Storage;

function image_url($image,$path)
{
    return $image ?  env('APP_URL') . Storage::url($path  . $image) : asset('img/no-image.png');

}

function format_number($number, $decimals = null, $dec_point = null, $thousands_sep = null)
{
    if ($number) {
        $number = preg_replace('/[^0-9+\-Ee.]/', '', ($number . ''));
        $n = !is_finite(+$number) ? 0 : +$number;
        $prec = !$decimals ? 0 : abs($decimals);
        $sep = !$thousands_sep ? ',' : $thousands_sep;
        $dec = !$dec_point ? '.' : $dec_point;
        $toFixedFix = function ($n, $prec) {
            $k = pow(10, $prec);
            return '' . round($n * $k) / $k;
        };
        // Fix for IE parseFloat(0.55).toFixed(0) = 0;
        $s = explode('.', ($prec ? $toFixedFix($n, $prec) : '' . round($n)));
        if (strlen($s[0]) > 3) {
            $first_part = substr($s[0], 0, strlen($s[0]) - 3);
            $second_part = substr($s[0], strlen($first_part), 3);
            $first_part = preg_replace('/\B(?=(?:\d{2})+(?!\d))/', $sep, $first_part);
            $s[0] = $first_part . ',' . $second_part;
        }
        if (!array_key_exists(1, $s)) {
            $s[1] = null;
        }
        if (array_key_exists(1, $s) && strlen($s[1]) < $prec) {
            $s[1] = str_pad($s[1], $prec, '0', STR_PAD_RIGHT);
        }
        return join($dec, $s);
    }
}
