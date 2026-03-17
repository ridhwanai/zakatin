<?php

namespace App\Support;

use Carbon\CarbonInterface;

class HijriCalendar
{
    public static function currentYear(): string
    {
        return (string) self::fromGregorian(now());
    }

    public static function fromGregorian(CarbonInterface $date): int
    {
        // Prefer ICU islamic calendar when intl extension is available.
        if (class_exists(\IntlDateFormatter::class)) {
            $formatter = new \IntlDateFormatter(
                'en_US@calendar=islamic',
                \IntlDateFormatter::NONE,
                \IntlDateFormatter::NONE,
                $date->getTimezone()->getName(),
                \IntlDateFormatter::TRADITIONAL,
                'yyyy'
            );

            if ($formatter !== false) {
                $formatted = $formatter->format($date);

                if (is_string($formatted) && is_numeric($formatted)) {
                    return (int) $formatted;
                }
            }
        }

        // Fallback conversion from Gregorian date to Hijri year.
        $gYear = (int) $date->format('Y');
        $gMonth = (int) $date->format('n');
        $gDay = (int) $date->format('j');

        if ($gMonth < 3) {
            $gYear--;
            $gMonth += 12;
        }

        $a = intdiv($gYear, 100);
        $b = 2 - $a + intdiv($a, 4);

        $julianDay = (int) (
            floor(365.25 * ($gYear + 4716))
            + floor(30.6001 * ($gMonth + 1))
            + $gDay
            + $b
            - 1524
        );

        $l = $julianDay - 1948440 + 10632;
        $n = intdiv($l - 1, 10631);
        $l = $l - (10631 * $n) + 354;

        $j = intdiv(10985 - $l, 5316) * intdiv(50 * $l, 17719)
            + intdiv($l, 5670) * intdiv(43 * $l, 15238);

        $hijriYear = (30 * $n) + $j - 30;

        return $hijriYear;
    }
}
