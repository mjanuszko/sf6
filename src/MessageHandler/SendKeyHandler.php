<?php

namespace App\MessageHandler;

use App\Message\SendKey;
use App\Repository\UserRepository;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class SendKeyHandler
{
    public function __construct(
        private UserRepository $userRepository,
    )
    {
    }

    public function __invoke(SendKey $message)
    {
        $user = $this->userRepository->find($message->getUserId());
        if ($user) {
            $this->sendEmail($user->getEmail(), $this->getKey());
        }
    }

    private function getKey(): int
    {
        sleep(5);
        return random_int(1000, 9999);
    }

    private function sendEmail(string $email, int $key)
    {
        file_put_contents('email-' . $key . '.txt', "$email: $key\n", FILE_APPEND);
    }
}