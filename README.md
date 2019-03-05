# WEBII-HW02-Routing
## Including

Include Autoloader
```php
require_once __DIR__ . '/Services/autoloader.php';
```

Import class
```php
use web2hw\Router;
```

Create routes file 

The routes file is a json file, where object key is route unique name. 

Each route must have **path**, **method** and **action** keys. Homepage route example:
```json
{
  "homepage": {
    "path": "/",
    "method": "GET",
    "action": "web2hw\\DefaultController::index"
  }
}
```
