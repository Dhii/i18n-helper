<?php

namespace Dhii\I18n\UnitTest;

use Dhii\I18n\StringTranslatorConsumingTrait as TestSubject;

use Dhii\I18n\StringTranslatorInterface;
use Xpmock\TestCase;
use Exception as RootException;
use PHPUnit_Framework_MockObject_MockObject as MockObject;
use PHPUnit_Framework_MockObject_MockBuilder as MockBuilder;

/**
 * Tests {@see TestSubject}.
 *
 * @since [*next-version*]
 */
class StringTranslatorConsumingTraitTest extends TestCase
{
    /**
     * The class name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\I18n\StringTranslatorConsumingTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @param array $methods The methods to mock.
     *
     * @return MockObject The new instance.
     */
    public function createInstance($methods = [])
    {
        is_array($methods) && $methods = $this->mergeValues($methods, [
        ]);

        $mock = $this->getMockBuilder(static::TEST_SUBJECT_CLASSNAME)
            ->setMethods($methods)
            ->getMockForTrait();

        return $mock;
    }

    /**
     * Merges the values of two arrays.
     *
     * The resulting product will be a numeric array where the values of both inputs are present, without duplicates.
     *
     * @since [*next-version*]
     *
     * @param array $destination The base array.
     * @param array $source      The array with more keys.
     *
     * @return array The array which contains unique values
     */
    public function mergeValues($destination, $source)
    {
        return array_keys(array_merge(array_flip($destination), array_flip($source)));
    }

    /**
     * Creates a mock that both extends a class and implements interfaces.
     *
     * This is particularly useful for cases where the mock is based on an
     * internal class, such as in the case with exceptions. Helps to avoid
     * writing hard-coded stubs.
     *
     * @since [*next-version*]
     *
     * @param string   $className      Name of the class for the mock to extend.
     * @param string[] $interfaceNames Names of the interfaces for the mock to implement.
     *
     * @return MockBuilder The builder for a mock of an object that extends and implements
     *                     the specified class and interfaces.
     */
    public function mockClassAndInterfaces($className, $interfaceNames = [])
    {
        $paddingClassName = uniqid($className);
        $definition = vsprintf('abstract class %1$s extends %2$s implements %3$s {}', [
            $paddingClassName,
            $className,
            implode(', ', $interfaceNames),
        ]);
        eval($definition);

        return $this->getMockBuilder($paddingClassName);
    }

    /**
     * Creates a new exception.
     *
     * @since [*next-version*]
     *
     * @param string $message The exception message.
     *
     * @return RootException|MockObject The new exception.
     */
    public function createException($message = '')
    {
        $mock = $this->getMockBuilder('Exception')
            ->setConstructorArgs([$message])
            ->getMock();

        return $mock;
    }

    /**
     * Creates a translator.
     *
     * @param array|null $methods The methods to mock, if any.
     *
     * @return MockObject|StringTranslatorInterface The new translator.
     */
    public function createStringTranslator($methods = [])
    {
        is_array($methods) && $methods = $this->mergeValues($methods, [
        ]);
        $mock = $this->getMockBuilder('Dhii\I18n\StringTranslatorInterface')
            ->setMethods($methods)
            ->getMock();

        return $mock;
    }

    /**
     * Tests whether a valid instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInternalType(
            'object',
            $subject,
            'A valid instance of the test subject could not be created.'
        );
    }

    /**
     * Tests that the `_translate()` method works as expected when no translator is present.
     *
     * @since [*next-version*]
     */
    public function testTranslateNoTranslator()
    {
        $string = uniqid('string');
        $context = uniqid('context');
        $subject = $this->createInstance(['_getTranslator']);
        $_subject = $this->reflect($subject);

        $subject->expects($this->exactly(1))
            ->method('_getTranslator')
            ->will($this->returnValue(null));

        $result = $_subject->_translate($string, $context);
        $this->assertEquals($string, $result, 'Wrong translation result');
    }

    /**
     * Tests that the `_translate()` method works as expected when a translator is present.
     *
     * @since [*next-version*]
     */
    public function testTranslateWithTranslator()
    {
        $translator = $this->createStringTranslator(['translate']);
        $string = uniqid('string');
        $context = uniqid('context');
        $translation = uniqid('translation');
        $subject = $this->createInstance(['_getTranslator']);
        $_subject = $this->reflect($subject);

        $translator->expects($this->exactly(1))
            ->method('translate')
            ->with($string, $context)
            ->will($this->returnValue($translation));

        $subject->expects($this->exactly(1))
            ->method('_getTranslator')
            ->will($this->returnValue($translator));

        $result = $_subject->_translate($string, $context);
        $this->assertEquals($translation, $result, 'Wrong translation result');
    }
}
