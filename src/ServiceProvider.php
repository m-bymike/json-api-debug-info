<?php declare(strict_types=1);

namespace ByMike\JsonApiDebugInfo;

use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use CloudCreativity\JsonApi\Contracts\Exceptions\ExceptionParserInterface;

class ServiceProvider extends BaseServiceProvider
{
    public function register()
    {
        $this->app->bind(ExceptionParserInterface::class, function (Container $app): JsonApiExceptionParser {
            return $app->make(JsonApiExceptionParser::class, [
                'debug' => config('app.debug'),
            ]);
        });
    }
}
