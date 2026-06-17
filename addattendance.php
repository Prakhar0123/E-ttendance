<table border="1" cellspacing="0">
    <form method="POST">
        <tr>
            <th>Employee Name</th>
            <th>P</th>
            <th>A</th>
            <th>L</th>
            <th>H</th>
        </tr>
        <?php
        require_once("config.php");
        $fetchingEmp = mysqli_query($db, "SELECT * FROM att") OR die(mysqli_error($db));
        
        while($data = mysqli_fetch_assoc($fetchingEmp)){
            $emp_name = $data['emp_name'];
            $empid = $data['id'];
            ?>
            <tr>
                <td><?php echo $emp_name; ?></td>
                <td><input type="checkbox" name="PreEmp[]" value="<?php echo $empid; ?>"></td>
                <td><input type="checkbox" name="AbsEmp[]" value="<?php echo $empid; ?>"></td>
                <td><input type="checkbox" name="LeavEmp[]" value="<?php echo $empid; ?>"></td>
                <td><input type="checkbox" name="HolEmp[]" value="<?php echo $empid; ?>"></td>
            </tr>
            <?php
        }
        ?>
        <tr>
            <td>Select Date (Optional)</td>
            <td colspan="4"><input type="date" name="selected_date" /></td>
        </tr>
        <tr>
            <th colspan="5"><input type="submit" name="attBTN" value="Submit"/></th>
        </tr>
    </form>
</table>

<?php
if(isset($_POST['attBTN'])){
    date_default_timezone_set("Asia/Kolkata");
    if(empty($_POST['selected_date'])){
        $selected_date = date("Y-m-d");
    } else {
        $selected_date = $_POST['selected_date'];
    }
    $att_mth = date("M", strtotime($selected_date));
    $att_yr = date("Y", strtotime($selected_date));
    function addOrUpdateAttendance($db, $empArray, $attendance, $selected_date, $att_mth, $att_yr) {
        foreach($empArray as $atd) {
            $checkQuery = "SELECT * FROM addatt WHERE empid = '$atd' AND curr_date = '$selected_date'";
            $checkResult = mysqli_query($db, $checkQuery) OR die(mysqli_error($db));

            if(mysqli_num_rows($checkResult) > 0) {
                $updateQuery = "UPDATE addatt SET attendance = '$attendance', att_mth = '$att_mth', att_yr = '$att_yr' WHERE empid = '$atd' AND curr_date = '$selected_date'";
                mysqli_query($db, $updateQuery) OR die(mysqli_error($db));
            } else {
                $insertQuery = "INSERT INTO addatt (empid, curr_date, att_mth, att_yr, attendance) VALUES ('$atd', '$selected_date', '$att_mth', '$att_yr', '$attendance')";
                mysqli_query($db, $insertQuery) OR die(mysqli_error($db));
            }
        }
    }
    if(isset($_POST['PreEmp']))  addOrUpdateAttendance($db, $_POST['PreEmp'], "P", $selected_date, $att_mth, $att_yr);
    if(isset($_POST['AbsEmp']))  addOrUpdateAttendance($db, $_POST['AbsEmp'], "A", $selected_date, $att_mth, $att_yr);
    if(isset($_POST['LeavEmp'])) addOrUpdateAttendance($db, $_POST['LeavEmp'], "L", $selected_date, $att_mth, $att_yr);
    if(isset($_POST['HolEmp']))  addOrUpdateAttendance($db, $_POST['HolEmp'], "H", $selected_date, $att_mth, $att_yr);
    echo "<script>
            alert('Attendance added/updated successfully!');
            window.location.href = window.location.pathname; 
          </script>";
}
?>
