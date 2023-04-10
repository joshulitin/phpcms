<?php ob_start(); ?>
<?php include "includes/admin_header.php"; ?>
<?php
$postCount = countRecords(getUserPost());
$commentCount = countRecords(getUserComments());
$categoryCount = countRecords(getUserCategories());
$postPublishCount = countRecords(getUserPublishPost());
$postDraftCount = countRecords(getUserDraftPost());
$approveCommentCount = countRecords(getUserApproveComments());
$unApproveCommentCount = countRecords(getUserunApproveComments());
?>


<div id="wrapper">

<!-- Navigation -->
<?php include "includes/admin_navigation.php"; ?>
<div id="page-wrapper">

    <div class="container-fluid">

    <!-- Page Heading -->
    <div class="row">
        <div class="col-lg-12">
            <h1 class="page-header">
                Welcome <?php echo strtoupper(getUsername()); ?>
            </h1>   
        </div>
    </div>
        <!-- /.row -->
                
<div class="row">
    <!-- Posts -->
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-file-text fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                    <?php echo "<div class='huge'>" . $postCount . "</div>"; ?>
                        <div>Posts</div>
                    </div>
                </div>
            </div>
            <a href="posts.php">
                <div class="panel-footer">
                    <span class="pull-left">View Posts</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <!-- Comments -->
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-green">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-comments fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                    <?php echo "<div class='huge'>" .
                      $commentCount .
                      "</div>"; ?>
                      <div>Comments</div>
                    </div>
                </div>
            </div>
            <a href="comments.php">
                <div class="panel-footer">
                    <span class="pull-left">View Comments</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
    <!-- Categories -->
    <div class="col-lg-4 col-md-6">
        <div class="panel panel-red">
            <div class="panel-heading">
                <div class="row">
                    <div class="col-xs-3">
                        <i class="fa fa-list fa-5x"></i>
                    </div>
                    <div class="col-xs-9 text-right">
                    <?php echo "<div class='huge'>" .
                      $categoryCount .
                      "</div>"; ?>
                         <div>Categories</div>
                    </div>
                </div>
            </div>
            <a href="categories.php">
                <div class="panel-footer">
                    <span class="pull-left">View Categories</span>
                    <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                    <div class="clearfix"></div>
                </div>
            </a>
        </div>
    </div>
</div>
<!-- /.row -->


<div class="row">
<script type="text/javascript">
    google.charts.load('current', {'packages':['bar']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Data', 'Count'],
        <?php
        $element_text = [
          "All Posts",
          "Active Posts",
          "Draft Posts",
          "Comments",
          "Approve Comments",
          "Unapprove Comments",
          "Categories",
        ];
        $element_count = [
          "$postCount",
          "$postPublishCount",
          "$postDraftCount",
          "$commentCount",
          "$approveCommentCount",
          "$unApproveCommentCount",
          "$categoryCount",
        ];
        for ($i = 0; $i < 6; $i++) {
          echo "['{$element_text[$i]}'" . "," . "{$element_count[$i]}],";
        }
        ?>
    //   ['Post', 1000],
    
    ]);

    var options = {
        chart: {
        title: '',
        subtitle: '',
        }
    };

    var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

    chart.draw(data, google.charts.Bar.convertOptions(options));
    }
</script>
<div id="columnchart_material" style="width: 'auto'; height: 500px;"></div>
</div>

</div>
<!-- /.container-fluid -->

</div>
<!-- /#page-wrapper -->
<?php include "includes/admin_footer.php"; ?>
