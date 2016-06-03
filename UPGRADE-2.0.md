UPGRADE FROM 1.x to 2.0
=======================

## Deprecations

All the deprecated code introduced on 1.x is removed on 2.0.

Please read [1.x](UPGRADE-1.x.md) upgrade guides for more information.

See also the [diff code](https://github.com/sonata-project/SonataAdminBundle/compare/1.x...2.0.0).

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
