<h2>Admin page</h2>

<p><strong></strong></p>

<p><strong></strong></p>

<h4><?php echo form_error('username')?></h4>
<h4><?php echo form_error('password')?></h4>
<h4><?php echo form_error('accesslevel')?></h4>

<h3>User Table</h3>

<table class="table">
  <thead class="thead-dark">
    <tr>
      <th scope="col">Delete</th>
      <th scope="col">Freeze</th>
      <th scope="col">Username</th>
      <th scope="col">Password</th>
      <th scope="col">Access level</th>
      <th scope="col">Frozen</th>
    </tr>
  </thead>

  <? foreach ($users as $row) { ?>
	<tr>
	 <td><a href="<?= base_url(); ?>index.php?/Admin/deleteuser/<?= $row['compid'] ?>">D</a></td>
	 <td><a href="<?= base_url(); ?>index.php?/Admin/freezeuser/<?= $row['compid'] ?>">F</a></td>
	 <td><?= $row['username'] ?></td>
	 <td><?= $row['password'] ?></td>
	 <td><?= $row['accesslevel'] ?></td>
	 <td><?= $row['freeze'] ?></td>
 	</tr>
  <? } ?>
</table>

<br>

  <form action="<?= base_url(); ?>index.php?/Admin/createuser" method="post" accept-charset="utf-8">
  <fieldset>
<legend>Add new user</legend>
  <label for="username">Username:</label> <br>
  <input type="text" name="username" value="<?php echo set_value('username'); ?>" id="username" />
 <br>
  <label for="password">Password:</label> <br>
  <input type="text" name="password" value="<?php echo set_value('password'); ?>" id="password" />
 <br>
  <label for="accesslevel">Access Level:</label> <br>
  <input type="text" name="accesslevel" value="<?php echo set_value('accesslevel'); ?>" id="accesslevel" />
 <br>
  <input type="submit" name="submit" value="Submit"  />
  </fieldset>  </form>

<br>