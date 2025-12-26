<?php

declare(strict_types=1);

namespace Hirtz\Cms\Tenant\Test;

use Override;

class TestCase extends \Hirtz\Tenant\Test\TestCase
{
    #[Override]
    protected function setUp(): void
    {
        $this->config ??= require(__DIR__ . '/../../config/test.php');
        parent::setUp();
    }
}
