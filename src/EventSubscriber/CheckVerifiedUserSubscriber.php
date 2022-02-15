<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;

class CheckVerifiedUserSubscriber implements EventSubscriberInterface
{

    public function onCheckPassport(CheckPassportEvent $event){
        $passport = $event->getPassport();

        $user = $passport->getUser();
        if (!$user->IsVerified()){
            throw new CustomUserMessageAuthenticationException('Veuillez verifier votre compte avant de vous connecter');
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            CheckPassportEvent::class => ['onCheckPassport', -10]
        ];
    }
}