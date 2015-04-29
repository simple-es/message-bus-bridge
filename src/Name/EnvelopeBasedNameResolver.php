<?php

/**
 * @license https://github.com/simple-es/message-bus-bridge/blob/master/LICENSE MIT
 */

namespace SimpleES\MessageBusBridge\Name;

use SimpleBus\Message\Message;
use SimpleBus\Message\Name\Exception\CouldNotResolveMessageName;
use SimpleBus\Message\Name\MessageNameResolver;
use SimpleES\EventSourcing\Event\Stream\EnvelopsEvent;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Ramon de la Fuente <ramon@future500.nl>
 */
class EnvelopeBasedNameResolver implements MessageNameResolver
{
    /**
     * The message name is taken directly from the Envelope
     *
     * {@inheritdoc}
     */
    public function resolve(Message $envelope)
    {
        if (!($envelope instanceof EnvelopsEvent)) {
            throw CouldNotResolveMessageName::forMessage($envelope, 'Message should be an instance of EnvelopsEvent');
        }

        return $envelope->eventName();
    }
}
