<?php

declare(strict_types=1);

namespace App\Controller;

use App\Database;
use App\Request;
use App\View;

abstract class AbstractController
{
    const DEFAULT_ACTION = 'list';
    protected static array $configuration = [];
    protected Database $database;
    protected View $view;
    protected request $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->view = new View();
        $this->database = new Database(self::$configuration);
    }

    public static function initConfiguration(array $configuration): void
    {
        self::$configuration = $configuration;
    }

    public function run(): void
    {
        $action = $this->action() . 'Action';
        if (!method_exists($this, $action)) {
            $action = self::DEFAULT_ACTION . 'Action';
        }
        $this->$action();
    }
    protected function action(): string
    {
        return $this->request->getParam('action', self::DEFAULT_ACTION);
    }
    protected function redirect(string $to, array $params): void
    {
        $location = $to;
        if (count($params)) {
            $queryParams = [];
            foreach ($params as $key => $value) {
                $queryParams[] = urlencode($key) . '=' . urlencode($value);
            }
            $queryParams = implode('&', $queryParams);
            $location .= '?' . $queryParams;
        }
        header("Location: $location");
        exit;
    }
}
