<?php
namespace App\Error;

use Cake\Error\ExceptionRenderer;
use Cake\Http\Response;
use Cake\Http\Exception\NotFoundException;
use Cake\Routing\Router;
use Cake\Core\Configure;
use Cake\Http\Exception\MissingControllerException;

class AppExceptionRenderer extends ExceptionRenderer
{
    public function render()
    {
        $exception = $this->error;

        if ($exception instanceof MissingControllerException) {
            // Return a 404 Not Found response with a custom error page
            $response = new Response();
            $response = $response->withStatus(404);

            $this->controller->response = $response;
            $this->controller->set('message', 'The page you are looking for does not exist.');
            $this->controller->render('/Error/error404');
            return $this->controller->response;
        }

        // For other exceptions, use the default rendering
        return parent::render();
    }
}
