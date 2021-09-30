<?php
namespace app\core;

use app\models\User;

class Application{

    public static string $ROOT_DIR;
    public DBModel $userClass;
    public Router $router;
    public Request $request;
    public Response $response;
    public Session $session;
    public Database $db;
    public ?DBModel $user;
    public static Application $app;
    private Controller $controller;
    public function __construct($rootPath,array $config)
    {
        $this->userClass = new $config['userClass'];
        self::$ROOT_DIR = $rootPath;
        self::$app = $this;
        $this->request = new Request();
        $this->response = new Response();
        $this->session = new Session();
        $this->router = new Router($this->request,$this->response);
        $this->db= new Database($config['db']);

        $primaryValue = $this->session->get('user');

        if($primaryValue){
            $primaryKey = $this->userClass->primaryKey();
            $this->user = $this->userClass->findOne([$primaryKey=>$primaryValue]);
        }else{
            $this->user = null;
        }

    }

    public function run(){
        echo $this->router->resolve();
    }

    public function setController(Controller $controller){
        $this->controller = $controller;
    }

    public function getController(){
        return $this->controller;
    }

    public function login(DBModel $user){
        $this->user = $user;
        $primaryKey = $user->primaryKey();
        $primaryValue = $user->{$primaryKey};
        $this->session->set('user',$primaryValue);
    }

    public function logout(){
        $this->user = null;
        $this->session->remove('user');
    }

    public function isGuest(){
        return !self::$app->user;
    }
}
