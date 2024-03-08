<?php

namespace App\Models;

use App\Scopes\TeamScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'mode_id',
        'image',
    ];

    // /**
    //  * The "booted" method of the model.
    //  *
    //  * @return void
    //  */
    // protected static function booted()
    // {
    //     static::addGlobalScope(new TeamScope);
    // }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function members() {
        return $this->hasMany(TeamMember::class);
    }

    public function scopeOfUser($query) {
        return $query->where('user_id', auth()->user()->id);
    }
}
