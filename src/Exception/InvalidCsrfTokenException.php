<?php
namespace Symfony\Component\Security\Core\Exception;

class InvalidCsrfTokenException extends AuthenticationException
{
    /**
     * {@inheritdoc}
     */
    public function getMessageKey(): string
    {
        return 'Invalid CSRF token.';
    }
}
?>