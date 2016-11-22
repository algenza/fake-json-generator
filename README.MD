# Fake Json Generator
[![Build Status](https://travis-ci.org/algenza/fake-json-generator.svg?branch=master)](https://travis-ci.org/algenza/fake-json-generator)

## Installation
```sh
composer require algenza/fake-json-generator 
```

## How to use?
```sh
use Algenza\Fjg\Generator;

$schemaPath = 'path_to_schema';
$jsonPath = 'path_to_generated_json';

$generator = new Generator($schemaPath, $jsonPath);
$generator->run();
```
## Lisence
MIT