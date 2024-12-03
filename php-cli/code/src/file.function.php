<?php

// function readAllFunction(string $address) : string {
function readAllFunction(array $config) : string {
    $address = $config['storage']['address'];

    if (file_exists($address) && is_readable($address)) {
        $file = fopen($address, "rb");
        
        $contents = ''; 
    
        while (!feof($file)) {
            $contents .= fread($file, 100);
        }
        
        fclose($file);
        return $contents;
    }
    else {
        return handleError("Файл не существует");
    }
}

// function addFunction(string $address) : string {
    
    function addFunction(array $config): string {
        $address = $config['storage']['address'];
    
        $name = readline("Введите имя: ");
        if (!validateName($name)) {
            return handleError("Неправильный формат имени. Используйте только буквы и пробелы.");
        }
    
        $date = readline("Введите дату рождения в формате ДД-ММ-ГГГГ: ");
        if (!validateData($date)) {
            return handleError("Неправильный формат даты.");
        }
    
        $data = $name . ", " . $date . "\r\n";
    
        if (file_exists($address)) {
            $contents = file_get_contents($address);
            if (strpos($contents, $data) !== false) {
                return handleError("Такая запись уже существует.");
            }
        }
    
        $fileHandler = fopen($address, 'a');
        if (!$fileHandler) {
            return handleError("Ошибка открытия файла для записи.");
        }
    
        if (fwrite($fileHandler, $data)) {
            fclose($fileHandler);
            return "Запись '$data' добавлена в файл '$address'";
        } else {
            fclose($fileHandler);
            return handleError("Произошла ошибка записи. Данные не сохранены.");
        }
    }
    

// function clearFunction(string $address) : string {
function clearFunction(array $config) : string {
    $address = $config['storage']['address'];

    if (file_exists($address) && is_readable($address)) {
        $file = fopen($address, "w");
        
        fwrite($file, '');
        
        fclose($file);
        return "Файл очищен";
    }
    else {
        return handleError("Файл не существует");
    }
}

function findBirthdays(array $config): string {
    $address = $config['storage']['address'];
    
    if (!file_exists($address) || !is_readable($address)) {
        return handleError("Файл не найден или недоступен для чтения.");
    }

    $today = date('d-m');
    $fileHandler = fopen($address, 'r');
    $found = [];
    
    while (($line = fgets($fileHandler)) !== false) {
        $parts = explode(", ", trim($line));
        if (count($parts) === 2) {
            [$name, $date] = $parts;
            if (substr($date, 0, 5) === $today) {
                $found[] = $name;
            }
        }
    }
} 

function helpFunction() {
    return handleHelp();
}

function readConfig(string $configAddress): array|false{
    return parse_ini_file($configAddress, true);
}

function readProfilesDirectory(array $config): string {
    $profilesDirectoryAddress = $config['profiles']['address'];

    if(!is_dir($profilesDirectoryAddress)){
        mkdir($profilesDirectoryAddress);
    }

    $files = scandir($profilesDirectoryAddress);

    $result = "";

    if(count($files) > 2){
        foreach($files as $file){
            if(in_array($file, ['.', '..']))
                continue;
            
            $result .= $file . "\r\n";
        }
    }
    else {
        $result .= "Директория пуста \r\n";
    }

    return $result;
}

function readProfile(array $config): string {
    $profilesDirectoryAddress = $config['profiles']['address'];

    if(!isset($_SERVER['argv'][2])){
        return handleError("Не указан файл профиля");
    }

    $profileFileName = $profilesDirectoryAddress . $_SERVER['argv'][2] . ".json";

    if(!file_exists($profileFileName)){
        return handleError("Файл $profileFileName не существует");
    }

    $contentJson = file_get_contents($profileFileName);
    $contentArray = json_decode($contentJson, true);

    $info = "Имя: " . $contentArray['name'] . "\r\n";
    $info .= "Фамилия: " . $contentArray['lastname'] . "\r\n";

    return $info;
}

function deleteRecord(array $config): string {
    $address = $config['storage']['address'];
    
    if (!file_exists($address) || !is_readable($address) || !is_writable($address)) {
        return handleError("Файл недоступен для изменения.");
    }

    $search = readline("Введите имя или дату (ДД-ММ-ГГГГ) для удаления: ");
    $fileHandler = fopen($address, 'r');
    $tempFile = [];
    $found = false;

    while (($line = fgets($fileHandler)) !== false) {
        if (strpos($line, $search) === false) {
            $tempFile[] = $line;
        } else {
            $found = true;
        }
    }

    fclose($fileHandler);

    if (!$found) {
        return handleError("Запись не найдена.");
    }

    file_put_contents($address, implode("", $tempFile));
    return "Запись с данными '$search' удалена.";
}
