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
database:
	devops/scripts/development/database
