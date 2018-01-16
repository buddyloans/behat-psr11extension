<?php
declare(strict_types=1);

namespace RoaveTest\BehatPsrContainer\Exception;

use PHPUnit\Framework\TestCase;
use Roave\BehatPsrContainer\Exception\NotAPsrContainer;

/**
 * @covers \Roave\BehatPsrContainer\Exception\NotAPsrContainer
 */
final class NotAPsrContainerTest extends TestCase
{
    public function nonContainerValuesProvider() : array
    {
        return [
            ['just a string', 'string'],
            [42, 'integer'],
            [3.14, 'double'],
            [new \stdClass(), \stdClass::class],
        ];
    }

    /**
     * @param mixed $value
     * @param string $expectedType
     * @dataProvider nonContainerValuesProvider
     */
    public function testExceptionForContainerValues($value, string $expectedType)
    {
        $filename = uniqid('filename', true);

        $exception = NotAPsrContainer::fromAnythingButAPsrContainer($filename, $value);

        self::assertInstanceOf(NotAPsrContainer::class, $exception);
        self::assertInstanceOf(\RuntimeException::class, $exception);
        self::assertSame(
            sprintf(
                'File %s must return a PSR-11 container, actually returned %s',
                $filename,
                $expectedType
            ),
            $exception->getMessage()
        );
    }
}
