<?php

namespace App\Tests\Entity;

use App\Entity\Demand;
 
class DemandTest extends \PHPUnit\Framework\TestCase
{      
           
    public function testchangeStatusWaitingToInProgress()
    {                                                                     
        $demand = (new Demand())->setStatus(Demand::STATUS_WAITING);                                                            
        $this->assertSame(Demand::STATUS_IN_PROGRESS, $demand->changeStatus(Demand::STATUS_IN_PROGRESS));              
    }

    public function testchangeStatusWaitingToClosed()
    {                                                                     
        $demand = (new Demand())->setStatus(Demand::STATUS_WAITING);                                                    
        $this->assertSame(Demand::STATUS_CLOSED, $demand->changeStatus(Demand::STATUS_CLOSED));              
    }

    public function testchangeStatusInProgressToClosed()
    {                                                                     
        $demand = (new Demand())->setStatus(Demand::STATUS_IN_PROGRESS);                                                    
        $this->assertSame(Demand::STATUS_CLOSED, $demand->changeStatus(Demand::STATUS_CLOSED));              
    }
}