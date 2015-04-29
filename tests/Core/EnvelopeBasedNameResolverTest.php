<?php

/**
 * @license https://github.com/simple-es/message-bus-bridge/blob/master/LICENSE MIT
 */

namespace SimpleES\MessageBusBridge\Test\Core;

use Mockery\Adapter\Phpunit\MockeryTestCase;
use SimpleES\EventSourcing\Event\Stream\EventId;
use SimpleES\MessageBusBridge\Event\Stream\EventEnvelope;
use SimpleES\MessageBusBridge\Name\EnvelopeBasedNameResolver;
use SimpleES\MessageBusBridge\Test\Auxiliary\AggregateId;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Ramon de la Fuente <ramon@future500.nl>
 */
class EnvelopeBasedNameResolverTest extends MockeryTestCase
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
    public function itReturnsTheEventNameAsTheUniqueNameOfAMessage()
    {
        $resolver = new EnvelopeBasedNameResolver();
        $message = $this->envelope;
        $this->assertSame(
            'some_name',
            $resolver->resolve($message)
        );
    }

    /**
     * @test
     * @expectedException \SimpleBus\Message\Name\Exception\CouldNotResolveMessageName
     */
    public function itThrowsAnExceptionWhenGivenMessagesNotImplementingEnvelope()
    {
        $resolver = new EnvelopeBasedNameResolver();
        $message = \Mockery::mock('\SimpleBus\Message\Message');
        $resolver->resolve($message);
    }

}

