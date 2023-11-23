<?php

namespace App\Service;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class CodeGeneratorDecorator
{


    public function __construct(
        private CodeGenerator $codeGenerator,
        private EmailService $emailService,
        private AuthenticationUtils $authenticationUtils)
    {
    }

    // Generate code and send it to user
    public function generate(): string
    {
        $authenticatedUser = $this->authenticationUtils->getLastUsername();
        $code = $this->codeGenerator->generate();
        if ($authenticatedUser) {
            $this->emailService->sendEmail($authenticatedUser, $code);
        }
        return $code;
    }
}