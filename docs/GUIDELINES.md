---
layout: default
title: Guidelines
description: chief is a package based cms built on top of the laravel framework.
navigation_weight: 9
---
## Frontend Guidelines

Note: for the server management, please view the SERVER.md documentation.

## Asset build process
For the build process of style and script assets, we make use of npm and bower. The script responsible for the entire workflow is gulp.

### Setup
- In your terminal go to the projectroot and run `npm install`. This will install all the gulp dependencies.

### Workflow
All frontend code development can be done in the `resources/assets` folder. There is a distinction in the front (website frontend) code and the back (website backend) one.
We have a couple of gulp tasks ready for the frontend development:
 
 - `gulp

## Guidelines
Name classes explicitly for their purpose:

```
<!-- bad -->
<div class="red pull-left"></div>
<div class="grid row"></div>
<div class="col-xs-4"></div>

<!-- good -->
<div class="header"></div>
<div class="basket"></div>
<div class="product"></div>
<div class="searchResults"></div>
```

## Resources
- http://maintainablecss.com/chapters/semantics/
