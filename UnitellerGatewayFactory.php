<?php

namespace Fullpipe\Payum\Uniteller;

use Fullpipe\Payum\Uniteller\Action\AuthorizeAction;
use Fullpipe\Payum\Uniteller\Action\CancelAction;
use Fullpipe\Payum\Uniteller\Action\CaptureAction;
use Fullpipe\Payum\Uniteller\Action\ConvertPaymentAction;
use Fullpipe\Payum\Uniteller\Action\FillOrderDetailsAction;
use Fullpipe\Payum\Uniteller\Action\NotifyAction;
use Fullpipe\Payum\Uniteller\Action\RefundAction;
use Fullpipe\Payum\Uniteller\Action\StatusAction;
use Payum\Core\Bridge\Spl\ArrayObject;
use Payum\Core\GatewayFactory;

class UnitellerGatewayFactory extends GatewayFactory
{
    /**
     * {@inheritDoc}
     */
    protected function populateConfig(ArrayObject $config)
    {
        $config->defaults([
            'payum.factory_name'              => 'uniteller',
            'payum.factory_title'             => 'Uniteller',
            'payum.action.capture'            => new CaptureAction(),
            'payum.action.authorize'          => new AuthorizeAction(),
            'payum.action.refund'             => new RefundAction(),
            'payum.action.cancel'             => new CancelAction(),
            'payum.action.notify'             => new NotifyAction(),
            'payum.action.status'             => new StatusAction(),
            'payum.action.convert_payment'    => new ConvertPaymentAction(),
            'payum.action.fill_order_details' => new FillOrderDetailsAction(),
        ]);

        if (false != $config['payum.api']) {
            return;
        }

        $config['payum.default_options'] = [
            'shop_id'  => '',
            'password' => '',
            'sandbox'  => true,
        ];
        $config->defaults($config['payum.default_options']);
        $config['payum.required_options'] = ['shop_id', 'password'];

        $config['payum.api'] = function (ArrayObject $config) {
            $config->validateNotEmpty($config['payum.required_options']);

            return new Api([
                'shop_id'  => $config['shop_id'],
                'password' => $config['password'],
                'sandbox'  => $config['sandbox']
            ]);
        };
    }
}
