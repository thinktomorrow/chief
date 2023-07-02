# Admin Toast
Shows the logged in admin user the edit link of a frontend page. Also option to toggle preview mode.

## What is preview mode?
Preview mode allows the admin to view offline and hidden fragments and pages. This is handy if you are preparing a draft and
want to preview it before putting online. 

## How it works
On each pageload a script will fetch the html of the toast element. This html is injected in a `<div id="jsChiefToast"></div>`.
The reason for this async retrieval is because on the frontend, all chief admin routes and auth logic is not available. For performance and security reasons, the front and admin logic is kept separate.

## Basic setup
In the project frontend template you need to add two blade directives.
- Place the `@chiefAdminToastMetatags` inside your head tag.
- Place the `@chiefAdminToastScripts` at the end of your body tag.

Optionally you can pass a querySelector to the script directive, if you need to point the element html in a tag of your choice.

## spa page transitions
If you have something as htmx or turbolinks on your projects for page transitions, the default toast setup will not work.
What you can do is basically:
- Place the metatags directive in the html that will be refreshed on each page transition. This way the tags contain the right page references
- Add the `loadAdminToast()` js method in an event listener that triggers after each page transition. This could be something like:
```js
// Example using barba.js
window.barba.hooks.after(() => {
    loadAdminToast();
});
```
