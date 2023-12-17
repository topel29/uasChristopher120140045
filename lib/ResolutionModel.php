<?php
include_once __DIR__ . '/db.php';
include_once __DIR__ . '/util.php';
class ResolutionModel
{
    private $db;
    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }
    public function getResolutions()
    {
        $sql = "SELECT * FROM resolutions";
        $result = $this->db->query($sql);
        $resolutions = [];
        while ($row = $result->fetch_assoc()) {
            $resolutions[] = $row;
        }
        return $resolutions;
    }
    public function getResolution($id)
    {
        $sql = "SELECT * FROM resolutions WHERE id = $id";
        $result = $this->db->query($sql);
        $resolution = $result->fetch_assoc();
        return $resolution;
    }
    public function addResolution($goal, $start_time, $detail, $priority)
    {
        $browser = get_browser_name($_SERVER['HTTP_USER_AGENT']);
        $ip = get_ip_address();
        $sql = "INSERT INTO resolutions (goal, start_time,detail,priority,ua_browser,ua_ip)
         VALUES ('$goal', '$start_time', '$detail', '$priority','$browser','$ip')";
        $result = $this->db->query($sql);
        return $result;
    }
    public function updateResolution($id, $goal, $start_time, $detail, $priority)
    {
        $sql = "UPDATE resolutions SET goal = '$goal',start_time = '$start_time', detail = '$detail', priority = '$priority' WHERE id = $id";
        $result = $this->db->query($sql);
        return $result;
    }
    public function deleteResolution($id)
    {
        $sql = "DELETE FROM resolutions WHERE id = $id";
        $result = $this->db->query($sql);
        return $result;
    }
}
?>