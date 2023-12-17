<?php
include_once __DIR__ . '\lib\ResolutionModel.php';
include_once __DIR__ . '/lib/util.php';


$url_parsed = parse_url($_SERVER['REQUEST_URI']);
$path = ltrim(strstr($url_parsed['path'], 'api.php'), 'api.php');

if ($path != '') {
    $path_param = explode('/', $path)[1];
} else {
    // if method get get all data
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {
        $resolutionModel = new ResolutionModel();
        $resolutions = $resolutionModel->getResolutions();
        $resolutions = json_encode($resolutions);
        echo $resolutions;
        exit();
    }
    // if method post save data
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $request_raw = file_get_contents('php://input');
        $request = json_decode($request_raw, true);
        // echo var_dump($request);
        $resolutionModel = new ResolutionModel();
        $goal = $request['goal'];
        $start_time = $request['time_start'];
        $detail = $request['detail'];
        $priority = $request['priority'];
        $result = $resolutionModel->addResolution($goal, $start_time, $detail, $priority);

        if ($result) {
            echo json_encode(array('status' => 'success', "data" => $result));
        } else {
            echo json_encode(array('status' => 'failed'));
        }
        exit();
    }
    //if method delete destroy data
    if ($_SERVER['REQUEST_METHOD'] == 'DELETE') {
        $request_raw = file_get_contents('php://input');
        $request = json_decode($request_raw, true);
        $resolutionModel = new ResolutionModel();
        $id = $request['id'];
        $result = $resolutionModel->deleteResolution($id);
        if ($result) {
            echo json_encode(array('status' => 'success', "data" => $result));
        } else {
            echo json_encode(array('status' => 'failed'));
        }
        exit();
    }
}


// if (isset($_POST)) {
//     $resolutionModel = new ResolutionModel();
//     $resolutions = $resolutionModel->getResolutions();
//     $resolutions = json_encode($resolutions);
//     echo $resolutions;
// }

// echo "<pre>";
// print_r($url_parsed);
// echo "</pre>";
?>