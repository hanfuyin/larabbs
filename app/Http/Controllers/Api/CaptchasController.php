<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\Controller;
use Gregwar\Captcha\CaptchaBuilder;
use App\Http\Requests\Api\CaptchaRequest;

class CaptchasController extends Controller
{
    public function store(CaptchaRequest $request, CaptchaBuilder $captchaBuilder)
    {
        $key = 'captcha'.str_random(15);
        $phone = $request->phone;

        $captchaBuilder->setLength(4);

        $captcha = $captchaBuilder->build();
        $expiredAt = now()->addMinute(2);

        \Cache::put($key, ['phone' => $phone, 'code' => $captcha->getPhrase()], $expiredAt);

        $result = [
            'captcha_key' => $key,
            'expired_at' => $expiredAt->toDateTimeString(),
            'captcha_image_content' => $captcha->inline()
        ];

        return $this->response->array($result)->setStatusCode(201);
    }
}
