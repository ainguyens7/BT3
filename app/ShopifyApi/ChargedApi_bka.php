<?php

namespace App\ShopifyApi;


use App\Services\ShopifyServices;

class ChargedApi_bka extends ShopifyServices
{
    public function addCharge()
    {
        try{
            $addCharge = $this->_shopify->call([
                'URL' => 'recurring_application_charges.json',
                'METHOD' => 'POST',
                'DATA' => [
                    'recurring_application_charge' => [
                        'name' => config('charged.name'),
                        'price' => config('charged.price'),
                        'return_url' => route('apps.chargeHandle'),
                        'trial_days' => config('charged.trial_days'),
                        'test' => config('charged.test')
                    ]
                ]
            ]);
            return ['status' => true, 'addCharge' => $addCharge->recurring_application_charge];
        } catch (\Exception $exception)
        {
            return ['status' => false, 'message' => $exception->getMessage()];
        }

    }

    /**
     * @param $id
     * @return array
     */
    public function detailCharge($id)
    {
        try{
            $detailCharge = $this->_shopify->call([
                'URL' => 'recurring_application_charges/'.$id.'.json',
                'METHOD' => 'GET'
            ]);
            return ['status' => true, 'detailCharge' => $detailCharge->recurring_application_charge];

        } catch (\Exception $exception)
        {
            return ['status' => false, 'message' => $exception->getMessage()];
        }
    }

    public function activeCharge($idCharge)
    {
        try{
            $activeCharge = $this->_shopify->call([
                'URL' => 'admin/recurring_application_charges/'.$idCharge.'/activate.json',
                'METHOD' => 'POST',
                'HEADER' => [
                    'Content-Length' => 256
                ]
            ]);
            return ['status' => true, 'activeCharge' => $activeCharge->recurring_application_charge];
        } catch (\Exception $exception)
        {
            return ['status' => false, 'message' => $exception->getMessage()];
        }
    }


    public function allCharge()
    {
        try{
            $allCharge = $this->_shopify->call([
                'URL' => 'recurring_application_charges.json',
                'METHOD' => 'GET'
            ]);
            return ['status' => true, 'allCharge' => $allCharge->recurring_application_charges];
        } catch (\Exception $exception)
        {
            return ['status' => false, 'message' => $exception->getMessage()];
        }
    }
}