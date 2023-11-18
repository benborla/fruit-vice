# Fruity Vice Application

Fruity Vice App is a simple monorepo application that has the following directories

- api - handles the RESTful API
- app - handles the UI and consumed the data from the API
- docker (TODO)

## Requirements:
- PHP 8.1+
- Node v16+
- NPM v8+
- MySQL 5.6+ or 8

## How to use
Clone this project https://github.com/benborla/fruit-vice

## Installation
### API
Follow the following commands:
- `cd ./api`
- `cp .env.dist .env`
- `composer install`
- `composer run setup`
- `php bin/console fruits:fetch --truncate=false`
- `composer run serve`

### APP
Follow the following commands:
- `cd ./app`
- `npm install`
- `cp .env.development .env`
- `npm run dev`
- Access the provided URL from `npm run dev` command

## Available commands

Below is the command available for Fruity Vice

Usage: `php bin/console fruits:fetch`

| Command | Description |
| ------ | ------ |
| fruits:fetch | Sync database from API source, optional parameter `--truncate={bool}` |



## API Endpoints


### Get all `fruits` resources

Method: **GET**

Endpoint: `/`

Parameters:
- page: for pagination, which page should it return
- size: how many rows it should return
- order_by: allowed values are **name** and **family**
- search: search criteria
- direction: allowed values are **asc** and **desc**

Sample Usage: `/?size=5&page=2`


### Get `fruit` resource

Method: **GET**

Endpoint: `/fruit/:id `

Parameters: id


### Create `fruit` resource

Method: **POST** or **PUT**

Endpoint: `/fruit`

Sample Payload:
```json
{
    "name": "test_name",
    "genus": "test_genus",
    "family": "test_family",
    "fruit_order": "test_fruit_order",
    "carbohydrates": 0,
    "fat": 0,
    "protein": 0,
    "sugar": 11,
    "calories": 1555.1
}
```


### Update `fruit` resource

Method: **POST** or **PATCH**

Endpoint: `/fruit/:id `

Parameters: id

Sample Payload:
```json
{
    "family": "test_family_updated",
    ...
}
```
*Only provide the properties that needs to be updated.*


### Delete `fruit` resource

Method: **DELETE**

Endpoint: `/fruit/:id `

Parameters: id


### Get all `favorites` resources

Method: **GET**

Endpoint: `/favorites`


### Add `fruit` in `favorite` resource

Method: **POST**

Endpoint: `/favorites/:fruitId`

Parameter: **fruitId**


### Remove `fruit` in `favorite` resource

Method: **DELETE**

Endpoint: `/favorite/:fruitId `

Parameters: fruitId
