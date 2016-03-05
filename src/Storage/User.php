<?php declare(strict_types=1);

namespace AerysPlayground\Storage;

use function Amp\File\exists;
use function Amp\File\get;
use function Amp\File\put;

class User
{
    private $filename;

    public function __construct(string $filename)
    {
        $this->filename = $filename;
    }

    public function getAll(): \Generator
    {
        if (!yield exists($this->filename)) {
            return [];
        }

        $users = yield get($this->filename);

        return json_decode($users, true);
    }

    public function get(string $username): \Generator
    {
        $users = yield from $this->getAll();

        return $users[strtolower($username)];
    }

    public function exists(string $username): \Generator
    {
        $users = yield from $this->getAll();

        return array_key_exists(strtolower($username), $users);
    }

    public function add(string $username, string $password): \Generator {
        if (yield from $this->exists($username)) {
            return;
        }

        $users = yield from $this->getAll();

        $users[strtolower($username)] = [
            'username'  => $username,
            'password'  => password_hash($password, PASSWORD_DEFAULT, ['cost' => 14]),
            'xp'        => 0,
            'positionX' => 1,
            'positionY' => 1,
        ];

        yield put($this->filename, json_encode($users));
    }

    public function logIn(string $username, string $password): \Generator
    {
        if (!yield from $this->exists($username)) {
            return false;
        }

        $user = yield from $this->get($username);

        return password_verify($password, $user['password']);
    }
}
