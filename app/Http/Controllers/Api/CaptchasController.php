<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Gregwar\Captcha\CaptchaBuilder;
use Gregwar\Captcha\PhraseBuilder;
use App\Http\Requests\Api\CaptchaRequest;

class CaptchasController extends Controller
{
    public function store(CaptchaRequest $request, CaptchaBuilder $captchaBuilder){
        $key = 'captcha-'.str_random(15);
        $phone = $request->phone;

        // 自定义字符长度和范围
        $phraseBuilder = new PhraseBuilder(4);
        $captchaBuilder = new CaptchaBuilder(null, $phraseBuilder);
        $captcha = $captchaBuilder->build();

        // $captcha = $captchaBuilder->build();
        $expiredAt = now()->addMinutes(2);

        \Cache::put($key,['phone'=>$phone, 'code'=>$captcha->getPhrase()],$expiredAt);

        $result = [
            'captcha_key'=>$key,
            'expired_at'=>$expiredAt->toDateTImeString(),
            'captcha_image_content'=>$captcha->inline()
        ];

        return $this->response->array($result)->setStatusCode(201);
    }
}
