<?php

namespace Dhii\I18n;

/**
 * Methods for classes that can have a string translator.
 *
 * @since [*next-version*]
 */
trait StringTranslatorAwareTrait
{
    /**
     * The translator associated with this instance.
     *
     * @since [*next-version*]
     *
     * @var StringTranslatorInterface
     */
    protected $translator;

    /**
     * Assigns the translator to be used by this instance.
     *
     * @since [*next-version*]
     *
     * @param StringTranslatorInterface $translator The translator.
     *
     * @return $this
     */
    protected function _setTranslator(StringTranslatorInterface $translator)
    {
        $this->translator = $translator;

        return $this;
    }

    /**
     * Retrieves the translator associated with this instance.
     *
     * @since [*next-version*]
     *
     * @return StringTranslatorInterface The translator.
     */
    protected function _getTranslator()
    {
        return $this->translator;
    }
}
