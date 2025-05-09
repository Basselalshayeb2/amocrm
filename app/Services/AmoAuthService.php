<?php

namespace App\Services;

use App\Models\AmoKeys;
use http\Exception\InvalidArgumentException;
use Illuminate\Support\Facades\Http;
use Mockery\Exception;
use function Psy\debug;

class AmoAuthService extends AmoService
{

    public function saveAccessTokens($data, $amoData = null): AmoKeys
    {
        if (!$amoData) {
            $amoData = new AmoKeys();
        }
        $amoData->access_token = $data['access_token'];
        $amoData->refresh_token = $data['refresh_token'];
        $amoData->expires_in = now()->addSeconds($data['expires_in'] - 60);
        $amoData->save();

        return $amoData;
    }

    public function getAccessToken(): AmoKeys
    {
        $response = Http::withoutVerifying()->post("$this->domain/oauth2/access_token", [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecrete,
            'grant_type' => 'authorization_code',
            'code' => $this->authCode,
            'redirect_uri' => $this->redirectUrl,
        ]);
        if (!$response->successful()) {
            throw new Exception($response->json()['hint']);
        }
        $data = $response->json();
        return $this->saveAccessTokens($data);
    }

    public function renewAccessToken(): AmoKeys
    {
        $amoKeys = AmoKeys::first();
        $response = Http::withoutVerifying()->post("$this->domain/oauth2/access_token", [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecrete,
            'grant_type' => 'refresh_token',
            'refresh_token' => $amoKeys->refresh_token,
            'redirect_uri' => $this->redirectUrl,
        ]);

        $data = $response->json();
        return $this->saveAccessTokens($data, $amoKeys);
    }

    public function getCurrentAccessToken(): AmoKeys
    {
        return AmoKeys::first();
    }


}
