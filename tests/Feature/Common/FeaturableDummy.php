<?php
declare(strict_types=1);

namespace Thinktomorrow\Chief\Tests\Feature\Common;

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Thinktomorrow\Chief\Concerns\Featurable;

/**
 * Class ValidationTraitDummyClass
 *
 * @package Thinktomorrow\Chief\Models
 */
class FeaturableDummy extends Model
{
    use Featurable;

    public $featured = false;

    public function save(array $options = [])
    {
        //
    }

    public static function migrateUp()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->boolean('featured')->default(false);
        });
    }
}
