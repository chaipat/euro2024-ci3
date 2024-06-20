runtime:
	docker build --no-cache \
		-t 074531296166.dkr.ecr.ap-southeast-1.amazonaws.com/worldcup2022-web:runtime-v1.0 \
		-f .docker/runtime.Dockerfile \
		.
	docker push 074531296166.dkr.ecr.ap-southeast-1.amazonaws.com/worldcup2022-web:runtime-v1.0

dev:
	docker build --no-cache \
		--build-arg APP_ENV=development \
		-t 074531296166.dkr.ecr.ap-southeast-1.amazonaws.com/worldcup2022-web:dev \
		-f .docker/Dockerfile \
		.
	docker push 074531296166.dkr.ecr.ap-southeast-1.amazonaws.com/worldcup2022-web:dev


prod:
	docker build --no-cache \
		--build-arg APP_ENV=production \
		-t 074531296166.dkr.ecr.ap-southeast-1.amazonaws.com/worldcup2022-web:prod \
		-f .docker/Dockerfile \
		.
	docker push 074531296166.dkr.ecr.ap-southeast-1.amazonaws.com/worldcup2022-web:prod
