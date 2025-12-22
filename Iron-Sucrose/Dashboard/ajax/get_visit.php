<?php include('../includes/config.php'); ?>
<?php include('../includes/functions.php'); ?>
<?php

if (isset($_POST['idd'])) {
    $id = $_POST['idd'];

    $qry = "SELECT pw_id,followup,dose,visit_date,ivr_scheduled_status,visit_status,callid,updated_date FROM pw_iron_visit WHERE pw_id='$id'";
    $res = mysqli_query($conn, $qry);

    if (mysqli_num_rows($res) > 0) {

        echo <<<DATA
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>PW ID</th>
                            <th>Follow-up</th>
                            <th>Dose</th>
                            <th>Scheduled date</th>
                            <th>Visit date</th>
                            <th>Visit Status</th>
                            <th>Call Status</th>
                        </tr>
                    </thead>
                    <tbody>
                DATA;

        while ($row = mysqli_fetch_object($res)) {
            $vst = ($row->visit_status == '0') ? 'pending' : 'done';
            $cls = ($row->visit_status == '1') ? 'send' : 'pending';
            $vst_dt = ($vst == 'done') ? $row->updated_date : '';
            echo <<<ROW
                            <tr>
                                <td>{$row->pw_id}</td>
                                <td>{$row->followup}</td>
                                <td>{$row->dose}</td>
                                <td>{$row->visit_date}</td>
                                <td>{$vst_dt}</td>
                                <td>{$vst}</td>
                                <td>{$cls}</td>
                            </tr>
                            ROW;
        }

        echo <<<DATA
                    </tbody>
                </table>
        DATA;
    } else {
        echo "<center><h2>Record Not Found!</h2></center>";
    }
}
