<?php

function parseCommand() : string {
    $functionName = 'helpFunction';
    
    if (isset($_SERVER['argv'][1])) {
        $functionName = match ($_SERVER['argv'][1]) {
            'read-all' => 'readAllFunction',
            'add' => 'addFunction',
            'clear' => 'clearFunction',
            'read-profiles' => 'readProfilesDirectory',
            'read-profile' => 'readProfile',
            'find-birthdays' => 'findBirthdays',
            'delete-record' => 'deleteRecord',
            'help' => 'helpFunction',
            default => 'helpFunction'
        };
    }

    return $functionName;
}

function handleHelp() : string {
    $help = "Программа работы с файловым хранилищем \r\n";

    $help .= "Порядок вызова\r\n\r\n";
    $help .= "php /code/app.php [COMMAND] \r\n\r\n";
    $help .= "Доступные команды: \r\n";
    $help .= "read-all - чтение всего файла \r\n";
    $help .= "add - добавление записи \r\n";
    $help .= "clear - очистка файла \r\n";
    $help .= "read-profiles - вывести список профилей пользователей \r\n";
    $help .= "read-profile - вывести профиль выбранного пользователя \r\n";
    $help .= "find-birthdays - найти дни рождения сегодня \r\n";
    $help .= "delete-record - удалить запись по имени или дате \r\n";
    $help .= "help - помощь \r\n";

    return $help;
}