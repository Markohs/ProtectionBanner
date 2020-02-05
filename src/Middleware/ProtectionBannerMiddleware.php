<?php

namespace Markohs\ProtectionBanner\Middleware;

use Closure;
use Cookie;
use Crawler;
use Illuminate\Http\Request;
use Log;
use Redirect;
use Response;
//use GeoIP;

use Session;

class ProtectionBannerMiddleware
{
    private function isReddit(Request $request)
    {
        return strpos($request->header('User-Agent'), 'redditbot') !== false;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $ses_name = config('protectionbanner.ses_name');

        if ($ses_name == null || config('protectionbanner.disabled')) {
            // probably a config:cache production error, disable this middleware
            return $next($request);
        }

        if ($request->cookie($ses_name) || Crawler::isCrawler() || $this->IPExcluded($request) || $this->isWhitelisted($request) || $this->isReddit($request)) {
            // Already confirmed
            return $next($request);
        } elseif ($request->input($ses_name) == 'yes') {
            // User is confirming the conditions,

            // If this user comes from a referal, save it on the session
            if ($request->input('id')) {
                Session::put('saved_adult_affiliate_id', $request->input('id'));
            }

            // Log the IP, and explicit accept of conditions
            $this->logAccept($request);

            $newurl = $request->url();

            if ($request->input('utm_source') || $request->input('utm_medium') || $request->input('utm_campaign' || $request->input('utm_medium'))) {
                $newurl .= '';

                $extra_items = [];

                if ($request->input('utm_source')) {
                    array_push($extra_items, 'utm_source='.$request->input('utm_source'));
                }

                if ($request->input('utm_medium')) {
                    array_push($extra_items, 'utm_medium='.$request->input('utm_medium'));
                }

                if ($request->input('utm_campaign')) {
                    array_push($extra_items, 'utm_campaign='.$request->input('utm_campaign'));
                }

                if ($request->input('s')) {
                    array_push($extra_items, 's='.$request->input('s'));
                }

                $newurl .= '?'.implode($extra_items, '&');
            }

            $redirect = Redirect::away($newurl);
            $redirect->withCookie(cookie($ses_name, 'yes', 259200));
            $redirect->header('Location', $newurl);

            $redirect->setTargetUrl($newurl);

            return $redirect;
        }
        // Show the disclaimer

        return response()->view('protectionbanner::banner', compact('request'), 406);
    }

    private function logAccept($request)
    {
        $logchannel = config('protectionbanner.logchannel');

        if ($logchannel == null) {
            return false;
        }

        $message = 'Info: Client:'.$request->getClientIp().' clicked YES';
        Log::channel($logchannel)->info($message);
    }

    private function isWhitelisted($request)
    {
        if (config('protectionbanner.whitelist_url') == null) {
            return false;
        }

        $regex = '#'.implode('|', config('protectionbanner.whitelist_url')).'#';

        return preg_match($regex, $request->path());
    }

    private function IPExcluded($request)
    {
        //return ($this->getContinent($request)!="EU");
        return false;
    }

    private function getContinent(Request $request)
    {
        $ip = $request->getClientIp();

        return 'DISABLED';
//        return GeoIP::getLocation($request->getClientIp())['continent'];
    }
}
