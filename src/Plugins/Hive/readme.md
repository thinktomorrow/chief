# Chief AI assistant for text fields

## Install

1. Add the HiveServiceProvider to your list of providers in `bootstrap/providers.php`:

```php
\Thinktomorrow\Chief\Plugins\Hive\HiveServiceProvider::class,
```

Voeg de `chief-hive` config toe aan je project.

```bash 
php artisan vendor:publish --tag=chief-hive-config
```

2. Install the necessary composer packages:

```bash 
composer require openai-php/client
```

3. Maak een API key aan bij OpenAI en voeg deze toe aan je `.env` bestand.
   Log hiervoor in op https://platform.openai.com/.

```
CHIEF_HIVE_CHATGPT_API_KEY=sk-proj-Pr6w_WnLh...
```

4. Voeg de project context toe aan je chief-hive config of `.env` bestand.

```
CHIEF_HIVE_PROJECT_CONTEXT="Jij bent een expert copywriter en helpt mij met het schrijven van teksten voor op mijn website. Houd de teksten kort en krachtig."
```


