<?php

/**
 * @license https://github.com/simple-es/doctrine-dbal-bridge/blob/master/LICENSE MIT
 */

namespace SimpleES\MessageBusBridge\Test\Auxiliary;

use SimpleES\EventSourcing\Identifier\Identifies;
use SimpleES\EventSourcing\Identifier\IdentifyingCapabilities;

/**
 * @copyright Copyright (c) 2015 Future500 B.V.
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 */
final class AggregateId implements Identifies
{
    use IdentifyingCapabilities;
}
