<?php
declare(strict_types=1);


interface ActiveContextOwner
{
    public function getActiveContextId(): ?string;
}
