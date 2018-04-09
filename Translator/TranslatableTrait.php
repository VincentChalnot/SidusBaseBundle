<?php
/*
 * This file is part of the Sidus/BaseBundle package.
 *
 * Copyright (c) 2015-2018 Vincent Chalnot
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sidus\BaseBundle\Translator;

use Sidus\BaseBundle\Utilities\TranslatorUtility;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Used to try multiple translations with fallback
 *
 * @author Vincent Chalnot <vincent@sidus.fr>
 */
trait TranslatableTrait
{
    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * @param TranslatorInterface $translator
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * Will check the translator for the provided keys and humanize the code if no translation is found
     *
     * @param string|array $tIds
     * @param array        $parameters
     * @param string       $fallback
     * @param bool         $humanizeFallback
     *
     * @return string
     */
    protected function tryTranslate($tIds, array $parameters = [], $fallback = null, $humanizeFallback = true)
    {
        return TranslatorUtility::tryTranslate($this->translator, $tIds, $parameters, $fallback, $humanizeFallback);
    }
}
