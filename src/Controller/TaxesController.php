<?php
namespace BasicInvoices\Tax\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use BasicInvoices\Tax\TaxManagerInterface;

class TaxesController extends AbstractActionController
{
    /**
     * @var TaxManagerInterface
     */
    protected $taxManager;
    
    public function __construct(TaxManagerInterface $taxManager)
    {
        $this->taxManager = $taxManager;
    }
    
    public function indexAction()
    {
        $taxes = $this->taxManager->getAll();
        return new ViewModel([
            'taxes' => $taxes,
        ]);
    }
}