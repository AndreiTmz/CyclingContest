</div>
<div class="fclear"></div>
		<div id="footer">
			<div class="fleft">Name: Andrei T.</div>
			<div class="fcenter">School: CMC Sinaia</div>
			<div class="fright">Class: XII B</div>
		</div>
		<div class="admin">
			<a href="admin.php">Administration</a> 
			<?php 
				if (!empty($_SESSION['authenticated']))
				{
			?>
				| <a href="logout.php">Logout</a>	
			<? 
				}
			?>
		</div>
</div>
</body>
</html>
