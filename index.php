<?php
	require ("config.php");
	$link = mysqli_connect($db_host, $db_user, $db_pw, $db_name);

	header('Content-Type: text/html; charset=utf-8');
	if (!ini_get('date.timezone')) {
		date_default_timezone_set('Europe/Tallinn');
	}
	require_once 'inc/feed.php';
	Feed::$cacheDir = __DIR__ . '/tmp';
	Feed::$cacheExpire = '5 minutes';
	$rss = Feed::loadRss('https://www.err.ee/rss');
	$delete = "TRUNCATE TABLE article";
	mysqli_query($link, $delete);
?>

<h1><?php echo htmlSpecialChars($rss->title) ?></h1>

<?php foreach ($rss->item as $item): 
	$sql = "INSERT INTO article VALUES (DEFAULT, '".$item->title."', '".$item->link."', '".$item->description."', '".$item->pubDate."')";
	mysqli_query($link, $sql);
	endforeach;
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>ERR uudised</title>
</head>
<body>
	<ol> 
		<?php 
			$page = (isset($_GET['page'])) ? (int)$_GET['page'] : 1;
			$perPage = 10 * $page;

			$getArticle = "SELECT * FROM article LIMIT $perPage";
			$articles = mysqli_query($link, $getArticle);
			while($row = $articles->fetch_assoc()) {?>
				<li>
					<article>
						<h2><a href="<?php echo htmlSpecialChars($row["link"]) ?>"><?php echo htmlSpecialChars($row["title"]) ?></a></h2>
						<time datetime="<?php echo htmlSpecialChars($row["pubDate"])?>"><?php echo htmlSpecialChars($row["pubDate"])?></time>
						<p><?php echo htmlSpecialChars($row["description"])?></p>
					</article>
				</li>
			<?php }
		?>
	</ol>
	<?php 
		$query = "SELECT COUNT(*) as total FROM article";
		$pages = mysqli_query($link, $query);
		while($row = $pages->fetch_assoc()) {
			$totalPages = ceil($row['total'] / 10);

			$links = "";
			$nextPage = $page + 1;
			$links .= ($page < $totalPages) 
				? "<a href='index.php?page=$nextPage'>Loe veel</a> "
				: "Rohkem uudiseid ei ole.";
		}
		echo $links;
		mysqli_close($link);
	?>
</body>
</html>



