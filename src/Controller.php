<?php

declare(strict_types=1);

namespace App;

use App\Exception\NotFoundException;

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


        switch ($this->action()) {
            case 'create':
                $page = 'create';
                $data = $this->getRequestPost();
                if (!empty($data)) {
                    $noteData = [
                        'title' => $data['title'],
                        'description' => $data['description'],
                    ];
                    $this->database->createNote($noteData);
                    header('Location: /?before=created');
                    exit;
                }
                break;
            case 'show';
                $page = 'show';
                $data = $this->getRequestGet();
                $noteId = (int) $data['id'] ?? null;
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
                $data = $this->getRequestGet();
                $viewParams = [
                    'notes' => $this->database->getNotes(),
                    'before' => $data['before'] ?? null,
                    'error' => $data['error'] ?? null,
                ];
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
