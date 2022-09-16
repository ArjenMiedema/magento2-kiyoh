<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Elgentos\Kiyoh\Test\Adminhtml\Model\System\Config\Source;

use PHPUnit\Framework\TestCase;
use Elgentos\Kiyoh\Adminhtml\Model\System\Config\Source\ReviewEvents;

/**
 * @coversDefaultClass \Elgentos\Kiyoh\Adminhtml\Model\System\Config\Source\ReviewEvents
 */
class ReviewEventsTest extends TestCase
{
    /**
     * @covers ::toOptionArray
     */
    public function testToOptionArray(): void
    {
        $subject = new ReviewEvents();
        $this->assertCount(2, $subject->toOptionArray());
    }
}
