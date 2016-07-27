<?php

namespace Fullpipe\Payum\Uniteller\Action;

use Fullpipe\Payum\Uniteller\Action\Api\BaseApiAwareAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Reply\HttpPostRedirect;
use Payum\Core\Request\Capture;

class CaptureAction extends BaseApiAwareAction
{
    /**
     * {@inheritDoc}
     */
    public function execute($request)
    {
        /* @var $request Capture */
        RequestNotSupportedException::assertSupports($this, $request);

        $details = ArrayObject::ensureArrayObject($request->getModel());

        if (null === $details['URL_RETURN'] && $request->getToken()) {
            $details['URL_RETURN'] = $request->getToken()->getAfterUrl();
        }

        $details['Shop_IDP']  = $this->api->getShopId();
        $details['Signature'] = $this->api->sing($details->toUnsafeArray());

        $details->validatedKeysSet([
            'Shop_IDP',
            'Order_IDP',
            'Subtotal_P',
            'Currency',
            'Signature',
        ]);

        /*$url = $this->api->getPaymentPageUrl().'?'.http_build_query($details->toUnsafeArray());
        echo '<pre>';
        print_r($url);
        echo '</pre>';
        exit;*/
        throw new HttpPostRedirect($this->api->getPaymentPageUrl(), $details->toUnsafeArray());
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Capture &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
