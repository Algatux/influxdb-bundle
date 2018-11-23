# influxdb-bundle

Bundle service integration of official [influxdb/influxdb-php](https://github.com/influxdata/influxdb-php) client

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/ecdd3130-abb1-4e63-bb92-20c52838aade/mini.png)](https://insight.sensiolabs.com/projects/ecdd3130-abb1-4e63-bb92-20c52838aade)

[![PHP Version](https://img.shields.io/badge/PHP-%5E7.0-blue.svg)](https://img.shields.io/badge/PHP-%5E7.0-blue.svg) [![Latest Stable Version](https://poser.pugx.org/algatux/influxdb-bundle/v/stable)](https://packagist.org/packages/algatux/influxdb-bundle) [![Latest Unstable Version](https://poser.pugx.org/algatux/influxdb-bundle/v/unstable)](https://packagist.org/packages/algatux/influxdb-bundle) [![Dependency Status](https://www.versioneye.com/user/projects/57a33e821dadcb004272cfc0/badge.svg?style=flat-square)](https://www.versioneye.com/user/projects/57a33e821dadcb004272cfc0) [![Total Downloads](https://poser.pugx.org/algatux/influxdb-bundle/downloads)](https://packagist.org/packages/algatux/influxdb-bundle)

[![Build Status](https://travis-ci.org/Algatux/influxdb-bundle.svg?branch=master)](https://travis-ci.org/Algatux/influxdb-bundle) [![StyleCI](https://styleci.io/repos/50687578/shield)](https://styleci.io/repos/50687578) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Algatux/influxdb-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Algatux/influxdb-bundle/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/Algatux/influxdb-bundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Algatux/influxdb-bundle/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/Algatux/influxdb-bundle/badge.svg?branch=master)](https://coveralls.io/github/Algatux/influxdb-bundle?branch=master) [![License](https://poser.pugx.org/algatux/influxdb-bundle/license)](https://packagist.org/packages/algatux/influxdb-bundle) 

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

Here is the configuration reference:

```yaml
influx_db:

    # If not defined, the first connection will be taken.
    default_connection:   ~
    connections:

        # Prototype
        name:

            # Your InfluxDB host address
            host:                 ~ # Required

            # Your InfluxDB database name
            database:             ~ # Required

            # Set it to true to activate the UDP connection
            udp:                  false
            
            # Set it to true to enable SSL over HTTP (required for Influx Cloud)
            ssl:                  false
            
            udp_port:             4444
            http_port:            8086
            username:             ''
            password:             ''
```

If you have only one connection to configure, this can be simplified to this:

```yaml
influx_db:
    # Your InfluxDB host address
    host:                 ~ # Required

    # Your InfluxDB database name
    database:             ~ # Required

    # Set it to true to activate the UDP connection
    udp:                  false
    
    # Set it to true to enable SSL over HTTP (required for Influx Cloud)
    ssl:                  false
    
    udp_port:             4444
    http_port:            8086
    username:             ''
    password:             ''
```

### Services

You can directly access to the `InfluxDB\Database` through UDP or HTTP with those services:

```php
$httpDatabase = $this->get('algatux_influx_db.connection.http'); // Default HTTP connection
$udpDatabase = $this->get('algatux_influx_db.connection.udp');   // Default UDP connection

// Same as before.
$httpDatabase = $this->get('algatux_influx_db.connection.default.http');
$udpDatabase = $this->get('algatux_influx_db.connection.default.udp');
```

You can also retrieve them thanks to the registry:

```php
$database = $this->get('algatux_influx_db.connection_registry')->getDefaultHttpConnection();
$database = $this->get('algatux_influx_db.connection_registry')->getDefaultUdpConnection();

// Same as before.
$database = $this->get('algatux_influx_db.connection_registry')->getHttpConnection('default');
$database = $this->get('algatux_influx_db.connection_registry')->getUdpConnection('default');
```

To manipulate the database, please read the official documentation
for [reading](https://github.com/influxdata/influxdb-php#reading)
and [writing](https://github.com/influxdata/influxdb-php#writing-data).

### Sending data to influx db through events

Assuming this collection to send:

```php
$time = new \DateTime();

$points = [new Point(
    'test_metric', // name of the measurement
    0.64, // the measurement value
    ['host' => 'server01', 'region' => 'italy'], // optional tags
    ['cpucount' => rand(1,100), 'memory' => memory_get_usage(true)], // optional additional fields
    $time->getTimestamp()
)];
```

Dispatch the event instance according to the chosen writing protocol:

```php
// UDP
$container
    ->get('event_dispatcher')
    ->dispatch(InfluxDbEvent::NAME, new UdpEvent($points, Database::PRECISION_SECONDS));

// HTTP
$container
    ->get('event_dispatcher')
    ->dispatch(InfluxDbEvent::NAME, new HttpEvent($points, Database::PRECISION_SECONDS));
```

Or, if you prefer to defer the event:

```php
// Deferred Events
// Collect your measurements during the request and make only one write to influxdb.
// Deferred events are catched and "stored". Than on the kernel.terminate event one write per
// event type and precision will be fired.

// UDP
$container
    ->get('event_dispatcher')
    ->dispatch(InfluxDbEvent::NAME, new DeferredUdpEvent($points, Database::PRECISION_SECONDS));

// HTTP
$container
    ->get('event_dispatcher')
    ->dispatch(InfluxDbEvent::NAME, new DeferredHttpEvent($points, Database::PRECISION_SECONDS));
```

If you want to write to another connection than the default, you must specify it:

```php
// UDP
$container
    ->get('event_dispatcher')
    ->dispatch(InfluxDbEvent::NAME, new UdpEvent($points, Database::PRECISION_SECONDS, 'other_connection'));

// HTTP
$container
    ->get('event_dispatcher')
    ->dispatch(InfluxDbEvent::NAME, new HttpEvent($points, Database::PRECISION_SECONDS, 'other_connection'));
```

### Commands

Some commands are provided:

* `algatux:influx:database:create`: To create the database.
* `algatux:influx:database:drop`: To drop the database.

To get more information, run:

```bash
./app/console help <command>
```

### Form types

This bundle provides several pre-defined form types. They are useful but optional.

If you want to use them, you have to require the `symfony/form` package.

Description of each of them is on the class doc block. Here is a short usage example:

```php
$form
    ->add('measurement', MeasurementType::class, [
        'connection' => 'default' // Optional: The connection you want to use.
    ])
    ->add('fields', FieldKeyType::class, [
        'measurement' => 'cpu', // The concerned measurement.
        'multiple' => true, // Parent type is ChoiceType. You can use parent option like multiple.
    ])
    ->add('tags', TagKeyType::class, [
        'measurement' => 'cpu',
        'exclude_host' => false, // True by default. Excludes the 'host' choice value.
        'multiple' => true,
    ])
    ->add('tag_value', TagValueType::class, [
        'measurement' => 'disk',
        'tag_key' => 'fstype', // The related tag key.
    ])
;
```

### Contributing

Feel free to contribute by opening a pull request, if you find a bug or to suggest a new feature.
If you like docker, this repository is provided with a dev environment with some scripts to prepare and use it.
All you need is docker and docker-compose installed on your system.

```
 make setup  # will build the needed containers and setup the project
```

```
 make start  # will start the needed containers and put you inside the php-cli container
```

```
 make test  # will launch the test suite
```

Note: All these scripts are meant to be used outside the containers.
