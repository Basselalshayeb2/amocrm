<?php

namespace App\Services;

class AmoService
{

    public function __construct()
    {
        $this->clientSecrete = config('amo.amo_secret_key');
        $this->clientId = config('amo.amo_integration_id');
        $this->authCode = config('amo.amo_auth_code');
        $this->domain = config('amo.amo_base_url');
        $this->redirectUrl = config('amo.amo_redirect_url');
    }

}
