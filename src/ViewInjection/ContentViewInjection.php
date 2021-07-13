<?php

declare(strict_types=1);

namespace App\ViewInjection;

use App\ApplicationParameters;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\User\CurrentUser;
use Yiisoft\Yii\View\ContentParametersInjectionInterface;

final class ContentViewInjection implements ContentParametersInjectionInterface
{
    private ApplicationParameters $applicationParameters;
    private UrlGeneratorInterface $url;
    private CurrentUser $user;

    public function __construct(
        ApplicationParameters $applicationParameters,
        UrlGeneratorInterface $url,
        CurrentUser $user
    ) {
        $this->applicationParameters = $applicationParameters;
        $this->url = $url;
        $this->user = $user;
    }

    public function getContentParameters(): array
    {
        return [
            'applicationParameters' => $this->applicationParameters,
            'url' => $this->url,
            'user' => $this->user,
        ];
    }
}
