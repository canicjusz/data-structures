<?php

function dwd(...$values)
{
  echo '<pre>';
  foreach ($values as $value) {
    print_r($value);
  }
  echo '<pre>';
  echo '<br>';
}