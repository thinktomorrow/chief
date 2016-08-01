<?php

namespace Chief\Trans\Commands;

use Chief\Trans\Domain\Trans;
use Chief\Trans\Handlers\ReadOriginalTranslationsFromDisk;
use Chief\Trans\Domain\Transgroup;
use Chief\Trans\Handlers\SaveTranslationsToDisk;
use Illuminate\Console\Command;

class ImportTranslationsCommand extends Command
{
    private $stats = [];

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tt:import-trans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Safe import of new translation lines to your lines in database.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info('Import started...');

        $locales = config('translatable.locales');
        $groups = ['blocks','factoring','ideal-partner','legal','links','pages','timeline','footer'];

        foreach($locales as $locale)
        {
            // Get all our translations files
            $lines = app(ReadOriginalTranslationsFromDisk::class)->readLoosely($locale,$groups);

            foreach($groups as $slug)
            {
                if(!$group = Transgroup::findBySlug($slug))
                {
                    $group = Transgroup::make($slug);
                }

                if(!isset($lines[$slug])) continue;

                foreach($lines[$slug] as $key => $value)
                {
                    $key = $group->slug.'.'.$key;

                    if(!$transline = Trans::findByKey($key))
                    {
                        $transline = Trans::make($key,$group->id,null,null,Trans::suggestType($value));
                    }

                    $currentline = $transline->getTranslation($locale,false);

                    if(!$currentline)
                    {
                        $transline->saveTranslation($locale,'value',$value);
                    }
                    elseif($currentline->value !== $value)
                    {
                        // Notify a possible change
                        $this->info($key. ' '.strtoupper($locale) .' translation has been changed.');
                        $this->comment("Original value:");
                        $this->line($currentline->value);
                        $this->comment("New value:");
                        $this->line($value);
                        if (!$this->confirm('Overwrite?', false))
                        {
                            //
                        }else
                        {
                            $transline->saveTranslation($locale,'value',$value);
                        }
                    }
                }
            }
        }

        $this->info('Import finished.');

        // Recache results
        app(SaveTranslationsToDisk::class)->clear()->handle();
        $this->info('Translation cache refreshed.');

        $this->output->writeln('');
    }

}