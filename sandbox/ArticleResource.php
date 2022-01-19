<?php
declare(strict_types=1);

class ArticleResource
{

    public function __construct()
    {

    }


    public function display()
    {
        // For pages: setup of page
        // For fragments: setup of wireframe

        yield Window::make('intro')->title('DIT IS TITEL VAN WINDOW')->type('info');
        <x-chief::window form="intro" />
    }

    public function adminView()
    {
        return 'back.article';
    }


    public function form()
    {
        // Page single form (default)
        yield \Thinktomorrow\Chief\Forms\Fields\Types\InputField::make('title');
        yield \Thinktomorrow\Chief\Forms\Fields\Types\TextField::make('content');

        // With grid
        yield Layout\FieldSet::make()->fields([
            Layout\Grid::make()->fields([
                \Thinktomorrow\Chief\Forms\Fields\Types\Input::make('firstname'),
                \Thinktomorrow\Chief\Forms\Fields\Types\Text::make('lastname'),
            ])->columns(2),
            \Thinktomorrow\Chief\Forms\Fields\Types\TextField::make('content'),
        ])->title('Introductie')->type('info');

        // Multiple forms (ajax)
        yield Forms::make()->forms([
            Form::make()->title()->displayAsWindow('custom-view')->fields([
                \Thinktomorrow\Chief\Forms\Fields\Types\InputField::make('title'),
                Card::make()->fields([
                    \Thinktomorrow\Chief\Forms\Fields\Types\TextField::make('content'),
                ])->title('DIT IS TITEL VAN WINDOW')->type('info'),

                FieldSet::make()->fields([
                    \Thinktomorrow\Chief\Forms\Fields\Types\InputField::make('title'),
                    \Thinktomorrow\Chief\Forms\Fields\Types\TextField::make('content'),
                ])->title('sisisisisi')->type('info'),
            ]),
            Form::make()->fields([
                \Thinktomorrow\Chief\Forms\Fields\Types\InputField::make('title'),

                Card::make()->fields([
                    \Thinktomorrow\Chief\Forms\Fields\Types\TextField::make('content'),
                ])->title('DIT IS TITEL VAN WINDOW')->type('info'),

                FieldSet::make()->fields([
                    \Thinktomorrow\Chief\Forms\Fields\Types\InputField::make('title'),
                    \Thinktomorrow\Chief\Forms\Fields\Types\TextField::make('content'),
                ])->title('sisisisisi')->type('info'),
            ])
        ]);

        yield Tabs::make()->tabs([ // component
            Tab::make()->fields([ // component
                \Thinktomorrow\Chief\Forms\Fields\Types\InputField::make('title'), // component
                \Thinktomorrow\Chief\Forms\Fields\Types\TextField::make('content'), // component
            ]),
            Tab::make()->fields([
                \Thinktomorrow\Chief\Forms\Fields\Types\InputField::make('title'),
                \Thinktomorrow\Chief\Forms\Fields\Types\TextField::make('content'),
            ]),
        ])->title('sisisisisi')->columns(2);

        // On page this is a livewire component, inline form with submit or ????
        // Window extends Card
        yield Window::make()->fields([
            \Thinktomorrow\Chief\Forms\Fields\Types\InputField::make('title'),
            \Thinktomorrow\Chief\Forms\Fields\Types\TextField::make('content'),
        ])->title('DIT IS TITEL VAN WINDOW')->type('info');

        yield Card::make()->fields([
            \Thinktomorrow\Chief\Forms\Fields\Types\InputField::make('title'),
            \Thinktomorrow\Chief\Forms\Fields\Types\TextField::make('content'),
        ])->title('DIT IS TITEL VAN WINDOW')->type('info');

        yield FieldSet::make()->fields([
            \Thinktomorrow\Chief\Forms\Fields\Types\InputField::make('title'),
            \Thinktomorrow\Chief\Forms\Fields\Types\TextField::make('content'),
        ])->title('sisisisisi')->type('info');

        yield Grid::make()->fields([
            \Thinktomorrow\Chief\Forms\Fields\Types\InputField::make('title'),
            \Thinktomorrow\Chief\Forms\Fields\Types\TextField::make('content'),
        ])->title('sisisisisi')->columns(2)->attributes(['class' => 'w-full']);



        yield Card::make('id')->html('<p>dude</p>');
    }
}
