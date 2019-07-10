<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Action_Type extends Model
{

    protected $table = 'actions_types';

    const POSITIVE_POINTS = 'positive';
    const NEGATIVE_POINTS = 'negative';
    const NEUTRAL_POINTS = 'neutral';
}
