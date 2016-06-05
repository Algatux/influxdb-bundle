UPGRADE FROM 1.x to 2.0
=======================

## Deprecations

All the deprecated code introduced on 1.x is removed on 2.0.

Please read [1.x](UPGRADE-1.x.md) upgrade guides for more information.

See also the [diff code](https://github.com/sonata-project/SonataAdminBundle/compare/1.x...2.0.0).

## Configuration

The `use_events` keys does not exists anymore. Listeners will be always defined.

## Internal classes

Some classes are now internal:

* `Algatux\InfluxDbBundle\Events\Listeners\InfluxDbEventListener`

Those classes should not be used outside of the package scope and can have BC break modifications.

## Integer type for port parameters

The following keys should now be integers, not string:

Before:

```yaml
influx_db:
    udp_port: '4444'
    http_port: '8086'
```

```yaml
influx_db:
    udp_port: 4444
    http_port: 8086
```

## InfluxDbEventDbListener

The constructor gets two `Database` instance instead of `Writer`.
The third `PointsCollectionStorage` argument is removed.

This should change nothing because the class should not be called manually.

## InfluxDbEvent

The abstract `InfluxDbEvent` class and all his children now receive only one parameter.

This parameter is an array of `Point` classes.

This concerns:

* `Algatux\InfluxDbBundle\Events\HttpEvent`
* `Algatux\InfluxDbBundle\Events\UdpEvent`
* `Algatux\InfluxDbBundle\Events\DeferredHttpEvent`
* `Algatux\InfluxDbBundle\Events\HttpEventDeferredUdpEvent`

Before:

```php
$points = new PointsCollection([new Point(
    'test_metric', // name of the measurement
    0.64, // the measurement value
    ['host' => 'server01', 'region' => 'italy'], // optional tags
    ['cpucount' => rand(1,100), 'memory' => memory_get_usage(true)], // optional additional fields
    $time->getTimestamp()
)], Database::PRECISION_SECONDS);

$container
    ->get('event_dispatcher')
    ->dispatch(InfluxDbEvent::NAME, new UdpEvent($points));
```

After:

```php
$points = [new Point(
    'test_metric', // name of the measurement
    0.64, // the measurement value
    ['host' => 'server01', 'region' => 'italy'], // optional tags
    ['cpucount' => rand(1,100), 'memory' => memory_get_usage(true)], // optional additional fields
    $time->getTimestamp()
)]);

$container
    ->get('event_dispatcher')
    ->dispatch(InfluxDbEvent::NAME, new UdpEvent($points, Database::PRECISION_SECONDS));
```

* `InfluxDbEvent::getPoints()` and `InfluxDbEvent::getPrecision()` are now `final`.

## PointsCollectionStorage

The `PointsCollectionStorage` does not exist anymore.
This class had an internal goal, this should not affect your project.
