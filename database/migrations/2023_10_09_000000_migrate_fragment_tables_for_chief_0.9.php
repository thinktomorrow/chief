<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::hasColumn('chief_urls', 'site')) {
            Schema::table('chief_urls', function (Blueprint $table) {
                $table->renameColumn('locale', 'site');
            });

            Schema::table('chief_urls', function (Blueprint $table) {
                $table->dropUnique('chief_urls_locale_slug_unique');
            });

            Schema::table('chief_urls', function (Blueprint $table) {
                $table->unique(['site', 'slug']);
            });
        }

        // This migration is meant for existing database setups.
        // It will migrate the existing fragment tables to the new structure.
        // New setups will have this structure by default.
        if ($this->columnSchemaIsAlreadyAltered()) {
            $this->insertDefaultContextSites();
            $this->changeModelReferencesToKeyFormat();
            $this->copyContextFragmentLookupToTree();
            $this->addActiveContextIdToUrl();

            return;
        }

        Schema::table('contexts', function (Blueprint $table) {
            $table->json('sites')->after('owner_id')->nullable();
            $table->string('title')->nullable();
        });

        // DO MIGRATION
        // TODO: migration expand to different contexts per locale
        // TODO: migrate active context ids to each url db record.
        $this->addActiveContextToUrl();
        $this->changeFragmentIdColumnToChar();
        $this->removeSoftDeletion();
        $this->renameModelReferenceColumnToKey();
        $this->nestableFragments();

        $this->insertDefaultContextSites();
        $this->changeModelReferencesToKeyFormat();
        $this->copyContextFragmentLookupToTree();
        $this->addActiveContextIdToUrl();
    }

    public function down() {}

    private function insertDefaultContextSites(): void
    {
        $siteIds = \Thinktomorrow\Chief\Sites\ChiefSites::all()->map(fn ($site) => $site->id)->toArray();

        DB::table('contexts')->update(['sites' => json_encode($siteIds)]);
    }

    public function addActiveContextToUrl(): void
    {
        Schema::table('chief_urls', function (Blueprint $table) {
            $table->unsignedBigInteger('context_id')->nullable()->after('id');
            $table->foreign('context_id')->references('id')->on('contexts')->nullOnDelete();
        });
    }

    private function changeFragmentIdColumnToChar(): void
    {
        Schema::table('context_fragment_lookup', function (Blueprint $table) {
            $table->dropForeign('context_fragment_lookup_fragment_id_foreign');
        });

        Schema::table('context_fragment_lookup', function (Blueprint $table) {
            $table->char('fragment_id', 36)->change();
        });

        Schema::table('context_fragments', function (Blueprint $table) {
            $table->char('id', 36)->change();
        });

        Schema::table('context_fragment_lookup', function (Blueprint $table) {
            $table->foreign('fragment_id')->references('id')->on('context_fragments')->onDelete('cascade');
        });
    }

    private function nestableFragments(): void
    {
        // TODO: replace current lookup. migrate existing fragments to new structure
        Schema::create('context_fragment_tree', function (Blueprint $table) {
            $table->unsignedBigInteger('context_id');
            $table->char('parent_id', 36)->nullable(); // Root fragments have no parent
            $table->char('child_id', 36);
            $table->json('locales')->nullable();
            $table->unsignedSmallInteger('order')->default(0);

            $table->foreign('context_id')->references('id')->on('contexts')->onDelete('cascade');
            $table->foreign('parent_id')->references('id')->on('context_fragments')->onDelete('cascade');
            $table->foreign('child_id')->references('id')->on('context_fragments')->onDelete('cascade');

            $table->unique(['context_id', 'parent_id', 'child_id']);
        });
    }

    private function addActiveContextIdToUrl(): void
    {
        // Get all contexts of all pages
        $contextRows = DB::table('contexts')->whereNot('owner_type', 'fragmentmodel')->get();

        foreach ($contextRows as $contextRow) {
            $urlRecords = DB::table('chief_urls')
                ->where('model_type', $contextRow->owner_type)
                ->where('model_id', $contextRow->owner_id)
                ->get();

            foreach ($urlRecords as $urlRecord) {
                DB::table('chief_urls')->where('id', $urlRecord->id)->update([
                    'context_id' => $contextRow->id,
                ]);
            }
        }
    }

    private function copyContextFragmentLookupToTree(): void
    {
        // TODO: differentiate between context of page or fragment contexts...
        $contextRows = DB::table('contexts')->whereNot('owner_type', 'fragmentmodel')->get();

        foreach ($contextRows as $contextRow) {
            $lookups = DB::table('context_fragment_lookup')->where('context_id', $contextRow->id)->get();

            foreach ($lookups as $lookup) {
                DB::table('context_fragment_tree')->insert([
                    'context_id' => $contextRow->id,
                    'parent_id' => null,
                    'child_id' => $lookup->fragment_id,
                    'order' => $lookup->order,
                ]);

                // Recursive context check for this fragment...
                $this->copyContextFragmentLookupForFragment($contextRow->id, $lookup->fragment_id);
            }

        }
    }

    private function copyContextFragmentLookupForFragment($mainContextId, $fragmentId): void
    {
        $contextRows = DB::table('contexts')
            ->where('owner_type', 'fragmentmodel')
            ->where('owner_id', $fragmentId)
            ->get();

        foreach ($contextRows as $contextRow) {
            $lookups = DB::table('context_fragment_lookup')->where('context_id', $contextRow->id)->get();

            foreach ($lookups as $lookup) {
                DB::table('context_fragment_tree')->insert([
                    'context_id' => $mainContextId,
                    'parent_id' => $fragmentId,
                    'child_id' => $lookup->fragment_id,
                    'order' => $lookup->order,
                ]);

                // Recursive context check for this fragment...
                $this->copyContextFragmentLookupForFragment($mainContextId, $lookup->fragment_id);
            }
        }
    }

    private function renameModelReferenceColumnToKey(): void
    {
        Schema::table('context_fragments', function (Blueprint $table) {
            $table->renameColumn('model_reference', 'key');
        });
    }

    private function removeSoftDeletion(): void
    {
        Schema::table('context_fragments', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
    }

    private function changeModelReferencesToKeyFormat(): void
    {
        DB::table('context_fragments')->get()->each(function ($row) {
            DB::table('context_fragments')->where('id', $row->id)->update(
                ['key' => substr($row->key, 0, strpos($row->key, '@'))]
            );
        });
    }

    private function columnSchemaIsAlreadyAltered(): bool
    {
        return Schema::hasColumn('contexts', 'sites');
    }
};
