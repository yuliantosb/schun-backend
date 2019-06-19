# cara menginstall backend nya

### requirement
- PHP 7.3
- composer
- extension untuk mongodb
- mongodb
- apache atau nginx untuk server

### Langkah Pertama
Silahkan clone repository ini
` git clone https://github.com/yuliantosb/schun-backend.git `
masuk ke branch yang branch-2
` git checkout branch-2 `

### Lalu pastikan directory nya di aplikasi
` cd schun-backend `

### install composer
` composer update `
 
### setting konfigurasi nya di file .env, tetapi copy dulu di file .env.example
` cp .env.example .env `

### buat settingan database nya sesuai dengan settingan database anda
`
DB_CONNECTION=mongodb
DB_HOST=localhost
DB_PORT=27017
DB_DATABASE=databsenya
DB_USERNAME=usernamenya
DB_PASSWORD=passwordnya
` 

### jangan lupa untuk membuat key chiper
` php artisan key:generate `


### lalu bersihkan configurasi nya
` php artisan config:cache `


### lalu run servis nya
` php artisan serve `

lalu buka browser dan ketikan localhost:8000
