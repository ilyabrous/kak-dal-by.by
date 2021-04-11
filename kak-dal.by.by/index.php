<?php
require "includes/config.php"
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<title><?php echo $config['title'] ?></title>

	<!-- Bootstrap Grid -->
	<link rel="stylesheet" type="text/css" href="/media/assets/bootstrap-grid-only/css/grid12.css">

	<!-- Google Fonts -->
	<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">

	<!-- Custom -->
	<link rel="stylesheet" type="text/css" href="/media/css/style.css">
</head>

<body>

	<div id="wrapper">

		<?php include("includes/header.php") ?>

		<div id="content">
			<div class="container">
				<div class="row">
					<section class="content__left col-md-8">


						<div class="block">
							<a href="/articles.php">Все записи</a>
							<h3>Новейшее_в_блоге</h3>
							<div class="block__content">
								<div class="articles articles__horizontal">

									<?php
									$articles = mysqli_query($connection, "SELECT * FROM `articles` ORDER BY `id` DESC LIMIT 10 ");
									?>

									<?php
									while ($art = mysqli_fetch_assoc($articles)) {
									?>
										<article class="article">
											<div class="article__image" style="background-image: url('/static/images/<?php echo $art['image'] ?>')"></div>
											<div class="article__info">
												<a href="/article.php?id=<?php echo $art['id'] ?>"><?php echo $art['title'] ?></a>
												<div class="article__info__meta">
													<?php
													$art_cat = false;
													foreach ($categories as $cat) {
														if ($art['categorie_id'] == $cat['id']) {
															$art_cat = $cat;
															break;
														}
													}
													?>
													<small>Категория: <a href="/articles.php?categorie=<?php echo $art_cat['id'] ?>"><?php echo $art_cat['title'] ?></a></small>
												</div>
												<div class="article__info__preview"><?php echo mb_substr(strip_tags($art['text']), 0, 101, 'utf-8') . '...' ?></div>
											</div>
										</article>
									<?php
									}
									?>
								</div>
							</div>
						</div>


						<?php
						$cat_id = 1; //все статьи с такой категорией
						$MAX_ID_OBJECT = mysqli_query($connection, "SELECT MAX(id) FROM `articles_categories` ");
						$MAX_ID = mysqli_fetch_assoc($MAX_ID_OBJECT); // кол-во категорий


						//из бд - текущие статьи по ид
						while ($cat_id <= $MAX_ID['MAX(id)']) {
							$currentCategorieObject = mysqli_query($connection, "SELECT * FROM `articles_categories` WHERE `id` = $cat_id"); // текущая категория
							$currentsArticles = mysqli_query($connection, "SELECT * FROM `articles` WHERE `categorie_id` = $cat_id ORDER BY `id` DESC LIMIT 10"); // текущие статьи

							$arts = array(); // статьи
							while ($art_p = mysqli_fetch_assoc($currentsArticles)) {
								$arts[] = $art_p;
							}
							if ($arts == array()) {
								$cat_id++;
								continue;
							}

							$art_cat = mysqli_fetch_assoc($currentCategorieObject);
						?>


							<div class="block">
								<a href="/articles.php?categorie=<?php echo $art_cat['id'] ?>">Все записи</a>
								<h3><?php echo $art_cat['title'] . " [Новейшее]" ?></h3>
								<div class="block__content">
									<div class="articles articles__horizontal">
										<?php foreach ($arts as $art) {

										?>
											<article class="article">
												<div class="article__image" style="background-image: url('/static/images/<?php echo $art['image'] ?>')"></div>
												<div class="article__info">
													<a href="/article.php?id=<?php echo $art['id'] ?>"><?php echo $art['title'] ?></a>
													<div class="article__info__meta">
														<small>Категория: <a href="/articles.php?categorie=<?php echo $art_cat['id'] ?>"><?php echo $art_cat['title'] ?></a></small>
													</div>
													<div class="article__info__preview"><?php echo mb_substr(strip_tags($art['text']), 0, 101, 'utf-8') . '...' ?></div>
												</div>
											</article>
										<?php
										}
										?>
									</div>
								</div>
							</div>
						<?php
							$cat_id++;
						}
						?>


					</section>
					<section class="content__right col-md-4">
						<?php include "includes/sidebar.php" ?>
					</section>
				</div>
			</div>
		</div>

		<?php include("includes/footer.php") ?>

	</div>

</body>

</html>