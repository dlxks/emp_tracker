# emp_tracker
 
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:fresh --seed
npm install --force
npm install -g npm@9.6.4
*npm install chart.js @j-t-mcc/vue3-chartjs --legacy-peer-deps
php artisan serve
npm run dev