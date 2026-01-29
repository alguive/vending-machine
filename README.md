# Vending Machine
---

## Start the environment
```docker-compose up -d --build```


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
