<?php
if (!isset($_SESSION['loggedin'])) {
    echo "<br/><br/><br/><div style='padding: 10px;'><h1>You are not logged in! :(</h1>You need to be logged in to Enter a contest.</div><br/><br/><br/>";
}
else {
    if ($judge['value'] != "Lockdown" || (isset($_SESSION['loggedin']) && $_SESSION['team']['status'] == 'Admin')) {
        if (isset($_GET['code'])) {
            $_GET['code'] = addslashes($_GET['code']);
            $query = "select * from contest where code = '$_GET[code]'";
            $contest = DB::findOneFromQuery($query);
            if ($contest) {

                $currtime = time();
                $endtime = $currtime + 1 * 60;
                $sql = "UPDATE contest SET starttime=$currtime , endtime = $endtime ,logindone='started' WHERE logindone='notstarted'";
                $time = DB::findOneFromQuery($sql);


                //newcode - to update the end time in admin table...bcz it contains timer settings
                $query1 = "select endtime from contest where logindone='started'";
                DB::query($query1);
                $result1 = DB::findAllFromQuery($query1);
                foreach ($result1 as $row1) {
                    $end_t = $row1['endtime'];
                    $sta_t = $row1['starttime'];
                }
                console.log($end_t);
                $sql_q = "UPDATE admin SET value=$end_t where variable='endtime' ";
                $time1 = DB::findOneFromQuery($sql_q);
                if ($time1)
                    echo "Successful time update";

                //new code

                ?>
                <script type='text/javascript'>

                    var ctime = <?php echo $contest['endtime'] - time(); ?>;
                    function zeroPad(num, places) {
                        var zero = places - num.toString().length + 1;
                        return Array(+(zero > 0 && zero)).join("0") + num;
                    }

                    function timer() {
                        if (ctime > 0) {
                            $("div#contesttimer").html("<h4>Ends in: " + parseInt(ctime / 3600) + ":" + zeroPad(parseInt((ctime / 60)) % 60, 2) + ":" + zeroPad(ctime % 60, 2) + "</h4>");
                            ctime--;
                            window.setTimeout("timer();", 1000);
                        } else {
                            $("div#contesttimer").html("<h4>Ends in: N/A</h4>");


                        }
                    }
                    timer();
                </script>
                <?php
                echo "<div class='contestheader'><h1 class='text-center'>$contest[name]</h1><div id='contesttimer'><h4>Starts in: N/A</h4></div></div>";
                if (isset($contest['starttime']) || (isset($_SESSION['loggedin']) && $_SESSION['team']['status'] == 'Admin')) {
                    if (isset($_SESSION['loggedin']) && $_SESSION['team']['status'] == 'Admin') {
                        if ($contest['starttime'] > time())
                            echo "<a class='btn btn-default pull-right' style='margin: 10px 0;' href='" . SITE_URL . "/preparecontest/$_GET[code]'><i class='glyphicon glyphicon-edit'></i>Prepare Contest</a>";
                        $query = "select * from problems where pgroup = '$_GET[code]' order by code";
                        echo "<a class='btn btn-default pull-right' style='margin: 10px 0;' href='" . SITE_URL . "/admincontest/$_GET[code]'><i class='glyphicon glyphicon-edit'></i> Edit</a>";
                    } else {
                        $query = "select * from problems where pgroup = '$_GET[code]' and status != 'Deleted' order by code";
                    }
                } else {
                    $query = "";
                }
                $prob = DB::findAllFromQuery($query);
                echo "<table class='table table-hover'><thead><tr><th>Name</th><th>Score</th><th>Code</th><th>Submissions</th></tr></thead>";
                if ($prob) {
                    foreach ($prob as $row) {
                        echo "<tr><td><a href='" . SITE_URL . "/problems/$row[code]'>$row[name]</a></td><td><a href='" . SITE_URL . "/problems/$row[code]'>$row[score]</a></td><td><a href='" . SITE_URL . "/submit/$row[code]'>$row[code]</a></td><td><a href='" . SITE_URL . "/status/$row[code]'>$row[solved]/$row[total]</a></td></tr>";
                    }
                }
                echo "</table><h3>Announcements</h3>$contest[announcement]";
            } else {
                echo "<br/><br/><br/><div style='padding: 10px;'><h1>Contest not Found :(</h1>The contest you are looking for is not found. Are you on the wrong website?</div><br/><br/><br/>";
            }
        } else {
            echo "<div class='text-center page-header'><h1>Contests</h1></div>";
            $query = "select * from contest order by starttime desc";
            $result = DB::findAllFromQuery($query);
            echo "<table class='table table-hover'>
            <thead><tr><th>Code</th><th>Name</th><th>Start Time</th><th>End Time</th></tr></thead>";
            foreach ($result as $row) {
                echo "<tr><td><a href='" . SITE_URL . "/contests/$row[code]'>$row[code]</a></td><td><a href='" . SITE_URL . "/contests/$row[code]'>$row[name]</a></td><td><a href='" . SITE_URL . "/contests/$row[code]'>" . date("d-M-Y, h:i:s a", $row['starttime']) . "</a></td><td><a href='" . SITE_URL . "/contests/$row[code]'>" . date("d-M-Y, h:i:s a", $row['endtime']) . "</a></td></tr>";
            }
            echo "</table>";
        }
    } else {
        echo "<br/><br/><br/><div style='padding: 10px;'><h1>Lockdown Mode :(</h1>This feature is now offline as Judge is in Lockdown mode.</div><br/><br/><br/>";
    }
}
?>
