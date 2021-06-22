# Chief wireframes

Chief wireframes are a group of laravel components, specifically made to build admin views for project fragments.
These components have a preconfigured, compact styling to provide a simple view for the available fragment fields. Also, they will show the content of a filled in field as well as a classic wireframe representation of an empty field.

## Components overview

#### Structural components

These components only effect the elements inside of it. These are used to build the structure of the wireframe.

-   [Wireframe wrapper](#wireframe-wrapper)
-   [Container](#container)
-   [Row](#row)
-   [Column](#column)

#### Content components

Components which hold content. They will display the content with compact styling and also show empty states if no content is present.

-   [Title](#title)
-   [Text](#text)
-   [Image](#image)

## Wireframe wrapper

This component should be used as a wrapper for all other wireframe components to ensure consistent styling across all wireframe views.

```html
<x-wireframe> ... </x-wireframe>
```

#### Name and css

The `css` attribute allows you to add custom styles to the head of the document with any content you insert into it. This style is scoped to the current wireframe. For it to be scoped, you always need to provide a name to the wireframe component via the `name` attribute.

```html
<x-wireframe
    name="my-custom-wireframe-name"
    css="
        .very-pretty-class {
            color: yellow;
            background-color: pink;
        }
    "
>
    ...
</x-wireframe>
```

## Container

This components main purpose is to add equal margins left and right of the element, to narrow down the content of the element.

```html
<!-- Wireframe container with the attributes default values -->
<x-wireframe-container size="md" gap="md"> ... </x-wireframe-container>
```

#### Size

The size of the margins of the container. The smaller the margins, the wider the content of the container.

| Value        | Width |
| ------------ | ----- |
| none         | 100%  |
| xs           | 90%   |
| sm           | 80%   |
| md (default) | 70%   |
| lg           | 60%   |
| xl           | 50%   |

#### Gap

Vertical space between the elements inside the container component.

| Value        | Margin  |
| ------------ | ------- |
| none         | 0       |
| xs           | 0.25rem |
| sm           | 0.5rem  |
| md (default) | 1rem    |
| lg           | 1.5rem  |
| xl           | 2rem    |

## Row

This components main purpose is to place all elements inside it on the same row. It will also wrap overflowing element underneath if present. Additionally you can set the horizontal and vertical alignment of this elements children relative to each other.

```html
<!-- Wireframe row with the attributes default values -->
<x-wireframe-row gap="md" justify="start" items="start"> ... </x-wireframe-row>
```

#### Gap

Space around each elements inside the row component.

| Value        | Padding  |
| ------------ | -------- |
| none         | 0        |
| xs           | 0.125rem |
| sm           | 0.25rem  |
| md (default) | 0.5rem   |
| lg           | 0.75rem  |
| xl           | 1rem     |

#### Justify

Horizontal alignment of this elements children relative to each other.

| Value           | Justify-content |
| --------------- | --------------- |
| start (default) | flex-start      |
| center          | center          |
| between         | space-between   |
| around          | space-around    |
| end             | flex-end        |

#### Items

Vertical alignment of this elements children relative to each other.

| Value           | Align-items |
| --------------- | ----------- |
| start (default) | flex-start  |
| center          | center      |
| baseline        | baseline    |
| stretch         | stretch     |
| end             | flex-end    |

## Column

This component should be used as a direct child of wireframe rows. It's made to hold any content and takes into account the gaps applied by wireframe rows.

```html
<!-- Wireframe column with the attributes default values -->
<x-wireframe-column width="100%" gap="md"> ... </x-wireframe-column>
```

#### Width

The width of the column element. You can pass any valid width to this attribute.

| Value          | Width |
| -------------- | ----- |
| 100% (default) | 100%  |
| 70vw           | 70vw  |
| 500px          | 500px |
| ...            | ...   |

#### Gap

Vertical space between the column elements children.

| Value        | Margin  |
| ------------ | ------- |
| none         | 0       |
| xs           | 0.25rem |
| sm           | 0.5rem  |
| md (default) | 1rem    |
| lg           | 1.5rem  |
| xl           | 2rem    |

## Title

```html
<!-- Wireframe title with the attributes default values -->
<x-wireframe-title lines="1" align="left"> Content goes here ... </x-wireframe-title>
```

#### Lines

Amount of lines which will be displayed by the component. Exceeding lines will be hidden.

| Value       | Amount of lines |
| ----------- | --------------- |
| 1 (default) | 1               |
| 2           | 2               |
| 3           | 3               |
| ...         | ...             |

#### Align

The text alignment inside this component.

| Value          | Text align |
| -------------- | ---------- |
| left (default) | left       |
| center         | center     |
| right          | right      |

## Text

```html
<!-- Wireframe text with the attributes default values -->
<x-wireframe-text lines="3" align="left"> Content goes here ... </x-wireframe-text>
```

#### Lines

Amount of lines which will be displayed by the component. Exceeding lines will be hidden.

| Value       | Amount of lines |
| ----------- | --------------- |
| 1           | 1               |
| 2           | 2               |
| 3 (default) | 3               |
| ...         | ...             |

#### Align

The text alignment inside this component.

| Value          | Text align |
| -------------- | ---------- |
| left (default) | left       |
| center         | center     |
| right          | right      |

## Image

```html
<!-- Wireframe image with the attributes default values -->
<x-wireframe-image type="image" size="md"> Image url goes here ... </x-wireframe-image>

<!-- Wireframe image with type set to custom -->
<x-wireframe-image type="custom"> Any HTML goes here ... </x-wireframe-image>
```

#### Type

The way in which the components default slot is rendered. Values `image` and `background` already provide default views for this component.

| Value           | Element                                     |
| --------------- | ------------------------------------------- |
| image (default) | Image element                               |
| background      | Div with covering/centered background image |
| custom          | Insert any HTML inside the component slot   |

#### Size

The size of the image. If `type` is set to background, this size will be specified by the height css property, otherwise it will be set by the max-height css property.

| Value        | Height |
| ------------ | ------ |
| xs           | 2rem   |
| sm           | 4rem   |
| md (default) | 6rem   |
| lg           | 8rem   |
| xl           | 12rem  |
| 2xl          | 16rem  |

## Additional wireframe attributes

Most components already have specific attributes to change the behaviour of the element and it's content. On top of that, each component has a style and class attribute which you can use to pass css properties or classes to the element just as you would in plain HTML.

### Style

```html
<!-- A wireframe column with a red background -->
<x-wireframe-column style="background-color: red;"></x-wireframe-column>

<!-- A wireframe image with an oval shape -->
<x-wireframe-image style="border-radius: 9999px;"></x-wireframe-image>
```

### Class

Tip: use this attribute in combination with the wireframe component `css` & `name` attributes. This will allow you to include custom styles, as well as Tailwindcss classes which were purged in Chief.

```html
<x-wireframe
    name="my-custom-wireframe"
    css="
        .bg-very-specific-brand-color {
            background-color: red;
        }
    "
>
    <x-wireframe-column class="bg-very-specific-brand-color"></x-wireframe-column>
</x-wireframe>
```

Keep in mind that these wireframe components also contain their own specific styling. These might conflict or have more importance than the styling passed by this attribute.
