<?php

declare(strict_types=1);

namespace App\ViewInjection;

use App\ApplicationParameters;
use Yiisoft\Router\UrlGeneratorInterface;
use Yiisoft\Yii\View\ContentParametersInjectionInterface;

final class ContentViewInjection implements ContentParametersInjectionInterface
{
    private ApplicationParameters $applicationParameters;
    private UrlGeneratorInterface $url;

    public function __construct(
        ApplicationParameters $applicationParameters,
        UrlGeneratorInterface $url
    ) {
        $this->applicationParameters = $applicationParameters;
        $this->url = $url;
    }

    public function getContentParameters(): array
    {
        return [
            'applicationParameters' => $this->applicationParameters,
            'url' => $this->url,
        ];
    }
}
