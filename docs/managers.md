# Allow models to be managed
## Creating your model
There are two prerequisites for a model to be manageable in Chief. 
The model should be an Eloquent model and it needs to implements the `Thinktomorrow\Chief\ManagedModels\ManagedModel` interface. 

Although this is enough to get you started, in reality you usually add some extra chief behavior. We'll like to help you on the way with two common contracts:
`Thinktomorrow\Chief\ManagedModels\Presets\Page` and
`Thinktomorrow\Chief\ManagedModels\Presets\Fragment`.

A model that implements the Page contract, is ... 

## Registering your model
Register your model in the boot method of your AppServiceProvider. 

## Selecting the manageable fields

## Selecting the page fragments

## Page or fragment

-----


# Reasoning for new chief manager flow

## Avoid global Managers class to discover the proper manager.
Each manager has its own set of routes

## End-to-end control of request and response flow.
A Manager is now responsible as request and response controller. 
It takes care of the routing, request validation, authorization and redirects

## Each managed model requires its own dedicated manager class.
It used to be possible to manage different models with the same Manager. This is no longer the case.
Now each model requires a corresponding Manager class. While this will likely require you to add more classes to your application, this greatly
simplifies the underlying code architecture.

## Assistant naming convention 
The assistant method naming convention is the method followed by its own classname, e.g.
PreviewAssistant::canPreviewAssistant. This not only avoids method collisions between
traits, it also allows for the plug & play functionality of an Assistant trait.

This applies to the following methods: can, routes and route


## Assistant dependencies
Most of the assistants don't rely on any manager properties and are fairly independent. One expection is the
generic `CrudAssistant` which relies on a `fieldValidator` property to exist on the Manager class. 
// EXAMPLE...

The 'index' route is required since this is expected for the Navigation menu as the route endpoint.



3 types of model commands
- model (collection): VIEWABLE OR NOT e.g. testimonial, member, faqs, blog categories, ...
- fragment (pageblock): VIEWABLE text, image, gallery, 3-column usp banners, latest 3 blogposts, ...
- page: VIEWABLE, VISITABLE  blog, blogpost, homepage, product detail, about us, ...
-> chief:model
-> chief:model --page chief:page
-> chief:model --fragment chief:fragment

chief:page article --fields={title:dynamic,content:text,thumb:image}


Duplicate page (or record)


## Specify fields for the create or edit form
By default, each field is available on both the create and edit forms. 
You can tag the field with resp. _create_ or _edit_ to assign it _only_ to that form.
```php 
// This fields will only appear on the create form
InputField::make('title')->tag('create');

// This fields will only appear on the edit form, and not on the create form.
InputField::make('title')->tag('edit');
```
