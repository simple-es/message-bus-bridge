<?php

/**
 * @license https://github.com/simple-es/message-bus-bridge/blob/master/LICENSE MIT
 */

namespace SimpleES\MessageBusBridge\Event\Store\Decorator;

use SimpleBus\Message\Message;
use SimpleBus\Message\Recorder\RecordsMessages;
use SimpleES\EventSourcing\Event\Store\StoresEvents;
use SimpleES\EventSourcing\Event\Stream\EventStream;
use SimpleES\EventSourcing\Identifier\Identifies;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
class MessageRecordingDecorator implements StoresEvents
{
    /**
     * @var RecordsMessages
     */
    private $messageRecorder;

    /**
     * @var StoresEvents
     */
    private $next;

    /**
     * @param RecordsMessages $messageRecorder
     * @param StoresEvents    $next
     */
    public function __construct(RecordsMessages $messageRecorder, StoresEvents $next)
    {
        $this->messageRecorder = $messageRecorder;
        $this->next            = $next;
    }

    /**
     * {@inheritdoc}
     */
    public function commit(EventStream $eventStream)
    {
        /** @var Message $envelope */
        foreach ($eventStream as $envelope) {
            $this->messageRecorder->record($envelope);
        }

        $this->next->commit($eventStream);
    }

    /**
     * {@inheritdoc}
     */
    public function read(Identifies $aggregateId)
    {
        return $this->next->read($aggregateId);
    }
}
