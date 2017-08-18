#  Chief application skeleton

## Project checklist
At Think Tomorrow we make use of a [Project checklist](https://github.com/thinktomorrow/chief-launch-checklist) to ensure all aspects of a project, small or large, are ready for their release.

## Getting started
Download the latest version of the Chief application
```bash
git clone https://github.com/thinktomorrow/chief.git --depth=1 <PROJECTNAME>
```

Remove the local `.git` folder and setup the new git repository
```bash 
# inside your <PROJECTNAME> folder
rm -r .git

# create a fresh git
git init
git add .
git commit -m"First commit"
git remote add origin git@github.com:thinktomorrow/<PROJECTNAME>.git
git push -u origin master
```

Install dependencies
```bash
composer install
```

Next, run chief setup. This will set the proper environment files
```bash
php artisan chief:setup
```

Run migrations and seeders
```bash
php artisan migrate --seed
```

Add yourself as superadmin user
```bash
php artisan admin:create
```

Install front end dependencies via `yarn`.
```bash
yarn
```

## What's next?
Save your client logo as `/public/assets/img/<project>logo.png` where `<project>` is the lowercased name of the project.

To activate the media image optimization you will need to install the optimizers on your system (using homebrew on mac):
- `brew install jpegoptim`
- `brew install optipng`
- `brew install pngquant`
- `brew install svgo`
- `brew install gifsicle`