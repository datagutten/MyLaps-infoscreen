<?php


namespace datagutten\amb\infoScreen;


use datagutten\tools\files\files;
use Twig;

class infoScreen
{

    /**
     * @var array Configuration parameters
     */
    protected $config;
    /**
     * @var array Database configuration
     */
    protected $config_db;
    /**
     * @var string Avatar image folder
     */
    public $avatar_folder;
    public $web_root;
    public $project_root;
    /**
     * @var Twig\Environment
     */
    public $twig;

    /**
     * infoScreen constructor.
     */
    function __construct()
    {
        $this->config = require __DIR__ . '/../config.php';
        $this->config_db = require __DIR__ . '/../config_db.php';
        $this->avatar_folder = files::path_join(__DIR__, '..', 'avatars');

        if (!file_exists($this->config['avatar_folder']))
            mkdir($this->config['avatar_folder']);

        if (!empty($_SERVER['SCRIPT_NAME']))
            $this->web_root = dirname($_SERVER['SCRIPT_NAME']);
        $this->project_root = dirname(dirname(__FILE__));

        $loader = new Twig\Loader\FilesystemLoader(array(__DIR__ . '/../templates'), __DIR__);
        $this->twig = new Twig\Environment($loader, array('debug' => false, 'strict_variables' => true));
    }

    /**
     * Render a twig template
     * @param $template
     * @param $context
     * @return string
     * @throws Twig\Error\LoaderError
     * @throws Twig\Error\RuntimeError
     * @throws Twig\Error\SyntaxError
     */
    public function render(string $template, array $context): string
    {
        $context['root'] = $this->web_root;
        return $this->twig->render($template, $context);
    }
}