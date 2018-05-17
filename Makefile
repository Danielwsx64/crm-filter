development:
	devops/scripts/development/execute
development-root:
	devops/scripts/development/execute root
development-run:
	docker-compose up
serve:
	php artisan serve --host=0.0.0.0
down:
	docker-compose down
setup-db:
	devops/scripts/development/database
