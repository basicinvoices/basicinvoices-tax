<?php
namespace BasicInvoices\Tax;

interface TaxInterface
{
    public function getDescription();
    
    public function getPercentage();
    
    
}