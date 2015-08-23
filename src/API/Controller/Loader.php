<?php

namespace API\Controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

class Loader
{
    public function getController(Request $request, Application $app)
    {
        extract($this->defaultsParams($request->attributes->all()));

        $controller = 'API\Controller\\' . ucfirst($controller) . "Controller";
        
        
        return call_user_func([new $controller($app, $request), $action], $params);        
    }

    public function defaultsParams(Array $params)
    {
        $params = $params['_route_params'];

        if (empty($params['controller']))
            $params['controller'] = 'index';

        $data['controller'] = $params['controller'];
        unset($params['controller']);

        if (empty($params['action']))
            $params['action'] = 'index';

        $data['action'] = $params['action'];
        unset($params['action']);

        $data['params'] = $params;

        return $data;
    }
}