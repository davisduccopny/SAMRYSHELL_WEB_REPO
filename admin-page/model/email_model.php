<?php
 class EmailModal {
    private $conn;
    public function __construct($conn) {
        $this->conn = $conn;
    }
    public function editemail($host, $port, $addressserver, $password, $business_name, $subject_forgot, $subject_advertse, $content_forgot, $content_advertse){
        $id = null;
        $query ='SELECT id FROM email_setting LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stmt->bind_result($id);
        $stmt->fetch();
        $stmt->close();
    
        if ($id !== null) {
            
            $update_query = 'UPDATE email_setting SET host=?, port=?, address_server=?, password=?, business_name=?, subject_forgot=?, subject_advertse=?, content_forgot=?, content_advertse=? WHERE id=?';
            $update_stmt = $this->conn->prepare($update_query);
            $update_stmt->bind_param('sssssssssi', $host, $port, $addressserver, $password, $business_name, $subject_forgot, $subject_advertse, $content_forgot, $content_advertse, $id);
            $success = $update_stmt->execute();
    
            if ($success) {
                return true; 
            } else {
                return false; 
            }
        } else {
            return false; 
        }
    }
    public function get_email_settings(){
        $id = $host = $port = $addressserver = $password = $business_name = $subject_forgot = $subject_adverse = $content_forgot = $content_advertse = null;
        $query = 'SELECT id, host, port, address_server, password, business_name, subject_forgot, subject_advertse, content_forgot, content_advertse FROM email_setting LIMIT 1';
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $stmt->bind_result($id, $host, $port, $addressserver, $password, $business_name, $subject_forgot, $subject_adverse, $content_forgot, $content_advertse);
        
        $stmt->fetch();
    
        if ($id !== null) {
            return array(
                'id' => $id,
                'host' => $host,
                'port' => $port,
                'addressserver' => $addressserver,
                'password' => $password,
                'business_name' => $business_name,
                'subject_forgot' => $subject_forgot,
                'subject_advertse' => $subject_adverse,
                'content_forgot' => $content_forgot,
                'content_advertse' => $content_advertse
            );
        } else {
            return false; // Trả về false nếu không tìm thấy bất kỳ dòng nào trong bảng email_setting
        }
    }
    
    


    public function executeAndGetHTML($filePath) {
        if (!file_exists($filePath)) {
            return false;
        }
    
        // Thực thi tệp PHP
        ob_start();
        include $filePath;
    
        // Lấy nội dung HTML từ bộ đệm đầu ra và mã hóa HTML
        $htmlContent = ob_get_contents();
    
        return $htmlContent;
    }
    
    

    
    
 }

?>