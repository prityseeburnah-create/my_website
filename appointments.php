<h1>Appointments</h1>
<form method="post">
  <input type="text" name="customer_name" placeholder="Customer Name" required>
  <input type="text" name="car_model" placeholder="Car Model" required>
  <input type="date" name="date" required>
  <select name="status">
    <option>Pending</option>
    <option>Completed</option>
    <option>Cancelled</option>
  </select>
  <button type="submit" name="add">Add</button>
</form>
<?php
if (isset($_POST['add'])) {
    $stmt=$conn->prepare("INSERT INTO appointments (customer_name,car_model,date,status) VALUES (?,?,?,?)");
    $stmt->bind_param("ssss",$_POST['customer_name'],$_POST['car_model'],$_POST['date'],$_POST['status']);
    $stmt->execute(); header("Location: dashboard.php?page=appointments"); exit;
}
if (isset($_GET['del'])) {
    $conn->query("DELETE FROM appointments WHERE id=".intval($_GET['del']));
    header("Location: dashboard.php?page=appointments"); exit;
}
$res=$conn->query("SELECT * FROM appointments");
?>
<table>
<tr><th>ID</th><th>Customer</th><th>Car</th><th>Date</th><th>Status</th><th>Action</th></tr>
<?php while($row=$res->fetch_assoc()): ?>
<tr>
  <td><?=$row['id']?></td>
  <td><?=htmlspecialchars($row['customer_name'])?></td>
  <td><?=htmlspecialchars($row['car_model'])?></td>
  <td><?=$row['date']?></td>
  <td><?=$row['status']?></td>
  <td><a href="?page=appointments&del=<?=$row['id']?>">❌</a></td>
</tr>
<?php endwhile; ?>
</table>

<h2>Calendar</h2>
<div id='calendar'></div>
<script>
document.addEventListener('DOMContentLoaded',function(){
  var calendar=new FullCalendar.Calendar(document.getElementById('calendar'),{
    initialView:'dayGridMonth',
    events:[
      <?php
      $events=$conn->query("SELECT * FROM appointments");
      while($e=$events->fetch_assoc()){
        echo "{title:'".addslashes($e['customer_name'])."',start:'".$e['date']."'},";
      }
      ?>
    ]
  });
  calendar.render();
});
</script>
