Pheign Library
-----------------

Pheign library makes writing php http client easier.

(inspired by  [OpenFeign/feign](https://github.com/OpenFeign/feign))

Installation
------------

Library can be installed with Composer. Installation is quite easy:

```bash
$ composer require airmanbzh/pheign
```

Composer will install the library to your project's `vendor/airmanbzh/pheign` directory.

Usage
------------

To make a request, you have to :

### Create a request class

````php
<?php
namespace test;

use pheign\annotation\method\GET;
use pheign\annotation\Options;
use pheign\annotation\Pheign;
use pheign\annotation\Target;

class Github
{
    /**
     * @Pheign
     *
     * @GET
     * @Target("/users/{owner}/repos")
     *
     * @Options(CURLOPT_SSL_VERIFYHOST=0, CURLOPT_SSL_VERIFYPEER=0)
     */
    public function repositories($owner){}
    
    /**
     * @Pheign
     *
     * @GET
     * @Target("/repos/{owner}/{repo}")
     *
     * @Options(CURLOPT_SSL_VERIFYHOST=0, CURLOPT_SSL_VERIFYPEER=0)
     */
    public function repositoryInformations($owner, $repo){}
}
````

### Make a request
````php
// Initialisation de pheign
$pheign = \pheign\builder\Pheign::builder()->target(\test\Github::class, 'https://api.github.com');

$result = $pheign->repositories('airmanbzh');
echo('<pre>' . htmlentities($result) . '</pre>');

$result = $pheign->repositoryInformations('airmanbzh', $repo);
echo('<pre>' . htmlentities($result) . '</pre>');
````

Annotations
------------

### Method
Namespace : pheign\annotation\method\...
Define request method

@GET, @POST, @PUT,  @DELETE

### Target
Namespace : pheign\annotation\Target

Request endpoint

````php
@Target("/search/{id}")
````

### Headers
Namespace : pheign\annotation\Headers

Define a custom header

````php
@Headers({"Content-Type : application/json", "Accept-Charset: utf-8"})
````

### Datas
Namespace : pheign\annotation\Datas
````php
@Datas(myDatas="{datas}", myId="{id}")
````

### Options
Namespace : pheign\annotation\Options

````php
@Options(CURLOPT_SSL_VERIFYHOST=0, CURLOPT_SSL_VERIFYPEER=0)
````

To configure AOP
------------
````php
<?php
$loader = require_once(__DIR__ . '/../vendor/autoload.php');

$applicationAspectKernel = \pheign\kernel\PheignKernel::getInstance();
$applicationAspectKernel->init(array(
    'debug' => true,
    'appDir' => __DIR__ . '/../private', // The directory where you find your request class
    'cacheDir' => __DIR__ . '/../cache',
    'excludePaths' => array(
        __DIR__ . '/../vendor'
    )
));
````

More about Goaop and its configuration : [goaop/framework](https://github.com/goaop/framework)