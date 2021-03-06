<?php /**
 * Created by jorgelsaud.
 * User: jorgelsaud
 * Date: 9/3/15
 * Time: 10:07
 */
namespace Giorgiosaud\Carbonlocalizer;

use Carbon\Carbon;
use Illuminate\Support\Facades\Lang;

class Carbonlocalizer extends Carbon {
    /**
     * Get the difference in a human readable format.
     *
     * When comparing a value in the past to default now:
     * 1 hour ago
     * 5 months ago
     *
     * When comparing a value in the future to default now:
     * 1 hour from now
     * 5 months from now
     *
     * When comparing a value in the past to another value:
     * 1 hour before
     * 5 months before
     *
     * When comparing a value in the future to another value:
     * 1 hour after
     * 5 months after
     *
     * @param  Carbon $other
     *
     * @return string
     */
    public function diffForHumans2(Carbon $other = null)
    {
        $isNow = $other === null;

        if ($isNow)
        {
            $other = static::now($this->tz);
        }

        $isFuture = $this->gt($other);

        $delta = $other->diffInSeconds($this);

        // 4 weeks per month, 365 days per year... good enough!!
        $divs = array(
            'second' => self::SECONDS_PER_MINUTE,
            'minute' => self::MINUTES_PER_HOUR,
            'hour'   => self::HOURS_PER_DAY,
            'day'    => self::DAYS_PER_WEEK,
            'week'   => 4,
            'month'  => self::MONTHS_PER_YEAR
        );

        $unit = 'year';

        foreach ($divs as $divUnit => $divValue)
        {
            if ($delta < $divValue)
            {
                $unit = $divUnit;
                break;
            }

            $delta = floor($delta / $divValue);
        }

        if ($delta == 0)
        {
            $delta = 1;
        }

        // Código adaptado para utilizar el gestor de idiomas de Laravel
        $txt = 'carbonlocale';

        if ($isFuture)
        {
            return trans_choice("$txt.past.$unit", $delta, compact('delta'));

            return Lang::choice("$txt.past.$unit", $delta, compact('delta'));
        }
        return trans_choice("$txt.future.$unit", $delta, compact('delta'));
    }
}