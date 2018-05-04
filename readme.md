#  Chief application skeleton

## Project checklist
At Think Tomorrow we make use of a [Project checklist](https://github.com/thinktomorrow/chief-launch-checklist) to ensure all aspects of a project, small or large, are ready for their release.

## Getting started
In order to develop on the chief CMS package, you should first clone the repo to a local copy:
```bash
git clone https://github.com/thinktomorrow/chief.git
```

Next, make sure you have a local environment file. Copy the `.env.example` to `.env` and run `php artisan key:generate` to add a cypher key.

Install dependencies
```bash
composer install
```

For development you can clear and reset the database with the default setup with following command
```bash
php artisan chief:db-refresh
```
This command does the following:
- Flushes and recreates the entire database! **WARNING: This is a destructive command and should only to be used on your local machine. Make sure you are not connected to the production database.**
- Setup of default roles and permissions.
- Adds our devteam as admin users with full access.
- It also asks you for a generic developer password which will be set on each of the dev accounts. This way you can log in easily with any one of these accounts.

Install front end dependencies via `yarn`.
```bash
yarn
```



