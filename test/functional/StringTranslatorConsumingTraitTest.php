<?php

namespace Dhii\I18n\FuncTest;

use Xpmock\TestCase;

/**
 * Tests {@see \Dhii\I18n\StringTranslatorConsumingTrait}.
 */
class StringTranslatorConsumingTraitTest extends TestCase
{
    /**
     * The name of the test subject.
     *
     * @since [*next-version*]
     */
    const TEST_SUBJECT_CLASSNAME = 'Dhii\\I18n\\StringTranslatorConsumingTrait';

    /**
     * Creates a new instance of the test subject.
     *
     * @since [*next-version*]
     *
     * @return mixed Something that uses the trait.
     */
    public function createInstance($translator = null)
    {
        $mock = $this->getMockForTrait(static::TEST_SUBJECT_CLASSNAME);
        $mock->method('_getTranslator')->willReturn($translator);

        return $mock;
    }

    public function createTranslator()
    {
        $mock = $this->mock('Dhii\\I18n\\StringTranslatorInterface')
                ->translate($this->returnArgument(0))
                ->new();

        return $mock;
    }

    /**
     * Tests whether a correct instance of the test subject can be created.
     *
     * @since [*next-version*]
     */
    public function testCanBeCreated()
    {
        $subject = $this->createInstance();

        $this->assertInternalType('object', $subject, 'Could not create an correct instance of the test subject');
    }

    /**
     * Tests to make sure that the translator consumer correctly consumes the translator.
     * 
     * This is the happy path.
     * 
     * @since [*next-version*]
     */
    public function testTranslate()
    {
        $string = uniqid('translation-subject-');
        $translator = $this->createTranslator();
        $translator->mock()->translate(array($string), $this->returnArgument(0), $this->once());
        $subject = $this->createInstance($translator);
        $_subject = $this->reflect($subject);

        $result = $_subject->__($string);
        $this->assertEquals($string, $result, 'Unexpected translation result');
    }
}
