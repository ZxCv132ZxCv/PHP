<?php

declare(strict_types=1);

namespace App;

include_once('./src/view.php');
require_once('./config/config.php');
require_once('./src/database.php');

class Controller
{
    const DEFAULT_ACTION = 'list';
    private static array $configuration = [];
    private Database $database;
    private View $view;
    private array $request;
    public function __construct(array $request)
    {
        $this->request = $request;
        $this->view = new view();
        $this->database = new Database(self::$configuration);
    }

    public static function initConfiguration(array $configuration): void
    {
        self::$configuration = $configuration;
    }

    public function run(): void
    {
        $viewParams = [];

        switch ($this->action()) {
            case 'create':
                $page = 'create';
                $created = false;
                $data = $this->getRequestPost();
                if (!empty($data)) {
                    $created = true;
                    $viewParams = [
                        'title' => $data['title'],
                        'description' => $Data['description'],
                    ];
                    $this->database->createNote($viewParams);
                    header('Location: /');
                }
                $viewParams['created'] = $created;
                break;
            default:
                $page = 'list';
                $viewParams['resultList'] = 'Wyświetlamy listę notatek';
                break;
        }

        $this->view->render($page, $viewParams);
    }
    private function action(): string
    {
        $data = $this->getRequestget();
        return $data['action'] ?? self::DEFAULT_ACTION;
    }

    private function getRequestPost(): array
    {
        return $this->request['post'] ?? [];
    }

    private function getRequestget(): array
    {
        return $this->request['get'] ?? [];
    }
}
