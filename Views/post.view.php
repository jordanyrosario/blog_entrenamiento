<?php

$title = $post->title;

require_once '../Views/template/header.index.php';

function createCommentRow(array $replies, int $level = 0,  $userInfo = null)
{
    $currentUserID = $userInfo['details']['id'];

    $response = '';
    foreach ($replies as $data) {
        $commentUserID = $data->user->getId();
        $canDeleteComments =  in_array('post.deleteComments', $userInfo['details']['permissions']);
        $imageData = isset($data->user->image) ? $data->user->image : null;
        $image = null != $imageData ? '<div class="image rounded-circle"> <img  width="30" src="data:image/jpg;charset=utf8;base64,'.$imageData.'" alt="..." class="img-thumbnail">    </div>' : '';

        $canDelete = '';

        if ( $canDeleteComments || $commentUserID == $currentUserID ) {
            $canDelete = <<< DEL
            <div ><a id = "deleteComment" style="float:right" class="delete btn btn-sm btn-danger" 
            data-commentID=" {$data->getId()} "  >Delete</a></div>
    DEL;
        }

        if (1 == $data->isVisible) {
        $response .= <<< RESP
         <div class="comment">'.$image.
            <div class="user"> {$data->user->name}  {$data->user->lastName} <span class="time"> {$data->creation_date->format('F d, Y H:i A')} </span></div> $canDelete
            <div class="userComment"> {$data->body} </div>
            <div class="reply "><a class="btn btn-sm btn-primary" href="javascript:void(0)" data-commentID=" {$data->getId()} " onclick="reply(this)">REPLY</a></div>
            <hr>
            <div class="replies" style="margin-left:5px;" >'
        RESP;
        } else {
            $response = ' 
        <div class="comment">
            <div class = "bg-light">
                <p class = "text-dark ">This message has been deleted.</p>
            </div>
        <div class="replies" style="padding-left:{$lve}px;" >';
        }

        $children = $data->getChildren()->toArray();

        if (0 != sizeof($children)) {
            $response .= createCommentRow($children, $level + 1, $userInfo);
        }
    }

    $response .= '
                        </div>
            </div>
        ';

    return $response;
}

?>

<main class="container d-flex">
    <div class="row g-5">
        <div class="col-md-8 blog-main">
            <h3 class="pb-4 mb-4 fst-italic border-bottom">
                <?php echo $postCategory->name; ?>
            </h3>

            <article class="blog-post min-vh-50">
                <h2 class="blog-post-title"><?php echo $post->title; ?></h2>
                <p class="blog-post-meta">Published on <?php echo $post->publishDate->format('F d, Y'); ?> by <a
                        href="#">Mark</a></p>

                <p><?php echo $post->description; ?></p>
                <hr>
                <p><?php echo $post->body; ?></p>

            </article>

            <!-- Comments section -->
            <div class="comments">
                <!-- Add comment section -->
                <div class="Add comment" style="margin-top:50px;">
                    <div class="row">
                        <div class="col-md-12">
<form method="post">
    
                                <div class='form-group'>
                                    <textarea class="form-control" id="mainComment" name='body'
                                        placeholder="Add Public Comment" cols="30" rows="2"></textarea> <br>
                                </div>
    
                                <input type="submit" value="Add Comment" id="addComment"
                                    class="btn-primary btn" style="float:right">
</form>


                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <h2><b id="numComments"><?php echo count($comments); ?> Comments</b></h2>
                            <div class="userComments">
                                <?php

                                      echo createCommentRow($comments, 0, $userInfo);

?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Reply html  -->
                <div class="row replyRow" style="display:none">

                    <div class="col-md-12">
                        <br><form action="post">
                            
                            <textarea class="form-control" id="replyComment" placeholder="Add a reply" cols="30"
                                rows="2"></textarea><br>
                            <button style="float:right" class="btn-primary btn" onclick="isReply = true;" id="addReply">Add
                                Reply</button>
                            <button style="float:right" class="btn-danger btn"
                               >Cancel</button>
                        </form>
                    </div>
                </div>

            </div>

        </div>

        <div class="col-md-4 blog-sidebar">
            <div class="position-sticky" style="top: 2rem;">
                <div class="p-4 mb-3 bg-light rounded">
                    <h4 class="fst-italic">About</h4>
                    <p class="mb-0">This blog has been created to share my opinion of the things that surround me</p>
                </div>


            </div>
        </div>
    </div>

</main>

<footer class="blog-footer">
    <p>Jordany 2022</p>
    <p>
        <a href="#">Back to top</a>
    </p>
</footer>

</body>

</html>