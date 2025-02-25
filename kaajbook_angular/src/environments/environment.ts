export let environment: { apiUrl: string; production: boolean };
environment = {
    // production: false,
    production: true,
    // use this apiUrl for local environment
    // apiUrl: 'http://127.0.0.1:8000'
    // apiUrl: 'http://localhost/kaajbook_laravel/public'

    // use this apiUrl for live server "kaajbook.com"
    apiUrl: "https://kaajbook.com/kaajbook_laravel/public",

    // use this apiUrl for live sub domain "test.kaajbook.com"
    // apiUrl: 'http://test.kaajbook.com/kaajbook_laravel/public'

    // use this apiUrl for live sub domain "smartlab.kaajbook.com"
    // apiUrl: 'http://smartlab.kaajbook.com/kaajbook_laravel/public'
};
