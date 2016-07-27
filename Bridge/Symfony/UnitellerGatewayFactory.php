<?php

namespace Fullpipe\Payum\Uniteller\Bridge\Symfony;

use Payum\Bundle\PayumBundle\DependencyInjection\Factory\Payment\AbstractPaymentFactory;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
// @todo: symfony support
class UnitellerGatewayFactory extends AbstractPaymentFactory
{
    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'uniteller';
    }

    /**
     * {@inheritDoc}
     */
    public function addConfiguration(ArrayNodeDefinition $builder)
    {
        parent::addConfiguration($builder);

        $builder->children()
            ->scalarNode('shop_id')->isRequired()->cannotBeEmpty()->end()
            ->scalarNode('password')->isRequired()->cannotBeEmpty()->end()
            ->booleanNode('sandbox')->defaultTrue()->end()
            ->end();
    }

    /**
     * {@inheritDoc}
     */
    protected function getPayumGatewayFactoryClass()
    {
        return UnitellerGatewayFactory::class;
    }

    /**
     * {@inheritDoc}
     */
    protected function getComposerPackage()
    {
        return 'fullpipe/payum-uniteller';
    }
}
