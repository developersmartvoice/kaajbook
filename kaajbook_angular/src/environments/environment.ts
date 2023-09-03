export let environment: { apiUrl: string; production: boolean };
environment = {
	production: false,
	// use this apiUrl for local environment
	// apiUrl: 'http://127.0.0.1:8000'

	// use this apiUrl for live server "kaajbook.com"
	apiUrl: 'http://kaajbook.com/kaajbook_laravel/public'

	// use this apiUrl for live sub domain "test.kaajbook.com"
	// apiUrl: 'http://test.kaajbook.com/kaajbook_laravel/public'

};
