<?php

declare(strict_types=1);

namespace App\Listener;

use App\Entity\User;
use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class TokenCreatedListener
{
    public function __invoke(JWTCreatedEvent $event): void
    {
        /** @var User $user */
        $user = $event->getUser();

        $payload = $event->getData();
        $payload['telegramLogin'] = $user->getTelegramLogin();
        $payload['championPartnersLogin'] = $user->getChampionPartnersLogin();

        $event->setData($payload);
    }
}
