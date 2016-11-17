# Order Rest Service

## End Points


### API DOC URI :

run the following command in you project root to create json file for swagger
```
./vendor/bin/swagger docs/api/ -o public/api/
```
then open following file 
```
public/dist/index.html
```

and change the following part to fit your domain :
```
url = "http://192.168.21.46:7000/api/swagger.json";
```


```
http://domain/dist
```

#### Show an Order Item 
```
GET http://domain/order/{id}
```

#### List of orders
```
GET    http://domain/order
```

#### Create New Order   
```
POST   http://domain/order
```

#### Update an existing Order Document 
```
PUT    http://domain/order/{id}
```

#### Change an order status 
```
PATCH  http://domain/order/{id}
```

#### Delete an Order Item
```
DELETE http://domain/order/{id}
```