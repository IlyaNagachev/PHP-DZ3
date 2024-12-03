<?php


function validateData(string $date): bool {
  $dateBlocks = explode("-", $date);
  
  if (count($dateBlocks) !== 3) {
      return false;
  }

  [$day, $month, $year] = $dateBlocks;

  if (!checkdate((int)$month, (int)$day, (int)$year)) {
      return false;
  }

  if ($year > date('Y')) {
      return false;
  }

  return true;
}

function validateName(string $name): bool {
  return !empty($name) && preg_match($name);
}