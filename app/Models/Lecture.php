<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Lecture extends Model
{
    use HasFactory;

    protected $hidden = ['pivot'];
    protected $fillable = ['theme', 'description'];
    protected $table = 'lectures';

    /**
     * @return BelongsToMany
     */
    public function plans(): BelongsToMany
    {
        return $this->belongsToMany(Plan::class, 'lecture_plan', 'lecture_id', 'plan_id')
            ->withTimestamps();
    }

}
