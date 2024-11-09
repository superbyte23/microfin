<?php 

function jsonResponse($data, $status = 200) {
  header('Content-Type: application/json', true, $status);
  echo json_encode($data);
  exit();
}


