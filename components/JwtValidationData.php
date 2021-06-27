<?php

declare(strict_types=1);

namespace app\components;

class JwtValidationData extends \sizeg\jwt\JwtValidationData
{
    public function init(): void
    {
        $this->validationData->setIssuer('http://example.com');
        $this->validationData->setAudience('http://example.org');
        $this->validationData->setId('4f1g23a12aa');

        parent::init();
    }
}