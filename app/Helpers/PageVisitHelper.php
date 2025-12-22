<?php
namespace App\Helpers;

use App\Models\PageVisit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PageVisitHelper
{
    /* تشخیص Bot */
    protected static function detectVisitorType(): string
    {
        $ua = strtolower(request()->userAgent() ?? '');

        foreach ([
                     'bot','crawl','spider','slurp',
                     'telegrambot','whatsapp','discordbot',
                     'facebookexternalhit','bingpreview',
                     'yandex','baidu'
                 ] as $bot) {
            if (str_contains($ua, $bot)) {
                return 'bot';
            }
        }

        return 'human';
    }

    /* fingerprint */
    protected static function fingerprint(): ?string
    {
        return request()->cookie('fp');
    }

    /* page_key جنرال */
    public static function resolvePageKey(): string
    {
        $route = request()->route();

        if ($route) {
            foreach ($route->parameters() as $param) {
                if ($param instanceof Model) {
                    return Str::snake(class_basename($param)) . ':' . $param->getRouteKey();
                }
            }

            if ($route->getName()) {
                return 'route:' . $route->getName();
            }
        }

        return 'page:' . trim(request()->path(), '/');
    }

    /* ثبت بازدید */
    public static function register(): void
    {
        $visitorType = self::detectVisitorType();
        $pageKey     = self::resolvePageKey();
        $today       = now()->toDateString();
        $fingerprint = self::fingerprint();

        if ($visitorType === 'human' && !$fingerprint) {
            return;
        }

        PageVisit::firstOrCreate(
            [
                'page_key'   => $pageKey,
                'fingerprint'=> $visitorType === 'human' ? $fingerprint : null,
                'visit_date' => $today,
            ],
            [
                'visitor_type' => $visitorType,
                'ip'           => request()->ip(),
                'user_agent'   => request()->userAgent(),
            ]
        );
    }

    /* شمارش بازدید انسانی */
    public static function countHuman(string $pageKey): int
    {
        return PageVisit::where('page_key', $pageKey)
            ->where('visitor_type', 'human')
            ->count();
    }
}
