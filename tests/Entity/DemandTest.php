<?php

namespace App\Tests\Entity;

use App\Entity\Demand;
 
class DemandTest extends \PHPUnit\Framework\TestCase
{      
           
    /**     
     *
     * @return void
     */
    public function testchangeStatusWaitingToInProgress(): void
    {                                                                     
        $demand = (new Demand())->setStatus(Demand::STATUS_WAITING);                                                            
        $this->assertSame(Demand::STATUS_IN_PROGRESS, $demand->changeStatus(Demand::STATUS_IN_PROGRESS));              
    }

    /**     
     *
     * @return void
     */
    public function testchangeStatusWaitingToClosed(): void
    {                                                                     
        $demand = (new Demand())->setStatus(Demand::STATUS_WAITING);                                                    
        $this->assertSame(Demand::STATUS_CLOSED, $demand->changeStatus(Demand::STATUS_CLOSED));              
    }

    /**     
     *
     * @return void
     */
    public function testchangeStatusInProgressToClosed(): void
    {                                                                     
        $demand = (new Demand())->setStatus(Demand::STATUS_IN_PROGRESS);                                                    
        $this->assertSame(Demand::STATUS_CLOSED, $demand->changeStatus(Demand::STATUS_CLOSED));              
    }
}