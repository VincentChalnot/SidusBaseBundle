<?php
/*
 * This file is part of the Sidus/BaseBundle package.
 *
 * Copyright (c) 2015-2019 Vincent Chalnot
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sidus\BaseBundle\Utilities;

use DateTime;
use UnexpectedValueException;

/**
 * @see    DateTimeUtility::parse
 *
 * @author Vincent Chalnot <vincent@sidus.fr>
 */
class DateTimeUtility
{
    /**
     * Parse a datetime, allowing either DateTime objects (passthrough), Unix timestamps as integers or valid ATOM or
     * ISO8601 string
     *
     * @param DateTime|int|string $data
     * @param bool                $allowNull
     *
     * @throws UnexpectedValueException
     *
     * @return DateTime
     */
    public static function parse($data, $allowNull = true)
    {
        if (null === $data) {
            if ($allowNull) {
                return null;
            }

            throw new UnexpectedValueException('Expecting DateTime or timestamp, null given');
        }

        if ($data instanceof DateTime) {
            return $data;
        }

        if (\is_int($data)) {
            if (0 === $data) {
                throw new UnexpectedValueException('Expecting timestamp, numeric value "0" given');
            }
            $date = new DateTime();
            $date->setTimestamp($data);

            return $date;
        }
        $date = DateTime::createFromFormat(DateTime::ATOM, $data);
        if (!$date) { // Trying deprecated pattern
            /** @noinspection DateTimeConstantsUsageInspection */
            $date = DateTime::createFromFormat(DateTime::ISO8601, $data);
        }
        if (!$date && '' !== $data) {
            try {
                $date = new DateTime($data);
            } catch (\Exception $e) {
                $date = null;
            }
        }
        if (!$date) {
            throw new UnexpectedValueException(
                "Unable to parse DateTime value: '{$data}' expecting DateTime or timestamp"
            );
        }

        return $date;
    }
}
