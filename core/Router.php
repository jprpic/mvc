<?php
namespace app\core;

class Router{
    protected array $routes = [];
    public Request $request;
    public Response $response;

    public function __construct(Request $request,Response $response)
    {
        $this->request=$request;
        $this->response = $response;
        
    }
    public function get($path,$callback)
    {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path,$callback)
    {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve(){
        $path = $this->request->getPath();
        $method = $this->request->method();
        $callback = $this->routes[$method][$path] ?? false;
        
        if(!$callback){
            $this->response->setStatusCode(404);
            return $this->renderView('_404');
        }

        // if callback is a view ex. contact.php
        if(is_string($callback)){
            return $this->renderView($callback);
        }
        if(is_array($callback)){
            $callback[0] = new $callback[0]();
            Application::$app->setController($callback[0]);
        }
        return call_user_func($callback,$this->request);
    }

    public function renderView($view,$params = []){
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view, $params);
        return str_replace('{{content}}',$viewContent,$layoutContent);
    }

    public function renderContent($viewContent){
        $layoutContent = $this->layoutContent();
        return str_replace('{{content}}',$viewContent,$layoutContent);
    }

    protected function layoutContent(){
        $layout = Application::$app->getController()->getLayout();
        ob_start();
        include_once Application::$ROOT_DIR."/layouts/$layout.php";
        return ob_get_clean();
    }

    protected function renderOnlyView($view, $params){
        foreach($params as $key => $value){
            $$key = $value;
        }
        ob_start();
        include_once Application::$ROOT_DIR."/views/$view.php";
        return ob_get_clean();
    }


}

?>