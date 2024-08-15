<?php


namespace datagutten\amb\infoScreen;


use datagutten\amb\laps\lap_timing;
use datagutten\tools\files\files;
use RuntimeException;
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
    public lap_timing $timing;
    public TransponderInfo $transponderInfo;

    /**
     * infoScreen constructor.
     * @param array $config
     * @param ?string $decoder_id Decoder ID
     */
    function __construct(array $config, string $decoder_id = null)
    {
        $this->config = $config;
        if (!empty($this->config['infoscreen']) && !empty($this->config['infoscreen']['avatar_folder']))
            $this->avatar_folder = $this->config['infoscreen']['avatar_folder'];
        else
            $this->avatar_folder = files::path_join(__DIR__, '..', 'avatars');

        if (!file_exists($this->avatar_folder))
            mkdir($this->avatar_folder);

        if (!empty($_SERVER['SCRIPT_NAME']))
            $this->web_root = dirname($_SERVER['SCRIPT_NAME']);
        if ($this->web_root == '/')
            $this->web_root = '';
        $this->project_root = dirname(dirname(__FILE__));

        $loader = new Twig\Loader\FilesystemLoader(array(__DIR__ . '/../templates'), __DIR__);
        $this->twig = new Twig\Environment($loader, array('debug' => false, 'strict_variables' => true));
        if ($decoder_id)
            $this->timing = new LapTimingWeb($config, $decoder_id);

        PassingWeb::$avatar_folder = $this->avatar_folder;
        PassingWeb::$web_root = $this->web_root;
        LapWeb::$avatar_folder = $this->avatar_folder;
        LapWeb::$web_root = $this->web_root;

        $this->transponderInfo = new TransponderInfo($config);
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
        $context['config'] = $this->config['infoscreen'];
        return $this->twig->render($template, $context);
    }

    /**
     * @return LapWeb[]
     */
    public function laps(int $lap_count_limit = 90, int $lap_time_limit = 60, int $time_before = 0): array
    {
        $laps = [];
        $laps_db = $this->timing->laps($lap_count_limit, $lap_time_limit, $time_before);
        $stats = $this->timing->stats($laps_db);
        foreach ($stats as $lap)
        {
            $lap_obj = new LapWeb($lap);
            try
            {
                $lap_obj = $this->transponderInfo->set_transponder($lap_obj);
                $laps[] = $lap_obj;
            }
            catch (RuntimeException)
            {
                continue;
            }
        }
        return $laps;
    }
}