<?php

namespace App\Http\Resources\Api\AppSetting;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'title' => $this->title,
            'slogan' => $this->slogan,
            'summary' => $this->summary,
            'emails' => $this->emails,
            'phone_numbers' => $this->phone_numbers,
            'map_latitude' => $this->map_latitude,
            'map_longitude' => $this->map_longitude,
            'map_iframe' => $this->map_iframe,
            'facebook_link' => $this->facebook_link,
            'instagram_link' => $this->instagram_link,
            'twitter_link' => $this->twitter_link,
            'behance_link' => $this->behance_link,
            'youtube_link' => $this->youtube_link,
            'tiktok_link' => $this->tiktok_link,
            'linkedin_link' => $this->linkedin_link,
            'pinterest_link' => $this->pinterest_link,
            'patreon_link' => $this->patreon_link,
            'google_maps_api_key' => $this->google_maps_api_key,
            'google_analytics_clint_id' => $this->google_analytics_clint_id,
            'google_recaptcha_api_key' => $this->google_recaptcha_api_key,
            'maintenance_mode' => $this->maintenance_mode,
            'logo' => $this->logo,
            'logo_light' => $this->logo_light,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'logo_url' => $this->logo_url,
            'logo_light_url' => $this->logo_light_url,
            'address' => $this->address,
            "supported_languages" => core()->getSupportedLocales(),
            "app_direction" => core()->getCurrentLocaleDirection(),
            // 'translations' => $this->translations,
        ];
    }
}
