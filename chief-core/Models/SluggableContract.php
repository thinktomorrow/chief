<?php

namespace Chief\Models;

interface SluggableContract
{
    public static function findBySlug($slug);
}