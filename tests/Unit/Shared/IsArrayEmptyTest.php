<?php

declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Unit\Shared;

use PHPUnit\Framework\TestCase;

class IsArrayEmptyTest extends TestCase
{
    public function test_it_returns_true_for_only_empty_like_values(): void
    {
        $this->assertTrue(is_array_empty(['', '   ', null, false]));
    }

    public function test_it_returns_false_when_a_non_empty_string_is_present(): void
    {
        $this->assertFalse(is_array_empty(['', 'foo', '']));
    }

    public function test_zero_values_are_not_treated_as_empty(): void
    {
        $this->assertFalse(is_array_empty(['0']));
        $this->assertFalse(is_array_empty([0]));
    }

    public function test_non_string_values_do_not_cause_errors_and_are_checked(): void
    {
        $this->assertFalse(is_array_empty(['', 123]));
    }
}

