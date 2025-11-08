.PHONY: help install update test test-coverage rector rector-dry quality check clean

.DEFAULT_GOAL := help

help: ## Show this help message
	@echo 'Usage: make [target]'
	@echo ''
	@echo 'Available targets:'
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "  \033[36m%-20s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

install: ## Install dependencies
	composer install

update: ## Update dependencies
	composer update

test: ## Run tests
	vendor/bin/phpunit

test-coverage: ## Run tests with coverage report (HTML)
	XDEBUG_MODE=coverage vendor/bin/phpunit --coverage-html coverage

rector: ## Run Rector to refactor code
	vendor/bin/rector process

rector-dry: ## Run Rector in dry-run mode (preview changes)
	vendor/bin/rector process --dry-run

jack: ## Check for outdated dependencies
	vendor/bin/jack

jack-update: ## Update composer.json with latest versions
	vendor/bin/jack raise-to-installed

quality: rector test ## Run quality checks (rector + tests)

check: rector-dry test ## Check code quality without making changes

clean: ## Clean generated files
	rm -rf vendor coverage .phpunit.cache

ci: install rector-dry test ## Run CI pipeline locally
