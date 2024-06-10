<?php
function translate($string,$echo=true) {
    global $translations;
    $lang = getenv('LANG');
    if ($echo) {
        echo isset($translations[$lang][$string]) ? $translations[$lang][$string] : $string;
    }
    else {
        return isset($translations[$lang][$string]) ? $translations[$lang][$string] : $string;
    }
    
}

$translations = array(
    'cs' => array(
        'Users' => 'Uživatelé',
        'Add User' => 'Přidat uživatele',
        'User' => 'Uživatel',
        'Created at' => 'Vytvořeno',
        'Last login' => 'Poslední přihlášení',
        'Action' => 'Akce',
        'Delete' => 'Smazat',
        'Email' => 'E-mail',
        'Enter complete email address.' => 'Napište kompletní emailovou adresu',
        'Close' => 'Zavřít',
        'Cancel' => 'Storno',
        'Add' => 'Přidat',
        'Delete User' => 'Smazat uživatele',
        'Operation result' => 'Výsledek operace',
        'Operation successful' => 'Operace proběhla úspěsně',
        'Unknown error' => 'Neznámá chyba',
        'User successfully added' => 'Uživatel byl úspěšně přidán',
        'User successfully deleted' => 'Uživatel byl úspěšně smazán',
        'User does not exist' => 'Uživatel neexistuje',
        'User already exist' => 'Tento uživatel již existuje',
        'Email is invalid' => 'E-mail je neplatný',
        'Request is missing data' => 'Požadavek postrádá data',
        'Are you sure you want to delete this user?' => 'Jste si jisti, že chcete smazat tohoto uživatele?',
        )
);


?>