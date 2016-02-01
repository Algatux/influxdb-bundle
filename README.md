# influxdb-bundle

Integration bundle for writing to influxdb

[![PHP Version](https://img.shields.io/badge/PHP-%3E%3D7.0-blue.svg)](https://img.shields.io/badge/PHP-%3E%3D7.0-blue.svg) [![Build Status](https://travis-ci.org/Algatux/influxdb-bundle.svg?branch=master)](https://travis-ci.org/Algatux/influxdb-bundle) [![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/Algatux/influxdb-bundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/Algatux/influxdb-bundle/?branch=master) [![Code Coverage](https://scrutinizer-ci.com/g/Algatux/influxdb-bundle/badges/coverage.png?b=master)](https://scrutinizer-ci.com/g/Algatux/influxdb-bundle/?branch=master)
[![Latest Stable Version](https://poser.pugx.org/algatux/influxdb-bundle/v/stable)](https://packagist.org/packages/algatux/influxdb-bundle) [![Latest Unstable Version](https://poser.pugx.org/algatux/influxdb-bundle/v/unstable)](https://packagist.org/packages/algatux/influxdb-bundle) [![License](https://poser.pugx.org/algatux/influxdb-bundle/license)](https://packagist.org/packages/algatux/influxdb-bundle)

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

- add support for credentials login
- add support to select database
- ...

