# Plugin MVC framework

- Start
- Model
- View
- Controller
- Router
- Database

## Start

This plugin is saved in a private repository in github. Clone the repository using the code below

```
git clone https://username:password@github.com/writerscentre.git
```

Once all the files has been downloaded, install all dependencies via cmd or terminal

```
npm install
composer install
```

  
## Model

Location: `lib\model`


To access the table via model, extend the `CoreModel` class and add values to the protected properties of the `CoreModel` class

```
protected $table = "table_name";

protected $primary_key = "primary_key";
```

### Model Methods

With the model methods, you can create a query builder. 

#### Retrieve

`getAll()` - Retrieve all model data <br />
`get( ...$columns)` - get a certain resource in the database with the column provided. <br />
`find( $id )` - find the resource that matches the ID given

#### Update


#### Delete


## View

 

## Controller

## Router

## Database