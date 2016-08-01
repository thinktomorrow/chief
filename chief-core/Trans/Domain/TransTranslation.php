<?php

namespace Chief\Trans\Domain;

use Illuminate\Database\Eloquent\Model;

class TransTranslation extends Model
{
    public $table = 'trans_translations';
    public $timestamps = false;
}