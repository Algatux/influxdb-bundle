UPGRADE FROM 1.0 to 1.1
=======================

## Deprecated services

Some services are deprecated and should not be used anymore:

* `algatux_influx_db.services_clients.influx_db_client_factory`
* `algatux_influx_db.client.udp.writer_client`
* `algatux_influx_db.client.http.writer_client`

Use `Database` services instead:

* `algatux_influx_db.database.http`
* `algatux_influx_db.database.udp`

## Deprecated class

Some class are deprecated and should not be used anymore:

* `Algatux\InfluxDbBundle\Services\Clients\InfluxDbClientFactory`
* `Algatux\InfluxDbBundle\Services\Clients\WriterClient`
* `Algatux\InfluxDbBundle\Services\Clients\ReaderClient`

## Deprecated interfaces

Some interfaces are deprecated and should not be used anymore:

* `Algatux\InfluxDbBundle\Services\Clients\Contracts\ClientInterface`
* `Algatux\InfluxDbBundle\Services\Clients\Contracts\ReaderInterface`
* `Algatux\InfluxDbBundle\Services\Clients\Contracts\WriterInterface`
