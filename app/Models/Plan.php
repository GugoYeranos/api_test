<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Plan extends Model
{
    use HasFactory;
    protected $table = 'plans';
    protected $fillable = ['name'];

    /**
     * @return BelongsToMany
     */
    public function lectures(): BelongsToMany
    {
        return $this->belongsToMany(Lecture::class, 'lecture_plan', 'plan_id', 'lecture_id')
            ->withTimestamps();
    }

    /**
     * @return HasMany
     */
    public function planClasses(): HasMany
    {
        return $this->hasMany(Group::class);
    }
}
