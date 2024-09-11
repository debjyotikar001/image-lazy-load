<?php

namespace Debjyotikar001\ImageLazyLoad\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ImgLazyload
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($response->isSuccessful() && config('imagelazyload.enabled')) {
            $content = $response->getContent();

            // Replace all img src with data-src in the HTML content
            $content = preg_replace('/<img([^>]*?)src=/', '<img$1data-src=', $content);

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

                // Function to load images when in the viewport
                function loadImages() {
                const images = document.querySelectorAll('img');
                images.forEach(img => {
                    const dataSrc = img.getAttribute('data-src');
                    if (img.offsetParent !== null && dataSrc && isInViewport(img) && !img.getAttribute('data-loaded')) {
                    img.setAttribute('src', dataSrc);
                    img.setAttribute('data-loaded', 'true');
                    img.removeAttribute('data-src');
                    }
                });
                }

                // Listen to scroll, resize, and mousemove events to trigger image loading
                window.addEventListener('scroll', loadImages);
                window.addEventListener('resize', loadImages);
                window.addEventListener('mousemove', loadImages);

                // Call loadImages function
                loadImages();
                </script>";

            // JQuery code
            $jquery = "<script src='" . config('imagelazyload.jqueryUrl') . "'></script>
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

                // Function to load images when in the viewport
                function loadImages() {
                $('img').each(function() {
                    const img = $(this), dataSrc = img.data('src');
                    if (img.is(':visible') && dataSrc && isInViewport(this) && !img.data('loaded')) {
                    img.attr('src', dataSrc).data('loaded', true).removeAttr('data-src');
                    }
                });
                }

                // Listen to scroll, resize, and mousemove events to trigger image loading
                $(window).on('scroll resize mousemove', loadImages);

                // Call loadImages function
                loadImages();
                </script>";

            // If JQuery is enabled
            $javascriptCode = config('imagelazyload.jquery') ? $jquery : $javascript;
            
            // Add javascript code in the HTML content
            $content = str_replace('</body>', $javascriptCode . '</body>', $content);

            $response->setContent($content);
        }

        return $response;
    }
}
