<?php 
    class NotificationModel {
        private $conn;
        public function __construct($conn) {
            $this->conn = $conn;
        }
        public function getNotification() {
            $query = "SELECT id, email, message, created_at FROM notification";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            
            $id = $email = $message = $created_at = null;
            
            $stmt->bind_result($id, $email, $message, $created_at);
            
            $notifications = [];
            
            while ($stmt->fetch()) {
                $notifications[] = [
                    'id' => $id,
                    'email' => $email,
                    'message' => $message,
                    'created_at' => $created_at
                ];
            }
            
            return $notifications;
        }
        
        public function deleteNotification ($not_id){
            $query = "DELETE FROM notification WHERE id = ? ";
            $stmt = $this->conn->prepare($query);
            $stmt -> bind_param ("i", $not_id);
            $stmt -> execute();
        }

    }

?>