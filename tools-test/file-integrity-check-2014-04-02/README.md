integrity-md5-class
===================

Comprueba los hashes MD5 de un árbol de directorios para ver si han sido modificados.

Uso
===

Podemos generar un archivo de hashes MD5 usando el metodo *getMD5Hashes()* este metodo generara un archivo con la fecha actual si no se especifica el nombre del archivo.

```php
include 'libraries/integrity.md5.class.php';
$integrity = new integrity('path/to/folder/');
$integrity->getMD5Hashes(); // archivo por defecto, por ejemplo: 20140316151603.md5
// or
$integrity->getMD5Hashes('MD5Check.md5'); // archivo personalizado.
```

Para realizar la comprobacion debemos usar el metodo *checkMD5Hashes()* al cual le pasaremos el archivo con los hashes MD5 con los que deseemos realizar la comparacion.

```php
include 'libraries/integrity.md5.class.php';
$integrity = new integrity('path/to/folder/');
$files = $integrity->checkMD5Hashes('MD5Check.md5');
var_dump($files);
```
Nos devolvera un array con la lista de los archivos que an sufrido cambios.

