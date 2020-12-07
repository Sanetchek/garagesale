<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <title><?php bloginfo( 'name' ); ?></title>
    <meta name="description" content="<?php bloginfo( 'description' ); ?>">

    <?php wp_head(); ?>
</head>

<body>
<?php get_template_part( 'template-parts/header/header', 'main' ); ?>

<main id="main">
    <div class="wrapper">