SHELL := /bin/bash
.SHELLFLAGS += -e

# Common ----------------------------------------------------------------
.PHONY: purge
purge:
	# Because it's a universal script for Symfony and Next.js or React projects
	rm -f pack.tar .env.local.php *cache .*cache .revision public/.revision next-env.d.ts
	rm -Rf .next node_modules public/build var/cache/* var/log/* vendor

.PHONY: install
install: compose_start
	symfony composer validate --strict
	symfony composer install

.PHONY: refresh
refresh: down purge install

.PHONY: down
down:
	symfony server:stop
	docker compose down --volumes

.PHONY: compose_start
compose_start:
	docker compose up -d
	sleep 5

.PHONY: db_refresh
db_refresh:
	symfony console doctrine:schema:drop --full-database --force $(ENV_PARAM)
	symfony console doctrine:migration:migrate --no-interaction $(ENV_PARAM)
	symfony console doctrine:fixtures:load --no-interaction $(ENV_PARAM)

.PHONY: health_check
health_check:
	if [[ "$(DOMAIN)" == "" ]]; then echo "Missing DOMAIN parameter"; exit 1; fi
	curl https://$(DOMAIN)/health

.PHONY: can-i-deploy
can-i-deploy:
	if [[ "$(SSH_HOST)" == "" ]]; then echo "Missing SSH_HOST parameter"; exit 1; fi
	if [[ "$(DOMAIN)" == "" ]]; then echo "Missing URL parameter"; exit 1; fi
	if [[ `ssh $(SSH_HOST) "cat ~/domains/$(DOMAIN)/public_html/current/.revision"` == `cat .revision` ]]; then \
		echo "Cannot deploy the same repository revision"; \
		exit 1; \
	fi

.PHONY: git-revision
git-revision:
	echo `git describe --tags --always` > .revision

.PHONY: deploy
deploy: refresh git-revision
	make can-i-deploy DOMAIN=$(DOMAIN) SSH_HOST=$(SSH_HOST)
	make pack ENV_PARAM=$(ENV_PARAM)

	if [[ "$(ENV_PARAM)" == "" ]]; then echo "Missing ENV parameter"; exit 1; fi
	if [[ "$(DOMAIN)" == "" ]]; then echo "Missing DOMAIN parameter"; exit 1; fi
	if [[ "$(SSH_HOST)" == "" ]]; then echo "Missing SSH_HOST parameter"; exit 1; fi

	ssh $(SSH_HOST) "cd ~/domains/$(DOMAIN)/public_html " \
		'&& for version in ./v[0-9]*.[0-9]*; do if [ "$$version" != "./$$(readlink ./current | cut -d "/" -f 1)" ]; then rm -Rf "$$version"; fi; done' \
		"&& unlink current" \
		"; rm -Rf `cat .revision`" \
		"&& mkdir `cat .revision`"

	scp pack.tar $(SSH_HOST):"~/domains/$(DOMAIN)/public_html/`cat .revision`"

	ssh $(SSH_HOST) "cd ~/domains/$(DOMAIN)/public_html/"`cat .revision`" " \
		"&& tar xf pack.tar " \
		"&& php ./bin/console doctrine:migration:migrate --no-interaction " \
		"&& php ./bin/console cache:clear " \
		"&& php ./bin/console cache:warmup " \
		"&& rm -f pack.tar " \
		"&& cd .. && ln -sf "`cat .revision`" current " \

	make refresh

	make health_check DOMAIN=$(DOMAIN) TOKEN=$(TOKEN)

.PHONY: pack
pack:
	if [[ "$(ENV_PARAM)" == "" ]]; then echo "Missing ENV_PARAM parameter"; exit 1; fi

	rm -Rf pack.tar var/log/*

	composer dump-env $(ENV_PARAM)

	tar \
		--exclude='docker' --exclude='*DS_Store' \
		--exclude='pack.tar' --exclude='.git' \
		--exclude='.platform' --exclude='.gitignore' \
		--exclude='.platform.app.yaml' --exclude='README.md' \
		--exclude='keys' --exclude='node_modules' --exclude='tmp' \
		-cf pack.tar .

# Dev -------------------------------------------------------------------
.PHONY: dev
dev: refresh git-revision
	symfony server:start --daemon --port=8001
	open https://127.0.0.1:8001/api
	make db_refresh ENV_PARAM=$(ENV_PARAM)
# EndDev ----------------------------------------------------------------

.PHONY: deploy-prod
deploy-prod:
	make deploy DOMAIN='influbrocker-api.ittools.pl' ENV_PARAM=prod SSH_HOST=h5
