start-dev:
	symfony server:start &
	npm run dev --prefix client/ &
