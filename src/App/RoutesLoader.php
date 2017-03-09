<?php

namespace App;

use Silex\Application;

class RoutesLoader
{
    private $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->instantiateControllers();

    }

    private function instantiateControllers()
    {
        $this->app['notes.controller'] = function() {
            return new Controllers\NotesController($this->app['notes.service']);
        };
        $this->app['containers.controller'] = function() {
            return new Controllers\ContainersController($this->app['containers.service']);
        };
    }

    public function bindRoutesToControllers()
    {
        $api = $this->app["controllers_factory"];

        $api->get('/containers', "containers.controller:getAll");
        $api->get('/containers/{id}', "containers.controller:getOne");
        $api->post('/containers', "containers.controller:save");
        $api->put('/containers/{id}', "containers.controller:update");
        $api->delete('/containers/{id}', "containers.controller:delete");

        $this->app->mount($this->app["api.endpoint"].'/'.$this->app["api.version"], $api);
    }
}
