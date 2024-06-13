<!DOCTYPE html>
<html>
<head>
	<title>Data from Database</title>
	<style>
		table {
			border-collapse: collapse;
			width: 50%;
		}
		
		th, td {
			border: 1px solid #ddd;
			padding: 8px;
			text-align: left;
		}
		
		th {
			background-color: #f0f0f0;
		}
        img{
            width: 401px;
        }
	</style>
</head>
<body>
	<h1>Data from Database</h1>
	
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
		<input type="text" name="search" placeholder="Search...">
		<button type="submit">Search</button>
	</form>
	
	<?php
	$con = mysqli_connect('localhost', 'root', '', 'jsondata');

	// Check connection
	if (mysqli_connect_errno()) {
		echo "Failed to connect to MySQL: ". mysqli_connect_error();
		exit();
	}

	// Search functionality
	if (isset($_GET['search'])) {
		$search = mysqli_real_escape_string($con, $_GET['search']);
		$query = "SELECT * FROM data WHERE title LIKE '%$search%' OR description LIKE '%$search%' OR link LIKE '%$search%' OR guid LIKE '%$search%' OR pubDate LIKE '%$search%' OR dc_creator LIKE '%$search%' OR enclosure_type LIKE '%$search%' OR enclosure_url LIKE '%$search%'";
	} else {
		$query = "SELECT * FROM data";
	}

	// Sorting functionality
	if (isset($_GET['sort'])) {
		$sort = mysqli_real_escape_string($con, $_GET['sort']);
		$query .= " ORDER BY $sort";
	}

	// Pagination functionality
	$limit = 10;
	$page = (isset($_GET['page'])) ? $_GET['page'] : 1;
	$offset = ($page - 1) * $limit;
	$query .= " LIMIT $limit OFFSET $offset";

	$result = mysqli_query($con, $query);

	if (mysqli_num_rows($result) > 0) {
		echo "<table border='1'>";
		echo "<tr><th><a href='?sort=title'>Title</a></th><th><a href='?sort=description'>Description</a></th><th><a href='?sort=link'>Link</a></th><th><a href='?sort=guid'>GUID</a></th><th><a href='?sort=pubDate'>Pub Date</a></th><th><a href='?sort=dc_creator'>DC Creator</a></th><th><a href='?sort=enclosure_type'>Enclosure Type</a></th><th><a href='?sort=enclosure_url'>Enclosure URL</a></th></tr>";

		while($row = mysqli_fetch_assoc($result)) {
			echo "<tr>";
			echo "<td>". $row['title']. "</td>";
			echo "<td style= width: 10%>". $row['description']. "</td>";
			echo "<td><a href='". $row['link']. "'>". $row['link']. "</a></td>";
			echo "<td>". $row['guid']. "</td>";
			echo "<td>". $row['pubDate']. "</td>";
			echo "<td>". $row['dc:creator']. "</td>";
			echo "<td>". $row['enclosure']. "</td>";
			echo "<td><a href='". $row['enclosure_url']. "'>". $row['enclosure_url']. "</a></td>";
			echo "</tr>";
		}



		echo "</table>";

		// Pagination links
		$total_rows = mysqli_num_rows(mysqli_query($con, "SELECT * FROM data"));
		$total_pages = ceil($total_rows / $limit);
		echo "<br>Pagination: ";
		for ($i = 1; $i <= $total_pages; $i++) {
			echo "<a href='?page=$i'>Page $i</a> ";
		}
	} else {
		echo "0 results";
	}

	mysqli_close($con);
	?>
	
</body>
</html>