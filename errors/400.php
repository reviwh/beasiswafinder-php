<?php
$res['code'] = 400;
$res['status'] = 'Bad Request';
$res['data']['error'] = 'Request method not allowed';
http_response_code($res['code']);
echo json_encode($res);
