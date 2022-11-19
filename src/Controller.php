<?php

declare(strict_types=1);

namespace App;

use App\Exception\NotFoundException;
use App\Request;

include_once('./src/view.php');
require_once('./config/config.php');
require_once('./src/database.php');

class Controller
{
    const DEFAULT_ACTION = 'list';
    private static array $configuration = [];
    private Database $database;
    private View $view;
    private Request $request;
    public function __construct(Request $request)
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


        switch ($this->action()) {
            case 'create':
                $page = 'create';
                if ($this->request->hasPost()) {
                    $noteData = [
                        'title' => $this->request->postParam('title'),
                        'description' => $this->request->postParam('description'),
                    ];
                    $this->database->createNote($noteData);
                    header('Location: /?before=created');
                    exit;
                }
                break;
            case 'show';
                $page = 'show';
                $noteId = (int) $this->request->getParam('id');
                if (!$noteId) {
                    header('location: /?error=missingNoteId');
                    exit;
                }
                try {
                    $note = $this->database->getNote($noteId);
                } catch (NotFoundException $e) {
                    header('Location: /?error=noteNotFound');
                    exit;
                }
                $viewParams = [
                    'title' => 'Moja Notatka',
                    'description' => 'Opis',
                    'Note' => $note,
                ];
                break;
            default:
                $page = 'list';

                $viewParams = [
                    'notes' => $this->database->getNotes(),
                    'before' => $this->request->getParam('before'),
                    'error' => $this->request->getParam('error'),
                ];
                break;
        }

        $this->view->render($page, $viewParams);
    }
    private function action(): string
    {
        return $this->request->getParam('action', self::DEFAULT_ACTION);
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
