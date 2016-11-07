<?php
namespace BasicInvoices\Tax;

use Zend\EventManager\EventManagerAwareInterface;

interface TaxManagerInterface extends EventManagerAwareInterface
{
    public function getAll($paginated = false);
}
