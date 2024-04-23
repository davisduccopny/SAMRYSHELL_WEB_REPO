<?php
    class CommentModel {
        private $conn;
        public function __construct($conn) {
            $this->conn = $conn;
        }
        public function insertComment($content, $email,$name,  $blog_id) {
            $query = "INSERT INTO comment (content,email,name, blog_id) VALUES (?, ?,?, ?)";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("sssi", $content, $email,$name, $blog_id);
            return $stmt->execute();
        }
        public function ShowComment($blog_id, $limit) {
            $id=$content=$email=$name=$created_at=null;
            $query = "SELECT id, content, email,name, created_at FROM comment WHERE blog_id = ?
            ORDER BY created_at DESC
            LIMIT $limit";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $blog_id);
            $stmt->execute();
            $stmt->bind_result($id, $content, $email, $name, $created_at);
            
            $comments = array();
            while ($stmt->fetch()) {
                $comment = array(
                    'id' => $id,
                    'content' => $content,
                    'email' => $email,
                    'name'=> $name,
                    'created_at'=> $created_at
                );
                $comments[] = $comment;
            }
            
            return $comments;
        }
        public function ShowComment_loadmore($blog_id, $comment_id) {
            $id=$content=$email=$name=$created_at=null;
            $query = "SELECT id, content, email,name, created_at FROM comment WHERE blog_id = ?  AND id < ?
            ORDER BY created_at DESC
            LIMIT 3";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("ii", $blog_id, $comment_id);
            $stmt->execute();
            $stmt->bind_result($id, $content, $email, $name, $created_at);
            
            $comments = array();
            while ($stmt->fetch()) {
                $comment = array(
                    'id' => $id,
                    'content' => $content,
                    'email' => $email,
                    'name'=> $name,
                    'created_at'=> $created_at
                );
                $comments[] = $comment;
            }
            
            return $comments;
        }
        public function ShowComment_count($blog_id) {
            $id=$content=$email=$name=$created_at=null;
            $query = "SELECT id, content, email,name, created_at FROM comment WHERE blog_id = ?
            ORDER BY created_at DESC";
            $stmt = $this->conn->prepare($query);
            $stmt->bind_param("i", $blog_id);
            $stmt->execute();
            $stmt->bind_result($id, $content, $email, $name, $created_at);
            
            $comments = array();
            while ($stmt->fetch()) {
                $comment = array(
                    'id' => $id,
                    'content' => $content,
                    'email' => $email,
                    'name'=> $name,
                    'created_at'=> $created_at
                );
                $comments[] = $comment;
            }
            
            return $comments;
        }
        
        

    }

?>