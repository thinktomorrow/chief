<?php

namespace Chief\Common\Contracts;

interface SluggableContract
{
    public static function findBySlug($slug);
}