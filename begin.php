<?php
require_once 'config.php';
require_once 'components.php';
$_SESSION['url'] = $_SERVER['REQUEST_URI']; // used by process.php to send to last visited page
$query = "select value from admin where variable='mode'";
$judge = DB::findOneFromQuery($query);
if ($judge['value'] == 'Lockdown' && isset($_SESSION['loggedin']) && !isAdmin()) {
    session_destroy();
    session_regenerate_id(true);
    session_start();
    $_SESSION['msg'] = "Judge is in Lockdown mode and so you have been logged out.";
    redirectTo(SITE_URL);
}
doCompetitionCheck(); //Activate competition when planned
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <link type="text/css" rel="stylesheet" href="<?php echo SITE_URL ?>/css/bootstrap.css" media="screen" />
        <link type="text/css" rel="stylesheet" href="<?php echo SITE_URL ?>/css/style.css" media="screen" />
        <script type="text/javascript" src="<?php echo SITE_URL ?>/js/jquery-3.1.0.min.js"></script>
        <script type="text/javascript" src="<?php echo SITE_URL ?>/js/bootstrap.js"></script>
        <script type="text/javascript" src="<?php echo SITE_URL ?>/js/plugin.js"></script>
        <script type="text/javascript">
            $(window).load(function() {
                if ($('#sidebar').height() < $('#mainbar').height())
                    $('#sidebar').height($('#mainbar').height());
            });
        </script>
        <title>CropIn</title>
        <link rel='shortcut icon' href='<?php echo SITE_URL; ?>/img/cropin.jpg' />
    </head>
    <body>
        <?php if ($judge['value'] == 'Active' && isset($_SESSION['loggedin'])) { ?>
            <script type='text/javascript'>
                function settitle() {
                    var t = window.document.title;
                    var n = t.match(/(\d*)\)/gi);
                    console.log(n);
                    if (n != null) {
                        n = parseInt(n) + 1;
                    } else {
                        n = 1;
                    }
                    window.document.title = "(" + n + ") CropIn";
                }
                function resettile() {
                    $.ajax({
                        type: "GET",
                        url: "<?php echo SITE_URL; ?>/broadcast.php",
                        data: {updatetime: ""}
                    });
                    window.document.title = "Aurora";
                }
                window.setTimeout("bchk();", <?php echo rand(300000, 600000); ?>);
                $.ajax("<?php echo SITE_URL; ?>/broadcast.php").done(function(msg) {
                    var json = eval('(' + msg + ')');
                    console.log(msg);
                    if (json.broadcast.length != 0) {
                        var str, i;
                        str = "";
                        for (i = 0; i < json.broadcast.length; i++)
                            str += "<b>" + json.broadcast[i].title + ":</b><br/>" + json.broadcast[i].msg + "<br/><br/>";
                        $("#bmsg").html(str);
                        $('#myModal').on('hidden.bs.modal', function() {
                            resettile();
                        });
                        $("#myModal").modal('show');
                        settitle();
                    }
                });
                function bchk() {
                    $.ajax("<?php echo SITE_URL; ?>/broadcast.php").done(function(msg) {
                        var json = eval('(' + msg + ')');
                        console.log(msg);
                        if (json.broadcast.length != 0) {
                            var str, i;
                            str = "";
                            for (i = 0; i < json.broadcast.length; i++)
                                str += "<b>" + json.broadcast[i].title + ":</b><br/>" + json.broadcast[i].msg + "<br/><br/>";
                            $("#bmsg").html(str);
                            $('#myModal').on('hidden.bs.modal', function() {
                                resettile();
                            });
                            $("#myModal").modal('show');
                            settitle();
                        }
                    });
                    window.setTimeout("bchk();", 600000);
                }
            </script>
            <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                            <h4 class="modal-title" id="myModalLabel">Alert</h4>
                        </div>
                        <div class="modal-body" id="bmsg">

                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        </div>
                    </div><!-- /.modal-content -->
                </div><!-- /.modal-dialog -->
            </div>
        <?php }
        ?>
        <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="<?php echo SITE_URL; ?>"><img src="<?php echo SITE_URL; ?>/img/cropin.jpg" style="display: inline-block;" width="70px" height="35px">
                        &nbsp;CropIn Technology Solutions
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="navbar-collapse-1">
                    <ul class="nav navbar-nav">
                                <li><a href="<?php echo SITE_URL; ?>/contact">Contact Us</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div> 
        </nav> 
        <div class="container bodycont">



            <table class="table table-striped">
                <tbody>
                <tr>
                    <th>Important Rules and Regulations </th>
                </tr>
                <tr>
                    <td style="text-align: justify">
                        <ul>
                            <li>Please Note that once you start the exam your timer will begin
                                and Judge would turn off automatically and codes wont be judges after that time.<br><br>
                            <li>
                                Time alloted is 1 Hr 30 Mins
                            </li><br>
                            <li>
                                 You will be redirected to a Contest Page , where 3 Question would be there , you have to attempt all three questions
                            </li><br>
                            <li>
                                 Allowed Languages are C/C++, Java , Python , Perl , Ruby , BrainFuck.
                            </li> <br>
                            <li>
                                For java users follow the Code Submission type of CodeChef IO.
                            </li>
                        </ul>
                    </td>
                </tr>
                </tbody>
            </table>



            <div class='row'>

                <div class='col-md-6'>


    <form action="contests/hire1" method="post">
        <input type="submit" name="begintest" value="Begin test" class="btn btn-primary btn-lg btn-block" />
    </form>


                    <?php
                    /* My Submissions Panel
                    if (isset($_SESSION['loggedin'])) mysubs();
                    /* Latest Submissions Panel
                    if ($judge['value'] == 'Active') latestsubs();
                    ?>

                </div>
            </div>
        </div>
        <div class="footer">
            
        </div>
    </body>
</html>
