<?php

namespace App;

use Silex\Application;
use App\Security\Authenticator;

class ServicesLoader
{
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function bindServicesIntoContainer()
    {
        $this->app['notes.service'] = function() {
            return new Services\NotesService($this->app["db"]);
        };
        $this->app['containers.service'] = function() {
            return new Services\ContainersService($this->app["db"]);
        };
    }
}
