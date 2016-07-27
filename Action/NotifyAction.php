<?php

namespace Fullpipe\Payum\Uniteller\Action;

use Fullpipe\Payum\Uniteller\Action\Api\BaseApiAwareAction;
use Payum\Core\Action\PaymentAwareAction;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Reply\HttpResponse;
use Payum\Core\Request\GetHttpRequest;
use Payum\Core\Request\Notify;

class NotifyAction extends BaseApiAwareAction implements ApiAwareInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;

    /**
     * {@inheritDoc}
     *
     * @param Notify $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $details = ArrayObject::ensureArrayObject($request->getModel());
        $this->gateway->execute($httpRequest = new GetHttpRequest());

        if (false == $this->api->validateNotificationSignature($httpRequest->request)) {
            throw new HttpResponse('The notification is invalid', 400);
        }

        $details['Status'] = $httpRequest->request['Status'];

        throw new HttpResponse('', 200);
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Notify &&
            $request->getModel() instanceof \ArrayAccess
        ;
    }
}
