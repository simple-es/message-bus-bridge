<?php

/**
 * @license https://github.com/simple-es/message-bus-bridge/blob/master/LICENSE MIT
 */

namespace SimpleES\MessageBusBridge\Test\Core;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use SimpleES\EventSourcing\Event\Stream\EventId;
use SimpleES\EventSourcing\Event\Stream\EventStream;
use SimpleES\MessageBusBridge\Event\Store\Decorator\MessageRecordingDecorator;
use SimpleES\MessageBusBridge\Event\Stream\EventEnvelope;
use SimpleES\MessageBusBridge\Test\Auxiliary\AggregateId;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
class MessageRecordingDecoratorTest extends MockeryTestCase
{
    /**
     * @var MessageRecordingDecorator
     */
    private $eventStore;

    /**
     * @var \Mockery\MockInterface
     */
    private $messageRecorder;

    /**
     * @var \Mockery\MockInterface
     */
    private $nextEventStore;

    public function setUp()
    {
        $this->messageRecorder = \Mockery::mock('SimpleBus\Message\Recorder\RecordsMessages');

        $this->nextEventStore = \Mockery::mock('SimpleES\EventSourcing\Event\Store\StoresEvents');

        $this->eventStore = new MessageRecordingDecorator(
            $this->messageRecorder,
            $this->nextEventStore
        );
    }

    public function tearDown()
    {
        $this->eventStore      = null;
        $this->messageRecorder = null;
        $this->nextEventStore  = null;
    }

    /**
     * @test
     */
    public function itEnrichesMetadataWhenEventsAreCommittedBeforePassingThemToTheNextEventStore()
    {
        $id = AggregateId::fromString('some-id');

        $eventStream = $this->createEventStream($id);

        $this->messageRecorder
            ->shouldReceive('record')
            ->times(3)
            ->with(\Mockery::type('SimpleBus\Message\Message'));

        $this->nextEventStore
            ->shouldReceive('commit')
            ->once()
            ->with($eventStream);

        $this->eventStore->commit($eventStream);
    }

    /**
     * @test
     */
    public function itSimplyProxiesGettingEventsToTheNextEventStore()
    {
        $id = AggregateId::fromString('some-id');

        $eventStream = $this->createEventStream($id);

        $this->nextEventStore
            ->shouldReceive('read')
            ->once()
            ->with($id)
            ->andReturn($eventStream);

        $returnedEnvelopeStream = $this->eventStore->read($id);

        $this->assertSame($eventStream, $returnedEnvelopeStream);
    }

    /**
     * @param AggregateId $id
     * @return EventStream
     */
    private function createEventStream(AggregateId $id)
    {
        $envelope1 = EventEnvelope::envelop(
            EventId::fromString('event-1'),
            'event_1',
            \Mockery::mock('SimpleES\EventSourcing\Event\DomainEvent'),
            $id,
            0
        );

        $envelope2 = EventEnvelope::envelop(
            EventId::fromString('event-2'),
            'event_2',
            \Mockery::mock('SimpleES\EventSourcing\Event\DomainEvent'),
            $id,
            1
        );

        $envelope3 = EventEnvelope::envelop(
            EventId::fromString('event-3'),
            'event_3',
            \Mockery::mock('SimpleES\EventSourcing\Event\DomainEvent'),
            $id,
            2
        );

        return new EventStream($id, [$envelope1, $envelope2, $envelope3]);
    }
}
