<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title> Fancybox </title>

	<meta name="theme-color" content="#f2f4f6">
    
    <!-- <link rel="stylesheet" href="https://fancyapps.com/assets/css/styles.8172b60e.css" id="theme-styles">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://unpkg.com/tailwindcss@2.2.19/dist/tailwind.min.css id="theme-styles"> -->

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fancyapps/ui/dist/fancybox.css" />

</head>
<body>

    <div class="container padding-top--md padding-bottom--lg">
        <h1 class="mt-12 mb-8 px-6 text-center text-lg md:text-2xl font-semibold">
        Disable image zoom animation
        </h1>

        <div class="flex flex-wrap gap-5 justify-center max-w-5xl mx-auto px-6">
        <a data-fancybox="gallery" href="https://lipsum.app/id/60/1600x1200">
            <img class="rounded" src="https://lipsum.app/id/60/200x150" />
        </a>
        <a data-fancybox="gallery" href="https://lipsum.app/id/61/1600x1200">
            <img class="rounded" src="https://lipsum.app/id/61/200x150" />
        </a>
        <a data-fancybox="gallery" href="https://lipsum.app/id/62/1600x1200">
            <img class="rounded" src="https://lipsum.app/id/62/200x150" />
        </a>
        <a data-fancybox="gallery" href="https://lipsum.app/id/63/1600x1200">
            <img class="rounded" src="https://lipsum.app/id/63/200x150" />
        </a>
        <a data-fancybox="gallery" href="https://lipsum.app/id/64/1600x1200">
            <img class="rounded" src="https://lipsum.app/id/64/200x150" />
        </a>
        <a data-fancybox="gallery" href="https://lipsum.app/id/65/1600x1200">
            <img class="rounded" src="https://lipsum.app/id/65/200x150" />
        </a>
        <a data-fancybox="gallery" href="https://lipsum.app/id/66/1600x1200">
            <img class="rounded" src="https://lipsum.app/id/66/200x150" />
        </a>
        <a data-fancybox="gallery" href="https://lipsum.app/id/67/1600x1200">
            <img class="rounded" src="https://lipsum.app/id/67/200x150" />
        </a>
        <a data-fancybox="gallery" href="https://lipsum.app/id/68/1600x1200">
            <img class="rounded" src="https://lipsum.app/id/68/200x150" />
        </a>
        <a data-fancybox="gallery" href="https://lipsum.app/id/69/1600x1200">
            <img class="rounded" src="https://lipsum.app/id/69/200x150" />
        </a>
        <a data-fancybox="gallery" href="https://lipsum.app/id/70/1600x1200">
            <img class="rounded" src="https://lipsum.app/id/70/200x150" />
        </a>
        <a data-fancybox="gallery" href="https://lipsum.app/id/71/1600x1200">
            <img class="rounded" src="https://lipsum.app/id/71/200x150" />
        </a>
        </div>
    </div>

	<!-- JS -->
	<!-- <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script> -->
	<!-- <script src="https://fancyapps.com/assets/js/runtime~main.60a3964f.js"></script> -->
    <!-- <script src="https://fancyapps.com/assets/js/main.00713ad2.js"></script> -->
    <script src="https://cdn.jsdelivr.net/npm/@fancyapps/ui@4.0/dist/fancybox.umd.js"></script>
<script>
Fancybox.bind('[data-fancybox="gallery"]', {
  Image: {
    zoom: false,
  },
});
</script>
</body>
</html>