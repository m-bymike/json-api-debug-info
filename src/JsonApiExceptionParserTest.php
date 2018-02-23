<?php declare(strict_types=1);

namespace ByMike\JsonApiDebugInfo;

use PHPUnit\Framework\TestCase;
use CloudCreativity\JsonApi\Document\Error;
use CloudCreativity\JsonApi\Contracts\Repositories\ErrorRepositoryInterface;

class JsonApiExceptionParserTest extends TestCase
{
    public function testAddsStacktrace()
    {
        $parser = $this->makeParser(true);

        $e = new \Exception('Test parser', 500);

        $expected = new Error(null, null, $e->getCode(), $e->getCode(), $e->getMessage(), $e->getTraceAsString());
        $actual = $parser->parse($e)->getErrors()[0] ?? false;

        $this->assertEquals($expected, $actual);
    }

    public function testDoesNotAddStacktrace()
    {
        $parser = $this->makeParser(false);

        $e = new \Exception('Test parser', 500);
        $expected = new Error();
        $actual = $parser->parse($e)->getErrors()[0] ?? false;

        $this->assertEquals($expected, $actual);
    }

    private function makeParser(bool $debug): JsonApiExceptionParser
    {
        $errors = $this->createMock(ErrorRepositoryInterface::class);
        $errors->method('error')->willReturn(new Error());
        return new JsonApiExceptionParser($errors, null, $debug);
    }
}
