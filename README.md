# Api Boilerplate built with Silex

# NOTICE: THIS IS A DEMO ONLY, IT IS NOT YET PERFECT #

## Features

- create an Endpoint for a Doctrine Entity with POST, PUT, DELETE, GET SINGLE, GET MULTIPLE (with filters, sorting ...) in 2min (i swear :D)
- contains default controller which fits most needs
- uses Doctrine ORM with Annotations
- uses Symfony Validations with Annotation
- uses JMS Serializer with Annotations for advanced serialization
- uses EntityBuilder (builds an entity from json)
- uses ResponseBuilder (build JSON Response)
- uses parameters.yml for settings
- handles creation on related Doctrine Entities
- uses ORM cache
- cli is configured
 
## How to install

- checkout this repository
- update config/parameters.yml to your needs
- get composer.phar from https://getcomposer.org/
- `php composer.phar install`
- `vendor/bin/doctrine orm:schema:create`
- `php -S 0.0.0.0:8080 index.php`


### create your first product
This creates a product with a mapped Price and a mapped Category

`
curl -X "POST" "http://localhost:8080/api/v1/products" \
     -H "Content-Type: application/json; charset=utf-8" \
     -d "{\"name\":\"hello kitty\",\"categories\":[{\"name\":\"Puppies\"}],\"price\":{\"value\":198}}"
`


### get a your list of products
notice we order by id desc and limit the query to 15 (your can also filter on specific fields)

`
curl -X "GET" "http://localhost:8080/api/v1/products?limit=15&offset=0&order=-id"
`