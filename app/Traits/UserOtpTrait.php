<?php

namespace App\Traits;

use App\Notifications\SendUserOtpNotification;
use App\Repositories\OtpRepository;

trait UserOtpTrait
{

    private static function generateOtp($length = 6)
    {
        return rand(pow(10, $length - 1), pow(10, $length) - 1);
    }

    private static function generateOtpExpiryDateTime($length = 5)
    {
        return now()->addMinutes($length);
    }

    public function sendOtpCode($user)
    {

        $otpCode = $this::generateOtp();
        $expiryDateTime = $this::generateOtpExpiryDateTime();

        app(OtpRepository::class)->storeOtp($user->id, $otpCode, $expiryDateTime);

        $user->notify(new SendUserOtpNotification($otpCode));

        return $otpCode;
    }

    public function isValidOtpCode($user, $otpCode)
    {

        $otp = app(OtpRepository::class)->getByUserId($user->id);
        if ($otp && $otp->otp == $otpCode) {
            return true;
        }
        return false;
    }

    public function resendOtpCode($user)
    {

        if (app(OtpRepository::class)->getByUserId($user->id)) {
            return false;
        }
        $otpCode = $this::generateOtp();
        $expiryDateTime = $this::generateOtpExpiryDateTime();

        app(OtpRepository::class)->storeOtp($user->id, $otpCode, $expiryDateTime);

        $user->notify(new SendUserOtpNotification($otpCode));

        return true;
    }
}
