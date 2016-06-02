# influxdb-bundle

Integration bundle for writing to influxdb

[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D7.0-blue.svg)](https://img.shields.io/badge/PHP-%3E%3D7.0-blue.svg) [![Build Status](https://travis-ci.org/Algatux/influxdb-bundle.svg?branch=master)](https://travis-ci.org/Algatux/influxdb-bundle) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Algatux/influxdb-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Algatux/influxdb-bundle/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/Algatux/influxdb-bundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Algatux/influxdb-bundle/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/algatux/influxdb-bundle/v/stable)](https://packagist.org/packages/algatux/influxdb-bundle) [![Latest Unstable Version](https://poser.pugx.org/algatux/influxdb-bundle/v/unstable)](https://packagist.org/packages/algatux/influxdb-bundle) [![License](https://poser.pugx.org/algatux/influxdb-bundle/license)](https://packagist.org/packages/algatux/influxdb-bundle)

### Installation

First of all, you need to require this library through composer:

```bash
composer require algatux/influxdb-bundle
```

Then, enable the bundle on the `AppKernel` class:

```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Algatux\InfluxDbBundle\InfluxDbBundle(),
    );

    // ...

    return $bundles
}
```

### Configuration

in your config.yml add:

```yaml
influx_db:
    host: '{your influxdb host address}'
    database: '{your influxdb database name}'

    # Other not required options
    username: '{your influxdb username}'
    password: '{your influxdb password}'
    udp_port: '{your influxdb udp port}' (default 4444)
    http_port: '{your influxdb http port}' (default 8086)
    use_events: true (default false)
```

### Sending data to influx db

assuming this collection to send:

```php
$time = new \DateTime();

$points = new PointsCollection([new Point(
    'test_metric', // name of the measurement
    0.64, // the measurement value
    ['host' => 'server01', 'region' => 'italy'], // optional tags
    ['cpucount' => rand(1,100), 'memory' => memory_get_usage(true)], // optional additional fields
    $time->getTimestamp()
)],Database::PRECISION_SECONDS);

```

###### Service
```php
// UDP
$container
    ->get('algatux_influx_db.client.udp.writer_client')
    ->write($points);

// HTTP
$container
    ->get('algatux_influx_db.client.http.writer_client')
    ->write($points);

```

###### Event Dispatcher
```php
// UDP
$container
    ->get('event_dispatcher')
    ->dispatch(InfluxDbEvent::NAME, new UdpEvent($points));

// HTTP
$container
    ->get('event_dispatcher')
    ->dispatch(InfluxDbEvent::NAME, new HttpEvent($points));


// Deferred Events
// Collect your measurements during the request and make only one write to influxdb.
// Deferred events are catched and "stored". Than on the kernel.terminate event one write per
// event type and precision will be fired.

// UDP
$container
    ->get('event_dispatcher')
    ->dispatch(InfluxDbEvent::NAME, new DeferredUdpEvent($points));

// HTTP
$container
    ->get('event_dispatcher')
    ->dispatch(InfluxDbEvent::NAME, new DeferredHttpEvent($points));

```


