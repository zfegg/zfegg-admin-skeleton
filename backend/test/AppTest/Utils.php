<?php


namespace AppTest;

use Mezzio\Authentication\UserInterface;

class Utils
{

    public static function initSession(array $user = []): array
    {
        return $_SESSION[UserInterface::class] = [
            'identifier' => 1,
            'user' => [
                'email' => 'test@test.com',
                'real_name' => '测试',
                'admin' => false,
            ],
        ];
    }
}
