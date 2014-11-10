<?php
/**
 * Created by PhpStorm.
 * User: rdiaz
 * Date: 25/10/14
 * Time: 11:56
 */

class Diazwatson_PayPalExpressConfirmation_Model_Config extends Mage_Paypal_Model_Config
{
    /**
     * Get url for dispatching customer to express checkout start
     *
     * @param string $token
     * @return string
     */
    public function getExpressCheckoutStartUrl($token)
    {
        return $this->getPaypalUrl(array(
            'cmd'   => '_express-checkout',
            'useraction' => 'commit',
            'token' => $token,
        ));
    }
}