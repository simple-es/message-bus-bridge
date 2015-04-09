<?php

/**
 * @license https://github.com/simple-es/message-bus-bridge/blob/master/LICENSE MIT
 */

namespace SimpleES\MessageBusBridge\Test\Core;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use SimpleES\EventSourcing\Event\Stream\EventId;
use SimpleES\EventSourcing\Metadata\Metadata;
use SimpleES\MessageBusBridge\Event\Stream\EventEnvelope;
use SimpleES\MessageBusBridge\Test\Auxiliary\AggregateId;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
class EventEnvelopeTest extends MockeryTestCase
{
    /**
     * @var EventEnvelope
     */
    private $envelope;

    /**
     * @var \Mockery\MockInterface
     */
    private $event;

    public function setUp()
    {
        $aggregateId = AggregateId::fromString('some-id');
        $eventId     = EventId::fromString('some-id');

        $this->event = \Mockery::mock('SimpleES\EventSourcing\Event\DomainEvent');

        $this->envelope = EventEnvelope::envelop(
            $eventId,
            'some_name',
            $this->event,
            $aggregateId,
            123
        );
    }

    public function tearDown()
    {
        $this->envelope = null;
        $this->event    = null;
    }

    /**
     * @test
     */
    public function itExposesAnEventId()
    {
        $id = EventId::fromString('some-id');

        $exposedId = $this->envelope->eventId();

        $this->assertTrue($id->equals($exposedId));
    }

    /**
     * @test
     */
    public function itExposesAnEventName()
    {
        $exposeName = $this->envelope->eventName();

        $this->assertSame('some_name', $exposeName);
    }

    /**
     * @test
     */
    public function itExposesAnEvent()
    {
        $exposedEvent = $this->envelope->event();

        $this->assertSame($this->event, $exposedEvent);
    }

    /**
     * @test
     */
    public function itExposesAnAggregateId()
    {
        $id = AggregateId::fromString('some-id');

        $exposedId = $this->envelope->aggregateId();

        $this->assertTrue($id->equals($exposedId));
    }

    /**
     * @test
     */
    public function itExposesAnAggregateVersion()
    {
        $exposedVersion = $this->envelope->aggregateVersion();

        $this->assertSame(123, $exposedVersion);
    }

    /**
     * @test
     */
    public function itExposesWhenItTookPlace()
    {
        $exposedTimestamp = $this->envelope->tookPlaceAt();

        $this->assertInstanceOf('SimpleES\EventSourcing\Timestamp\Timestamp', $exposedTimestamp);
    }

    /**
     * @test
     */
    public function itExposesMetadata()
    {
        $exposedMetadata = $this->envelope->metadata();

        $this->assertInstanceOf('SimpleES\EventSourcing\Metadata\Metadata', $exposedMetadata);
    }

    /**
     * @test
     */
    public function itEnrichesMetadata()
    {
        $enrichedEnvelope = $this->envelope->enrichMetadata(new Metadata(['some-key' => 'Some value']));
        $enrichedMetadata = $enrichedEnvelope->metadata();

        $this->assertSame('Some value', $enrichedMetadata['some-key']);
    }

    /**
     * @test
     */
    public function itDoesNotChangeItselfWhenMetadataIsenrich()
    {
        $originalMetadata = $this->envelope->metadata();

        $this->envelope->enrichMetadata(new Metadata(['some-key' => 'Some value']));

        $this->assertSame($originalMetadata, $this->envelope->metadata());
    }
}
