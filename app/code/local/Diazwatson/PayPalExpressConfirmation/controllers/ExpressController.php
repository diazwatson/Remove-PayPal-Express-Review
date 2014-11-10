<?php
/**
 * Created by PhpStorm.
 * User: rdiaz
 * Date: 23/10/14
 * Time: 23:11
 */

require_once ("Mage/Paypal/controllers/ExpressController.php");

class Diazwatson_PayPalExpressConfirmation_ExpressController extends Mage_Paypal_ExpressController
{
    /**
     * Return from PayPal and dispatch customer to order review page
     */

    public function _contruct()
    {
        $hideConfirmationPage = Mage::getStoreConfig('payment/paypal_payments/hide_confirmation_page');
        if(!$hideConfirmationPage)
        {
            exit();
        }
    }

    public function returnAction()
    {
        try {
            $this->_initCheckout();
            $this->_checkout->returnFromPaypal($this->_initToken());

            $hideConfirmationPage = Mage::getStoreConfig('payment/express_checkout_required_express_checkout/hide_confirmation_page');

            if($hideConfirmationPage)
            {
                $this->_redirect('*/*/placeOrder');
            }else{
                $this->_redirect('*/*/review');
            }

            return;
        }
        catch (Mage_Core_Exception $e) {
            Mage::getSingleton('checkout/session')->addError($e->getMessage());
        }
        catch (Exception $e) {
            Mage::getSingleton('checkout/session')->addError($this->__('Unable to process Express Checkout approval.'));
            Mage::logException($e);
        }
        $this->_redirect('checkout/cart');
    }

    /**
     * Instantiate quote and checkout
     * @throws Mage_Core_Exception
     */
    private function _initCheckout()
    {
        $quote = $this->_getQuote();
        if (!$quote->hasItems() || $quote->getHasError()) {
            $this->getResponse()->setHeader('HTTP/1.1','403 Forbidden');
            Mage::throwException(Mage::helper('paypal')->__('Unable to initialize Express Checkout.'));
        }
        $this->_checkout = Mage::getSingleton($this->_checkoutType, array(
            'config' => $this->_config,
            'quote'  => $quote,
        ));
    }

    /**
     * Return checkout quote object
     *
     * @return Mage_Sale_Model_Quote
     */
    private function _getQuote()
    {
        if (!$this->_quote) {
            $this->_quote = $this->_getCheckoutSession()->getQuote();
        }
        return $this->_quote;
    }

    /**
     * Return checkout session object
     *
     * @return Mage_Checkout_Model_Session
     */
    private function _getCheckoutSession()
    {
        return Mage::getSingleton('checkout/session');
    }
}