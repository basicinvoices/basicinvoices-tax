<?php

namespace BasicInvoices\Tax;

class TaxManager extends AbstractTaxManager
{
    public function getAll($paginated = false)
    {
        $select = $this->sql->select($this->table);
        
        return $this->executeSelect($select);
    }
}
