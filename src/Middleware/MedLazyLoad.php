<?php

namespace Debjyotikar001\MediaLazyLoad\Middleware;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MedLazyLoad
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->isSuccessful() && config('medialazyload.enabled')) {
            $content = $response->getContent();

            if (!in_array(config('app.env'), explode(',', config('medialazyload.allowed_envs')))) { return $response; }

            if (!empty(config('medialazyload.skip_urls'))) {
                $currentUrl = $request->path();
                foreach (config('medialazyload.skip_urls') as $item) {
                  if (Str::is($item, $currentUrl)) { return $response; }
                }
            }

            // Replace all img, iframe, video and audio src attributes with data-src
            $content = preg_replace('/<(img|iframe|source)([^>]*?)src=/', '<$1$2data-src=', $content);

            // JavaScript code
            $javascript = "<script>
                // Function to check if an element is in the viewport
                function isInViewport(element) {
                const rect = element.getBoundingClientRect();
                return (
                    rect.top >= 0 && rect.left >= 0 &&
                    rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                    rect.right <= (window.innerWidth || document.documentElement.clientWidth)
                );
                }

                // Function to load media (images, iframes, videos and audios) when in the viewport
                function loadMedia() {
                const mediaElements = document.querySelectorAll('img, iframe, video source, audio source');
                mediaElements.forEach(media => {
                    const dataSrc = media.getAttribute('data-src');
                    if (media.offsetParent !== null && dataSrc && isInViewport(media) && !media.getAttribute('data-loaded')) {
                    media.setAttribute('src', dataSrc);
                    media.setAttribute('data-loaded', 'true');
                    media.removeAttribute('data-src');
                    }
                });
                }

                // Listen to scroll, resize, and mousemove events to trigger media loading
                window.addEventListener('scroll', loadMedia);
                window.addEventListener('resize', loadMedia);
                window.addEventListener('mousemove', loadMedia);

                // Call loadMedia function
                loadMedia();
                </script>";

            // JQuery code
            $jquery = "<script src='" . config('medialazyload.jqueryUrl') . "'></script>
                <script>
                // Function to check if an element is in the viewport
                function isInViewport(element) {
                const rect = element.getBoundingClientRect();
                return (
                    rect.top >= 0 && rect.left >= 0 &&
                    rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
                    rect.right <= (window.innerWidth || document.documentElement.clientWidth)
                );
                }

                // Function to load media (images, iframes, videos and audios) when in the viewport
                function loadMedia() {
                $('img, iframe, video source, audio source').each(function() {
                    const media = $(this), dataSrc = media.data('src');
                    if (media.is(':visible') && dataSrc && isInViewport(this) && !media.data('loaded')) {
                    media.attr('src', dataSrc).data('loaded', true).removeAttr('data-src');
                    }
                });
                }

                // Listen to scroll, resize, and mousemove events to trigger image loading
                $(window).on('scroll resize mousemove', loadMedia);

                // Call loadMedia function
                loadMedia();
                </script>";

            $javascriptCode = config('medialazyload.jquery') ? $jquery : $javascript;

            // Add javascript code in the HTML content
            $content = str_replace('</body>', $javascriptCode . '</body>', $content);

            $response->setContent($content);
        }

        return $response;
    }
}
