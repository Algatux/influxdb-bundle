# influxdb-bundle

Integration bundle for writing and reading to influxdb

[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D7.0-blue.svg)](https://img.shields.io/badge/PHP-%3E%3D7.0-blue.svg) [![Build Status](https://travis-ci.org/Algatux/influxdb-bundle.svg?branch=master)](https://travis-ci.org/Algatux/influxdb-bundle) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Algatux/influxdb-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Algatux/influxdb-bundle/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/Algatux/influxdb-bundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Algatux/influxdb-bundle/?branch=master)

### install using composer 

    composer require algatux/influxdb-bundle dev-master

### configuration

in your config.yml add:
    
    influx_db:
      host: '{your influxdb host address}' (default localhost)
      database: '{your influxdb host address}' (default udp)
      udp_port: '{your influxdb udp port}' (default 4444)
      http_port: '{your influxdb http port}' (default 8086)
    

### todo:

- implementation of ReaderClient service
- implementation of event listener to admit event driven data writings
- add support for credentials login
- add support for database selection
- ...

