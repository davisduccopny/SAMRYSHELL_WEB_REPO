<?php 
    session_start();
    require('../admin-page/config/database.php');
    require('../admin-page/model/comment_model.php');
    $CommentModel = new CommentModel($conn);
    if(isset($_POST['insert_comment'])){
        $email = $_POST['email'];
        $name = $_POST['name'];
        $content = $_POST['content'];
        $blog_id = $_POST['blog_id'];
        $insertComment =$CommentModel->insertComment($content,$email,$name, $blog_id);
        if ($insertComment && $email==$_SESSION['email_customer']){
            echo 'success_insert_comment';
        }
        else {
            echo 'error_insert_comment';
        }
    }
    if (isset($_GET['load_more_comment'])){
        $blog_id = $_GET['blog_id'];
        $comment_id = $_GET['Comment_id'];
        $loadcomment = $CommentModel->ShowComment_loadmore($blog_id,$comment_id);
        if ($loadcomment){
            $html = '';
            foreach ($loadcomment as $comment) {
                $html .= '<div class="single-comment d-block d-md-flex">';
                $html .= '<div class="comment-author">';
                $html .= '<a href="#"><img src="assets/img/user-comment_81638.png" class="img-fluid" alt="' . $comment['name'] . '"/></a>';
                $html .= '</div>';
                $html .= '<div class="comment-info mt-3 mt-md-0">';
                $html .= '<div class="comment-info-top d-flex justify-content-between">';
                $html .= '<h3>' . $comment['name'] . '</h3>';
                $html .= '<a href="#" class="btn-add-to-cart"><i class="fa fa-reply"></i> Reply</a>';
                $html .= '</div>';
                $html .= '<a href="#" class="comment-date">' .date("d F Y, h:i A", strtotime($comment['created_at'])). '</a>';
                $html .= '<p>' . $comment['content'] . '</p>';
                $html .= '</div>';
                $html .= '</div>';
               $last_comment_id = $comment['id'];
            }
            $html.='<input type="hidden" id="Comment_id_last" value="'.$last_comment_id.'">';
            echo $html;
        }
        else{
            echo 'error_load_comment';
        }
    }
?>