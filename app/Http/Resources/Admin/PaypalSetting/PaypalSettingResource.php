<?php

namespace App\Http\Resources\Admin\PaypalSetting;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaypalSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'client_id' => $this->client_id,
            'client_secret' => $this->client_secret,
            'mode' => $this->mode,
            'currency' => $this->currency,
            'payment_action' => $this->payment_action,
            'notify_url' => $this->notify_url,
            'locale' => $this->locale,
            'validate_ssl' => $this->validate_ssl,
            'status' => $this->status,
        ];
    }
}
