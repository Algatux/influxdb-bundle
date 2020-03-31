<?php

namespace Algatux\InfluxDbBundle\Events;

// Symfony 5+
if (class_exists('\Symfony\Contracts\EventDispatcher\Event')) {
    class SymfonyEvent extends \Symfony\Contracts\EventDispatcher\Event
    {
    }
} else {
    class SymfonyEvent extends \Symfony\Component\EventDispatcher\Event
    {
    }
}

