<?php
namespace BasicInvoices\Tax;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\Sql\TableIdentifier;
use Zend\EventManager\EventManager;
use Zend\EventManager\EventManagerInterface;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Select;
use Zend\Db\ResultSet\ResultSet;

abstract class AbstractTaxManager implements TaxManagerInterface
{
    /**
     * @var AdapterInterface
     */
    protected $adapter = null;
    
    /**
     * @var TaxEvent
     */
    protected $event;
    
    /**
     * @var EventManagerInterface
     */
    protected $events;
    
    /**
     * @var Sql
     */
    protected $sql = null;
    
    /**
     * @var string|array|TableIdentifier
     */
    protected $table = null;
    
    /**
     * Constructor.
     * 
     * @param AdapterInterface             $adapter
     * @param string|TableIdentifier|array $table
     * @param Sql                          $sql
     * @throws Exception\InvalidArgumentException
     */
    public function __construct(AdapterInterface $adapter, $table = 'taxes', Sql $sql = null)
    {
        // table
        if (!(is_string($table) || $table instanceof TableIdentifier || is_array($table))) {
            throw new Exception\InvalidArgumentException('Table name must be a string or an instance of Zend\Db\Sql\TableIdentifier');
        }
        $this->table = $table;
        
        // adapter
        $this->adapter = $adapter;
        
        // Sql object (factory for select, insert, update, delete)
        $this->sql = ($sql) ?: new Sql($this->adapter);
    }
    
    public function executeSelect(Select $select)
    {
        $events = $this->getEventManager();
        $event  = $this->getEvent();
        
        // trigger the event to allow extending the query
        $event->setName(TaxEvent::EVENT_SELECT);
        $event->setParams([
            'select' => $select,
        ]);
        
        $events->triggerEvent($event);
        
        $select = $event->getParam('select', null);
        
        $statement = $this->sql->prepareStatementForSqlObject($select);
        $result    = $statement->execute();
        
        $resultSet = new ResultSet();
        $resultSet->initialize($result);
        
        // Trigger post event
        $event->setName(TaxEvent::EVENT_SELECT_POST);
        $event->setParams([
            'statement'  => $statement,
            'result'     => $result,
            'result_set' => $resultSet,
        ]);
        $events->triggerEvent($event);
        
        $resultSet = $event->getParam('result_set', null);
        
        return $resultSet;
    }
    
    /**
     * Get the tax event
     *
     * @return TaxEvent
     */
    public function getEvent()
    {
        if (!$this->event instanceof TaxEvent) {
            $event = new TaxEvent();
            $event->setTarget($this);
            $this->setEvent($event);
        }
        return $this->event;
    }
    
    /**
     * Retrieve the event manager
     *
     * Lazy-loads an EventManager instance if none registered.
     *
     * @return EventManagerInterface
     */
    public function getEventManager()
    {
        if (!$this->events instanceof EventManagerInterface) {
            $this->setEventManager(new EventManager());
        }
        return $this->events;
    }
    
    /**
     * Set the tax event
     *
     * @param  TaxEvent $event
     * @return AbstractTaxManager
     */
    public function setEvent(TaxEvent $event)
    {
        $event->setTarget($this);
        $this->event = $event;
        return $this;
    }
    
    /**
     * Inject an EventManager instance
     *
     * @param  EventManagerInterface $eventManager
     * @return AbstractTaxManager
     */
    public function setEventManager(EventManagerInterface $events)
    {
        $events->setIdentifiers([
            __CLASS__,
            get_class($this),
            'tax_manager',
        ]);
        $this->events = $events;
        return $this;
    }
}
