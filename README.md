# Vending Machine
---

## Start the environment
```
docker-compose up -d --build
docker-compose exec php composer install
```

## API Endpoints
```
http://localhost:8080/api/vending-machine/
```

```
  VendingMachineController
  │
  ├── insertCoin()     →  POST   /api/vending-machine/coins
  ├── returnCoins()    →  DELETE /api/vending-machine/coins
  ├── purchase()       →  POST   /api/vending-machine/purchase
  ├── service()        →  PUT    /api/vending-machine/service
  └── status()         →  GET    /api/vending-machine
```
## How to use
In the root directory there's the Postman Collection to import it and use it.

