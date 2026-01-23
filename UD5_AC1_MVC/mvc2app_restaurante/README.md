# Proyecto con namespaces MRS

Cambios aplicados:
- Se elimina `Acme\IntranetRestaurante\...` y se unifica a:
  - `MRS\Librerias\` (Core, Controlador, Db)
  - `MRS\Controladores\` (Carrito, Restaurante, Paginas)
  - `MRS\Modelos\` (Modelos + entidades)
  - `MRS\Tools\` (Mailer reutilizable)
- Se actualizan todas las referencias (use, strings FQCN, docblocks) que contenían `Acme\IntranetRestaurante`.

## Composer (mínimo)
En la raíz, usa el `composer.json` incluido y ejecuta:

```bash
composer require phpmailer/phpmailer ramsey/uuid
composer require --dev phpunit/phpunit:^10.5
composer dump-autoload -o
```

## Arranque (AMPPS / Apache o servidor embebido)
- Entry point: `public/index.php`
- Si usas servidor embebido:
  ```bash
  php -S localhost:8000 -t public public/router.php
  ```

## Estructura
- `app/` contiene MVC (controladores, modelos, librerías, vistas)
- `public/` contiene el front-controller y estáticos (css/js)
- `tests/` contiene PHPUnit
