<title>{{ $seo['title'] }}</title>
<meta name="description" content="{{ $seo['description'] }}">
<link rel="canonical" href="{{ $seo['canonical'] }}">

<meta property="og:title" content="{{ $seo['og_title'] }}">
<meta property="og:description" content="{{ $seo['og_description'] }}">
<meta property="og:url" content="{{ $seo['og_url'] }}">
<meta property="og:type" content="{{ $seo['og_type'] }}">
@if (! empty($seo['og_image']))
<meta property="og:image" content="{{ $seo['og_image'] }}">
@endif

<meta name="twitter:card" content="{{ $seo['twitter_card'] }}">
<meta name="twitter:title" content="{{ $seo['og_title'] }}">
<meta name="twitter:description" content="{{ $seo['og_description'] }}">
@if (! empty($seo['og_image']))
<meta name="twitter:image" content="{{ $seo['og_image'] }}">
@endif
