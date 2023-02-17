start-dev:
	docker start smartfreelancer-mysql
	symfony server:start &
	npm run dev --prefix client/ &

stop-dev:
	docker stop smartfreelancer-mysql
	sudo killall -9 node
	sudo killall -9 symfony
