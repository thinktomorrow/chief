<?php

namespace Thinktomorrow\Chief\Plugins\Export\Tests\Import;

use Thinktomorrow\Chief\Fragments\Database\ContextModel;
use Thinktomorrow\Chief\Managers\Register\Registry;
use Thinktomorrow\Chief\Plugins\Export\Import\FieldReference;
use Thinktomorrow\Chief\Plugins\Export\Tests\TestCase;
use Thinktomorrow\Chief\Tests\Shared\Fakes\ArticlePage;
use Thinktomorrow\Chief\Tests\Unit\UnitTestHelpers;

class FieldReferenceTest extends TestCase
{
    use UnitTestHelpers;

    private FieldReference $fieldReference;

    public function setUp(): void
    {
        parent::setUp();

        $this->fieldReference = $this->createFieldReference();
    }

    public function test_it_can_create_reference_from_encrypted_key()
    {
        $reference = FieldReference::fromEncryptedKey(encrypt(ArticlePage::first()->modelReference()->get() . '|title'));

        $this->assertEquals(ArticlePage::first(), $this->getPrivateProperty($reference, 'model'));
        $this->assertEquals(app(Registry::class)->resource('article_page'), $this->getPrivateProperty($reference, 'resource'));
        $this->assertEquals(app(Registry::class)->resource('article_page')->field(ArticlePage::first(), 'title')->getKey(), $this->getPrivateProperty($reference, 'field')->getKey());
        $this->assertEquals('title', $this->getPrivateProperty($reference, 'fieldName'));
    }

    public function test_it_can_create_reference_for_repeat_field()
    {
        $article = ArticlePage::create();
        $snippet = $this->setUpAndCreateSnippet($article);
        $resource = app(Registry::class)->resource($snippet::resourceKey());

        $reference = FieldReference::fromEncryptedKey(encrypt($snippet->fragmentModel()->modelReference()->get() . '|links.1.title'));

        $this->assertEquals($snippet->fragmentModel()->fresh(), $this->getPrivateProperty($reference, 'model'));
        $this->assertEquals($resource, $this->getPrivateProperty($reference, 'resource'));
        $this->assertEquals($resource->field($snippet->fragmentModel(), 'links')->getKey(), $this->getPrivateProperty($reference, 'field')->getKey());
        $this->assertEquals('links.1.title', $this->getPrivateProperty($reference, 'fieldName'));
    }

    public function test_it_can_get_value()
    {
        $this->assertEquals('title article nl', $this->fieldReference->getValue('nl'));
    }

    public function test_it_can_save_value()
    {
        $this->fieldReference->saveValue('new title', 'nl');

        $this->assertEquals('new title', ArticlePage::first()->dynamic('title_trans', 'nl'));
        $this->assertEquals('title article en', ArticlePage::first()->dynamic('title_trans', 'en'));
    }

    public function test_it_can_save_repeat_value()
    {
        $fieldReference = $this->createRepeatFieldReference();

        $this->assertTrue($fieldReference->isRepeatField());
        $fieldReference->saveValue('new title', 'nl');

        $fragmentModel = ContextModel::ownedBy(ArticlePage::all()[1])->fragments()->first();

        $this->assertEquals([
            ['title' => ['nl' => 'new title']],
        ], $fragmentModel->links);
    }

    private function createFieldReference(): FieldReference
    {
        $article = $this->setUpAndCreateArticle(['title_trans' => ['nl' => 'title article nl', 'en' => 'title article en'], 'content_trans' => ['nl' => 'content article nl']]);
        $resource = app(Registry::class)->resource('article_page');

        return new FieldReference($resource, $article, $resource->field($article, 'title_trans'), 'title_trans');
    }

    private function createRepeatFieldReference(): FieldReference
    {
        $article = ArticlePage::create();
        $snippet = $this->setUpAndCreateSnippet($article);
        $resource = app(Registry::class)->resource($snippet::resourceKey());

        return new FieldReference($resource, $snippet->fragmentModel(), $resource->field($snippet, 'links'), 'links.0.title');
    }
}
