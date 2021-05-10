# Chat CQRS

## Installation

- Clone project

- Setup git hooks:

```shell
git config core.hooksPath .githooks
```

- Setup alias to fix code using fixer

```shell
alias 'chat-fix'='git diff --cached --name-only --diff-filter=AM | tr "\n" " " | awk '\''{print "cd docker && docker-compose exec -T app bash -c \"./vendor/bin/php-cs-fixer fix --config=\".php_cs\" --diff --path-mode=intersection --using-cache=no "$0"\""}'\''| bash'
```

- Create `.env`:

```shell
cp .env.example .env
```

- Setup Docker:

```shell
cd docker && cp .env.example .env
docker-compose up -d
```

- Setup backend:

```shell
docker-compose exec -T app bash -c 'composer install && php artisan migrate:refresh --seed'
```
