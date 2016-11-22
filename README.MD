# Fake Json Generator
[![Build Status](https://travis-ci.org/algenza/fake-json-generator.svg?branch=master)](https://travis-ci.org/algenza/fake-json-generator)

## Installation
```sh
composer require algenza/fake-json-generator 
```

## How to use?
Make simple schema
```sh
{
    "type": "object",
    "properties": {
        "users": {
            "type": "object",
            "minimum": 102,
            "maximum": 101,
            "properties": {
                "id": {
                    "type": "integer",
                    "unique": true,
                    "value": "faker.randomNumber"
                },
                "name": {
                    "type": "string",
                    "value": "faker.name"
                },
                "email": {
                    "type": "string",
                    "value": "faker.email"
                },
                "hallo": {
                    "type": "string",
                    "value": "hallo"
                },
                "phone": {
                    "type": "array",
                    "minimum": 1,
                    "maximum": 1,
                    "items": {
                        "type": "string",
                        "value": "faker.e164PhoneNumber"
                    }
                }
            },
            "required": ["id", "name", "email"]
        }
    }
}
```
then generate
```sh
use Algenza\Fjg\Generator;

$schemaPath = 'path_to_schema';
$jsonPath = 'path_to_generated_json';

$generator = new Generator($schemaPath, $jsonPath);
$generator->run();
```
## Lisence
MIT

