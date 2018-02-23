<?php declare(strict_types=1);

namespace ByMike\JsonApiDebugInfo;

use CloudCreativity\JsonApi\Document\Error;
use Neomerx\JsonApi\Exceptions\JsonApiException;
use CloudCreativity\LaravelJsonApi\Exceptions\ExceptionParser;
use CloudCreativity\JsonApi\Contracts\Exceptions\ErrorIdAllocatorInterface;
use CloudCreativity\JsonApi\Contracts\Repositories\ErrorRepositoryInterface;

class JsonApiExceptionParser extends ExceptionParser
{
    private $debug = false;

    public function __construct(
        ErrorRepositoryInterface $errors,
        ErrorIdAllocatorInterface $idAllocator = null,
        bool $debug = false
    ) {
        $this->debug = $debug;
        parent::__construct($errors, $idAllocator);
    }

    protected function getErrors(\Exception $e)
    {
        $errors = parent::getErrors($e);
        if ($errors !== false) {
            return $errors;
        }

        if (!$this->debug) {
            return parent::getDefaultError();
        }

        $httpCode = $e instanceof JsonApiException ? $e->getHttpCode() : 500;

        return new Error(null, null, $httpCode, $e->getCode(), $e->getMessage(), $e->getTraceAsString());
    }

    protected function getDefaultError()
    {
        return false;
    }
}
