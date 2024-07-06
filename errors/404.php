<?php
$res['code'] = 404;
$res['status'] = 'Not Found';
$res['data']['error'] = 'No data found';
http_response_code($res['code']);
echo json_encode($res);
