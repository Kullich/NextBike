# Nextbike Zlín

Tato aplikace zobrazuje stanice sdílených kol Nextbike ve Zlíně.

## Instalace

1. Naklonuj tento projekt do svého lokálního počítače.
2. Vytvoř databázi a tabulku pomocí SQL příkazu:

   ```sql
   CREATE TABLE bike_stations (
       id INT PRIMARY KEY AUTO_INCREMENT,
       station_name VARCHAR(255),
       latitude DECIMAL(10, 8),
       longitude DECIMAL(11, 8),
       available_bikes INT,
       timestamp DATETIME
   );
3. Nastav přihlašovací údaje k databázi v config/config.php.
4. Spusť cron/update_data.php, abys poprvé načetl data.
5. Přístup k webové aplikaci získej přes public/index.php.
6. Pro automatickou aktualizaci dat nastav cron job, který bude volat cron/update_data.php každých 10 minut.