<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventScore extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'event_id',
        'team_id',
        'date_number',
        'match_number',
        'kills',
        'kills_image',
        'position',
        'position_image'
    ];

    /**
     * The relations to eager load on every query.
     *
     * @var array
     */
    protected $with = [
        'team'
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
        'points'
    ];

    public function event() {
        return $this->belongsTo(Event::class);
    }

    public function team() {
        return $this->belongsTo(Team::class);
    }

    protected function points(): Attribute
    {
        $multiplierDetails = $this->event->multiplier->details;
        $multipliersByValue = [];

        foreach ($multiplierDetails as $detail) {
            $rangeExplode = explode('-', $detail->position_range);

            array_push($multipliersByValue, [
                'value' => $detail->value,
                'positions' => range($rangeExplode[0], $rangeExplode[1])
            ]);
        };

        $points = $this->kills;

        foreach ($multipliersByValue as $multiplier) {
            if (array_search($this->position, $multiplier['positions']) !== false) {
                $points = $points * $multiplier['value'];

                break;
            };
        };

        return Attribute::make(
            get: fn (mixed $value) => $points
        );
    }
}
