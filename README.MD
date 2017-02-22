# Fake Json Generator
[![Build Status](https://travis-ci.org/algenza/fake-json-generator.svg?branch=master)](https://travis-ci.org/algenza/fake-json-generator)

## Installation
```sh
composer require algenza/fake-json-generator 
```

## How to use?
Make schema
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
                    "value": "faker.randomNumber"
                    "unique": true,
                },
                "name": {
                    "value": "faker.name"
                },
                "email": {
                    "value": "faker.email"
                },
                "hallo": {
                    "value": "hallo"
                },
                "phone": {
                    "type": "array",
                    "minimum": 1,
                    "maximum": 1,
                    "items": {
                        "value": "faker.e164PhoneNumber"
                    }
                }
            }
        },
        "Companies": {
            "type": "object",
            "minimum": 20,
            "maximum": 20,
            "properties": {
                "id": {
                    "definition" : "uniqueId"
                },
                "name": {
                    "value": "faker.company"
                    "unique": true
                },
                "suffix": {
                    "value": "faker.companySuffix"
                },
                "address": {
                    "value": "faker.streetName"
                },
                "type": {
                    "value": "Type A"
                },
                "phone": {
                    "type": "array",
                    "minimum": 1,
                    "maximum": 1,
                    "items": {
                        "value": "faker.e164PhoneNumber"
                    }
                }
            }
        }
    },
    "required": ["users","companies"],
    "definitions" : {
        "uniqueId" : {
            "value": "faker.randomNumber",
            "unique": true          
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

