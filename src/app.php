<?php

use Silex\Application;
use Silex\Provider\HttpCacheServiceProvider;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\MonologServiceProvider;
use Silex\Provider\ServiceControllerServiceProvider;
use Silex\Provider\SecurityServiceProvider;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\ServicesLoader;
use App\Security\TokenAuthenticator;
use App\RoutesLoader;
use Carbon\Carbon;

//require 'password.php';

date_default_timezone_set('Europe/London');

$app->register(new ServiceControllerServiceProvider());

$app->register(new DoctrineServiceProvider(), array(
  "db.options" => $app["db.options"]
));

$app->register(new HttpCacheServiceProvider(), array("http_cache.cache_dir" => ROOT_PATH . "/storage/cache",));
$app->register(new MonologServiceProvider(), array(
    "monolog.logfile" => ROOT_PATH . "/storage/logs/" . Carbon::now('Europe/London')->format("Y-m-d") . ".log",
    "monolog.level" => $app["log.level"],
    "monolog.name" => "application"
));

//load services
$servicesLoader = new App\ServicesLoader($app);
$servicesLoader->bindServicesIntoContainer();

//load routes
$routesLoader = new App\RoutesLoader($app);
$routesLoader->bindRoutesToControllers();

//accepting JSON & Checking credentials
$app->before(function (Request $request) {
  $token = $request->headers->get('X-AUTH-TOKEN');
  $db = new SQLite3('app.db');
  $users = array(
    'pauline' => array('role' => 'ROLE_USER', 'secret'=> '$2a$06$hCpAM4n7GD5pChZecVMDzOsDk3b9/QiDrkWzXnorH7YykC3ZbSfga'),
    'admin' => array('role' => 'ROLE_ADMIN', 'secret'=> '$2a$06$hCpAM4n7GD5pChZecVMDzOsDk3b9/QiDrkWzXnorH7YykC3ZbSfga'),
    'container' => array('role' => 'ROLE_CONTAINER', 'secret'=> '$2a$06$hCpAM4n7GD5pChZecVMDzOsDk3b9/QiDrkWzXnorH7YykC3ZbSfga', 'name'=>'Bercy'),
  );
  //Authentication
    if($token){
      echo "Authentification Attempt \n";
      if (false === strpos($token, ':')) {
        throw new Exception("Authentication Invalid", 1);
      }else{
        list($username, $secret) = explode(':', $token, 2);
        //echo $username," - ", $secret, "\n";
        //find username in base // TODO in users db

        $user = $users[$username];
        /*$results = $db->query("SELECT * FROM users WHERE username=$username");
        echo $results;
        echo $db;*/
        //echo  $users[$username], "\n";
        if($user){
          // match secret
          //echo $user['secret'], " - ", $secret, "\n";
          if(password_verify($secret, $user['secret'])){
            echo "Authentified \n";
          }else{
            echo "Password verification fails \n";
            throw new Exception("Authentication Invalid", 1);
          }
        }else{
          throw new Exception("Authentication Invalid", 1);
        }
      };
      //echo "Done \n";
    }else {
      echo "No attempt \n";
      throw new Exception("Authentication Invalid", 1);
    }
//Additionnal ROLE check for certain routes
    $restricted = array(
      'POST' => array('role'=>'ROLE_ADMIN'),
      'DELETE' => array('role'=>'ROLE_ADMIN'),
      'PUT' => array('role'=>'ROLE_CONTAINER')
    );

    if($restricted[$_SERVER['REQUEST_METHOD']]){
      //check that the user has the proper role
      list($username, $secret) = explode(':', $token, 2);
      $user = $users[$username];
      if($user['role']===$restricted[$_SERVER['REQUEST_METHOD']]['role']){
        echo "Authorized \n";
      }else{
        echo "NOT Authorized \n";
        throw new Exception("Action Denied", 1);
      }
    }

    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

$app->error(function (\Exception $e, $code) use ($app) {
    $app['monolog']->addError($e->getMessage());
    $app['monolog']->addError($e->getTraceAsString());
  return new JsonResponse(array("statusCode" => $code, "message" => $e->getMessage()/*, "stacktrace" => $e->getTraceAsString()*/));
});

return $app;
