#  Chief application skeleton

## Project checklist
At Think Tomorrow we make use of a [Project checklist](https://github.com/thinktomorrow/chief-launch-checklist) to ensure all aspects of a project, small or large, are ready for their release.

## Chief setup
1. fetch the latest code from Chief repo. (! drop the git history)
2. Save your client logo as `/public/assets/img/<project>logo.png` where `<project>` is the lowercased name of the project.
3. In the root of your project run `php artisan chief:setup` to complete the setup. 



## Media library

- to activate the image optimization out of the box you will need to install the optimizers on your system (using homebrew on mac):
        brew install jpegoptim
        brew install optipng
        brew install pngquant
        brew install svgo
        brew install gifsicle