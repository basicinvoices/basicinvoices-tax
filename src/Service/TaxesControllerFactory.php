<?php
namespace BasicInvoices\Tax\Service;

use BasicInvoices\Tax\Controller\TaxesController;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

class TaxesControllerFactory implements FactoryInterface
{
    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string             $requestedName
     * @param  null|array         $options
     * @return TaxesController
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $taxManager = $container->get('TaxManager');
        return new TaxesController($taxManager);
    }
}