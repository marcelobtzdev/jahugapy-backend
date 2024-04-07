<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        
    ];

    public function eventTeams() {
        return $this->hasMany(EventTeam::class);
    }

    public function eventScores() {
        return $this->hasMany(EventScore::class);
    }

    public function multiplier() {
        return $this->belongsTo(Multiplier::class);
    }

    protected function image(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => url('events/'.$value)
        );
    }
}
