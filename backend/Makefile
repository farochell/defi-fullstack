# Makefile pour gérer uniquement la base de données Symfony + MySQL

# Nom du container PHP
PHP_CONTAINER=php

# Commande docker-compose
DC=docker compose

# ------------------------------------------------------------
# Créer la base de données si elle n'existe pas
# ------------------------------------------------------------
.PHONY: db-create
db-create:
	$(DC) exec $(PHP_CONTAINER) php bin/console doctrine:database:create --if-not-exists

.PHONY: db-create-test
db-create-test:
	$(DC) exec $(PHP_CONTAINER) php bin/console doctrine:database:create --if-not-exists --env=test

# ------------------------------------------------------------
# Appliquer les migrations
# ------------------------------------------------------------
.PHONY: db-migrations db-migrate db-migrations-test db-migrate-test
db-migrations:
	$(DC) exec $(PHP_CONTAINER) php bin/console make:migration --no-interaction

db-migrate:
	$(DC) exec $(PHP_CONTAINER) php bin/console doctrine:migrations:migrate --no-interaction

db-migrations-test:
	$(DC) exec $(PHP_CONTAINER) php bin/console make:migration --no-interaction --env=test

db-migrate-test:
	$(DC) exec $(PHP_CONTAINER) php bin/console doctrine:migrations:migrate --no-interaction --env=test

# ------------------------------------------------------------
# Mettre à jour le schema directement (si pas de migrations)
# ------------------------------------------------------------
.PHONY: db-schema-update db-schema-update-test
db-schema-update:
	$(DC) exec $(PHP_CONTAINER) php bin/console doctrine:schema:update --force
db-schema-update-test:
	$(DC) exec $(PHP_CONTAINER) php bin/console doctrine:schema:update --force --env=test
# ------------------------------------------------------------
# Initialisation complète de la base pour DEV
# ------------------------------------------------------------
db-init: db-create db-migrations db-migrate
	@echo "✅ Base de données prête"

db-init-test: db-create-test db-migrations-test db-migrate-test
	@echo "✅ Base de données prête"
