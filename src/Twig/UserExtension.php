<?php

namespace App\Twig;

use App\Service\UserService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class UserExtension extends AbstractExtension
{
    /**     
     *
     * @param UserService $userService
     */
    public function __construct(private UserService $userService)
    {
        $this->userService = $userService;            
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('hasAccess', [$this, 'hasAccess']),
        ];
    }

    /**     
     *
     * @return boolean
     */
    public function hasAccess(): bool
    {
        return $this->userService->hasAccess();        
    }
}