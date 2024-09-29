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

      // img, iframe, video and audio
      $content = preg_replace('/<(img|iframe|source)([^>]*?)src=/', '<$1$2data-src=', $content);

      // style {background-image:url()}
      $content = preg_replace_callback(
        '/<([a-zA-Z]+)([^>]*?)style\s*=\s*"(.*?)background-image\s*:\s*url\((["\']?)(.*?)\4\)(.*?);?(.*?)"(.*?)>/',
        function ($matches) {
          $styleWithoutBg = trim(preg_replace('/background-image\s*:\s*url\((["\']?).*?\1\);?/', '', $matches[3]));
          $newStyle = !empty($styleWithoutBg) ? 'style="' . $styleWithoutBg . '"' : '';
          return "<{$matches[1]}{$matches[2]} $newStyle data-bg=\"{$matches[5]}\" {$matches[8]}>";
        },
        $content
      );

      $jsIsInViewport = "function isInViewport(e) {
          const r = e.getBoundingClientRect();
          return (
            r.top >= 0 && r.left >= 0 &&
            r.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            r.right <= (window.innerWidth || document.documentElement.clientWidth)
          );
        }";

      // JavaScript code
      $javascript = "<script>" . $jsIsInViewport . "
          function loadMedia() {
            const mediaElements = document.querySelectorAll('img, iframe, video source, audio source'),
              bgElements = document.querySelectorAll('[data-bg]');
            mediaElements.forEach(media => {
              const dataSrc = media.getAttribute('data-src');
              if (media.offsetParent !== null && dataSrc && isInViewport(media) && !media.getAttribute('data-loaded')) {
                media.setAttribute('src', dataSrc);
                media.setAttribute('data-loaded', 'true');
                media.removeAttribute('data-src');
              }
            });
            bgElements.forEach(bg => {
              const bgUrl = bg.getAttribute('data-bg');
              if (bgUrl && isInViewport(bg) && !bg.getAttribute('data-loaded-bg')) {
                bg.style.backgroundImage = 'url(' + bgUrl + ')';
                bg.setAttribute('data-loaded-bg', 'true');
                bg.removeAttribute('data-bg');
              }
            });
          }

          window.addEventListener('scroll', loadMedia);
          window.addEventListener('resize', loadMedia);
          window.addEventListener('mousemove', loadMedia);
          loadMedia();
        </script>";

      // JQuery code
      $jquery = "<script src='" . config('medialazyload.jqueryUrl') . "'></script><script>" . $jsIsInViewport . "
          function loadMedia() {
            $('img, iframe, video source, audio source').each(function() {
              const media = $(this), dataSrc = media.data('src');
              if (media.is(':visible') && dataSrc && isInViewport(this) && !media.data('loaded')) {
                media.attr('src', dataSrc).data('loaded', true).removeAttr('data-src');
              }
            });
            $('[data-bg]').each(function() {
              const bg = $(this), bgUrl = bg.data('bg');
              if (bgUrl && bg.is(':visible') && isInViewport(this) && !bg.data('loaded-bg')) {
                bg.css('background-image', 'url(' + bgUrl + ')').data('loaded-bg', true).removeAttr('data-bg');
              }
            });
          }

          $(window).on('scroll resize mousemove', loadMedia);
          loadMedia();
        </script>";

      $javascriptCode = config('medialazyload.jquery') ? $jquery : $javascript;
      $content = str_replace('</body>', $javascriptCode . '</body>', $content);

      $response->setContent($content);
    }

    return $response;
  }
}
