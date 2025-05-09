<?php

namespace App\Http\Controllers;

use App\Services\AmoAuthService;
use App\Services\AmoMainService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AmoController
{
    //

    public function webhook(Request $request, AmoMainService $amoAuthService)
    {
        $data = $request->all();

        $contactModel = $data['contacts']['add'][0] ?? $data['contacts']['update'][0] ?? false;
        // lead creation
        if (isset($data['leads']['add'])) {
            foreach ($data['leads']['add'] as $lead) {
                $amoAuthService->handleLeadCreation($lead);
            }
        }
        // lead update
        if (isset($data['leads']['update'])) {
            foreach ($data['leads']['update'] as $lead) {
                $amoAuthService->handleLeadUpdate($lead);
            }
        }
        // contact create
        if (isset($data['contacts']['add'])) {
            foreach ($data['contacts']['add'] as $contact) {
                $amoAuthService->handleContactCreation($contact);
            }
        }
        // contact update
        if (isset($data['contacts']['update'])) {
            foreach ($data['contacts']['update'] as $contact) {
                $amoAuthService->handleContactCreation($contact);
            }
        }
        return response()->json(['status' => 'ok']);
    }
}
