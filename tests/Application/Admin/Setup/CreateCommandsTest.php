<?php

namespace Thinktomorrow\Chief\Tests\Application\Admin\Setup;

use Thinktomorrow\Chief\Tests\ChiefTestCase;

class CreateCommandsTest extends ChiefTestCase
{
    public function test_it_can_create_a_page()
    {
        $filepath = $this->getTempDirectory().'/'.'Article.php';

        if (file_exists($filepath)) {
            unlink($filepath);
        }

        $this->artisan('chief:page')
            ->expectsQuestion('What is the name in singular for your page model?', 'article')
            ->expectsQuestion('Where do you want to put this class?', $this->getTempDirectory())
            ->expectsQuestion('Which namespace will be used?', 'foo\bar')
            ->expectsConfirmation('Would you like to create a migration file?', 'no')
            ->expectsConfirmation('Would you like to add a frontend view (pages.article)?', 'no')
            ->assertExitCode(0);

        $this->assertFileExists($filepath);
    }

    public function test_it_can_create_a_fragment()
    {
        $filepath = $this->getTempDirectory().'/'.'Quote.php';

        if (file_exists($filepath)) {
            unlink($filepath);
        }

        $this->artisan('chief:fragment')
            ->expectsQuestion('What is the name in singular for your fragment?', 'quote')
            ->expectsQuestion('Where do you want to put this class?', $this->getTempDirectory())
            ->expectsQuestion('Which namespace will be used?', 'foo\bar')
            ->expectsConfirmation('Would you like to add a frontend view (fragments.quote)?', 'no')
            ->expectsConfirmation('Would you like to add a backend view (back.fragments.quote)?', 'no')
            ->assertExitCode(0);

        $this->assertFileExists($filepath);
    }

    public function test_it_can_create_an_admin_view()
    {
        $this->setupAndCreateArticle();

        $filepath = resource_path('views/back/pages/article_page/edit.blade.php');

        if (file_exists($filepath)) {
            unlink($filepath);
        }

        $this->artisan('chief:view article_page')
            ->assertExitCode(0);

        $this->assertFileExists($filepath);
    }

    // chief:view {resourceKey : the managed modelkey of the model you want a view for}
}
