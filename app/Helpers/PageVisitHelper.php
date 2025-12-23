<?php

namespace App\Helpers;

use App\Models\PageVisit;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Jenssegers\Agent\Agent;
use Illuminate\Support\Str;

class PageVisitHelper
{
    /* ========= Fingerprint ========= */

    protected static function resolveFingerprint(): string
    {
        return request()->cookie('fp')
            ?? sha1(Request::ip() . Request::userAgent());
    }

    /* ========= Page Key ========= */

    public static function resolvePageKey(): string
    {
        $route = request()->route();

        if (!$route) {
            return 'page:' . trim(request()->path(), '/');
        }

        foreach ($route->parameters() as $param) {

            // Model binding
            if (is_object($param) && isset($param->id)) {
                return Str::snake(class_basename($param)) . ':' . $param->getRouteKey();
            }

            // numeric or slug param
            if (is_numeric($param) || is_string($param)) {
                return $route->getName()
                    ? $route->getName() . ':' . $param
                    : 'path:' . trim(request()->path(), '/');
            }
        }

        return $route->getName()
            ? 'route:' . $route->getName()
            : 'page:' . trim(request()->path(), '/');
    }

    /* ========= Record Visit ========= */

    public static function record(): void
    {
        $agent = new Agent();

        if ($agent->isRobot()) {
            return;
        }

        $fingerprint = self::resolveFingerprint();
        $pageKey     = self::resolvePageKey();

        $exists = PageVisit::query()
            ->where('page_key', $pageKey)
            ->where('fingerprint', $fingerprint)
            ->whereDate('created_at', now()->toDateString())
            ->exists();

        if ($exists) {
            return;
        }

        PageVisit::create([
            'page_key'    => $pageKey,
            'fingerprint' => $fingerprint,
            'user_id'     => Auth::id(),
            'ip'          => Request::ip(),
            'user_agent'  => Request::userAgent(),
            'is_bot'      => false,
        ]);
    }

    /* ========= Count ========= */

    public static function countHuman(string $pageKey): int
    {
        return PageVisit::where('page_key', $pageKey)
            ->where('is_bot', false)
            ->count();
    }

}
