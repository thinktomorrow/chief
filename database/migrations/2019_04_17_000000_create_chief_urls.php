<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Thinktomorrow\Chief\Pages\Page;
use Thinktomorrow\Chief\States\Archivable\ArchiveScope;
use Thinktomorrow\Chief\Urls\UrlRecord;

class CreateChiefUrls extends Migration
{
    public function up()
    {
        Schema::create('chief_urls', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('redirect_id')->nullable();
            $table->string('locale');
            $table->string('slug');
            $table->string('model_type');
            $table->integer('model_id')->unsigned();
            $table->timestamps();

            $table->unique(['locale', 'slug']);
            $table->foreign('redirect_id')->references('id')->on('chief_urls')->onDelete('cascade');
        });

        // Migrate the slugs
        $this->migrateSlugs();

        Schema::table('page_translations', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }

    public function down()
    {
        Schema::dropIfExists('chief_urls');
    }

    private function migrateSlugs()
    {
        foreach (Page::withoutGlobalScope(ArchiveScope::class)->get() as $page) {
            foreach ($page->translations as $translation) {
                UrlRecord::create([
                    'locale'              => $translation->locale,
                    'slug'                => $translation->slug,
                    'model_type'          => $page->getMorphClass(),
                    'model_id'            => $page->id,
                ]);
            }
        }
    }
}
