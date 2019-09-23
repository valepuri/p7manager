#php artisan config:clear
#aggiungi nel file app.php -> providers -> Valepuri\P7Manager\P7ManagerServiceProvider::class e l'alias
#lanciare il comando: php artisan vendor:publish e modificare i parametri delle colonne del DB nel file config/p7manager.php
#modificare i capi del db che si vogliono creare per memorizzare i pdf estratti
#lanciare la migration: php artisan migrate