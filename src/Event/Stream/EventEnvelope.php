<?php

/**
 * @license https://github.com/simple-es/message-bus-bridge/blob/master/LICENSE MIT
 */

namespace SimpleES\MessageBusBridge\Event\Stream;

use SimpleBus\Message\Message;
use SimpleES\EventSourcing\Event\Stream\EnvelopsEvent;
use SimpleES\EventSourcing\Event\Stream\EventEnvelopingCapabilities;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class EventEnvelope implements EnvelopsEvent, Message
{
    use EventEnvelopingCapabilities;
}
