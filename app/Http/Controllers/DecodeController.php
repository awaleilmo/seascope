<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DecodeController extends Controller
{
    public function decodeBase64($base64): bool|string|array|null|int
    {
        return base64_decode($base64);
    }
    public function encodeBase64($base64): string
    {
        return base64_encode($base64);
    }
}
