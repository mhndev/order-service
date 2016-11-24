<?php

\mhndev\event\Event::bind('order.created', new \mhndev\orderService\events\EventOrderCreated);
