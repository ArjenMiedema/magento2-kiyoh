<?php

/**
 * Copyright Youwe. All rights reserved.
 * https://www.youweagency.com
 */

declare(strict_types=1);

namespace Elgentos\Kiyoh\Test\Cron;

use Elgentos\Kiyoh\Model\Config;
use Magento\Framework\Exception\CronException;
use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Variable\Model\ResourceModel\Variable as VariableResource;
use Magento\Variable\Model\VariableFactory;
use PHPUnit\Framework\TestCase;
use Elgentos\Kiyoh\Cron\RetrieveReviews;
use Psr\Log\LoggerInterface;

/**
 * @coversDefaultClass \Elgentos\Kiyoh\Cron\RetrieveReviews
 */
class RetrieveReviewsTest extends TestCase
{
    /**
     * @covers ::__construct
     * @covers ::processAggregateScores
     *
     * @dataProvider setDataProvider
     */
    public function testProcessAggregateScores(
        bool $hasLocationId,
        bool $hasApiKey
    ): void
    {
        $subject = new RetrieveReviews(
            $this->createMock(Curl::class),
            $this->createMock(Json::class),
            $this->createMock(LoggerInterface::class),
            $this->createVariableFactoryMock(),
            $this->createMock(VariableResource::class),
            $this->createConfigMock($hasLocationId, $hasApiKey)
        );

        if (!$hasLocationId || !$hasApiKey) {
            $this->expectException(CronException::class);
        }

        $subject->processAggregateScores();
    }

    private function createVariableFactoryMock(): VariableFactory
    {
        /** @var VariableFactory $factory */
        $factory = $this->getMockBuilder(VariableFactory::class)
            ->allowMockingUnknownTypes()
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        return $factory;
    }

    private function createConfigMock(
        bool $hasLocationId,
        bool $hasApiKey
    ): Config {
        $config = $this->createMock(Config::class);
        $config->expects($hasLocationId && $hasApiKey ? self::exactly(2) : self::once())
            ->method('getLocationId')
            ->willReturn($hasLocationId ? 12345467 : 0);

        $config
            ->expects(
                !$hasLocationId
                    ? self::never()
                    : ($hasApiKey ? self::exactly(2) : self::once())
            )
            ->method('getApiKey')
            ->willReturn($hasApiKey ? 'test' : '');

        return $config;
    }

    public function setDataProvider(): array
    {
        return [
            'noLocationId' => [false, true],
            'noApiKey' => [true, false],
            'validData' => [true, true]
        ];
    }
}
